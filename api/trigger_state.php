<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';
$pdo = getDBConnection();

$action = $_GET['action'] ?? '';
$elderly_user_id = $_SESSION['user_id'] ?? null;
$elderly_name = $_SESSION['first_name'] ?? null;

$patient = null;
if ($elderly_user_id) {
    $stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id = ?");
    $stmt->execute([$elderly_user_id]);
    $patient = $stmt->fetch();

    if (!$patient) {
        $stmt = $pdo->prepare("SELECT id FROM patients WHERE first_name = ? LIMIT 1");
        $stmt->execute([$elderly_name]);
        $patient = $stmt->fetch();
        if ($patient) {
            $pdo->prepare("UPDATE patients SET user_id = ? WHERE id = ?")->execute([$elderly_user_id, $patient['id']]);
        }
    }
}

if ($action === 'sos_on' && $patient) {
    // Activates the native Caregiver Web Dashboard Siren (flashes screen red and plays alarm sound)
    $pdo->prepare("UPDATE patients SET emergency_active = TRUE WHERE id = ?")->execute([$patient['id']]);
}

if ($action === 'sos_off' && $patient) {
    $pdo->prepare("UPDATE patients SET emergency_active = FALSE WHERE id = ?")->execute([$patient['id']]);
}

if ($action === 'med_taken' && $patient) {
    $med = $_GET['med'] ?? '';
    if ($med) {
        $insert = $pdo->prepare("INSERT INTO medicine_logs (patient_id, medicine_name) VALUES (?, ?)");
        $insert->execute([$patient['id'], $med]);
    }
}
echo json_encode(['success' => true]);
?>