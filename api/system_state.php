<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id']))
    exit(json_encode(['state' => 'normal']));

require_once '../config/database.php';
$pdo = getDBConnection();
$elderly_user_id = $_SESSION['user_id'];
$elderly_name = $_SESSION['first_name'];

$stmt = $pdo->prepare("SELECT id, emergency_active FROM patients WHERE user_id = ?");
$stmt->execute([$elderly_user_id]);
$patient = $stmt->fetch();

if (!$patient) {
    $stmt = $pdo->prepare("SELECT id, emergency_active FROM patients WHERE first_name = ? LIMIT 1");
    $stmt->execute([$elderly_name]);
    $patient = $stmt->fetch();
    if ($patient) {
        $pdo->prepare("UPDATE patients SET user_id = ? WHERE id = ?")->execute([$elderly_user_id, $patient['id']]);
    }
}

if (!$patient) {
    echo json_encode(['state' => 'normal']);
    exit;
}

// 1. Check Explicit Database Emergency Flag
if ($patient['emergency_active']) {
    echo json_encode(['state' => 'emergency', 'message' => 'SOS Triggered explicitly']);
    exit;
}

// 2. Poll Database logs for today's medicines
date_default_timezone_set('Asia/Kolkata');
$currentHour = (int) date('H');
$currentPeriod = '';
if ($currentHour >= 5 && $currentHour < 11)
    $currentPeriod = 'Morning';
else if ($currentHour >= 11 && $currentHour < 16)
    $currentPeriod = 'Afternoon';
else if ($currentHour >= 16 && $currentHour < 21)
    $currentPeriod = 'Evening';
else
    $currentPeriod = 'Night';

$med_stmt = $pdo->prepare("SELECT medicine_name, dosage, times_of_day, notes FROM medicine_schedule WHERE patient_id = ?");
$med_stmt->execute([$patient['id']]);
$meds = $med_stmt->fetchAll();

$due_meds = [];

$log_stmt = $pdo->prepare("SELECT medicine_name FROM medicine_logs WHERE patient_id = ? AND DATE(taken_at) = CURDATE()");
$log_stmt->execute([$patient['id']]);
$taken_today = $log_stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($meds as $med) {
    if (strpos(strtolower($med['times_of_day']), strtolower($currentPeriod)) !== false) {
        if (!in_array($med['medicine_name'], $taken_today)) {
            $due_meds[] = $med;
        }
    }
}

// Demo Helper Override 
if (isset($_GET['force_state'])) {
    if ($_GET['force_state'] === 'medicine') {
        if (count($meds) > 0)
            $due_meds = [$meds[0]];
        else
            $due_meds = [['medicine_name' => 'Demo Pill', 'dosage' => '20mg', 'notes' => 'Take with water.']];
    } elseif ($_GET['force_state'] === 'emergency') {
        echo json_encode(['state' => 'emergency', 'message' => 'Emergency Triggered via override']);
        exit;
    } elseif ($_GET['force_state'] === 'normal') {
        $due_meds = [];
    }
}

if (count($due_meds) > 0) {
    echo json_encode(['state' => 'medicine', 'medicines' => $due_meds]);
    exit;
}

echo json_encode(['state' => 'normal']);
?>