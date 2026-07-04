<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'Elderly') {
    echo json_encode(['error' => 'Unauthorized Access']);
    exit;
}

require_once '../config/database.php';
require_once '../ai/ConversationService.php';

$pdo = getDBConnection();
$elderly_user_id = $_SESSION['user_id'];
$elderly_name = $_SESSION['first_name'];

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

if (!$patient) {
    echo json_encode(['error' => 'Patient profile not linked in database.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$message = $data['message'] ?? '';
if (empty($message)) {
    echo json_encode(['error' => 'Empty message payload.']);
    exit;
}

try {
    // 6. chat.php API requirement fulfilled via unified class routing
    $aiService = new ConversationService($pdo);
    $response = $aiService->processMessage($patient['id'], $message);

    echo json_encode(['response' => $response['text'], 'action' => $response['action']]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Pipeline Failure: ' . $e->getMessage() . ' | Line: ' . $e->getLine()]);
}
?>