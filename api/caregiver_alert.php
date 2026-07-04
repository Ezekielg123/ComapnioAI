<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'Caregiver')
    exit(json_encode(['active' => false]));

require_once '../config/database.php';
$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'resolve') {
    $pdo->prepare("UPDATE patients SET emergency_active = FALSE WHERE caregiver_id = ?")->execute([$_SESSION['user_id']]);
    exit(json_encode(['success' => true]));
}

$stmt = $pdo->prepare("SELECT first_name, last_name FROM patients WHERE caregiver_id = ? AND emergency_active = TRUE");
$stmt->execute([$_SESSION['user_id']]);
$emergencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['active' => count($emergencies) > 0, 'patients' => $emergencies]);
?>