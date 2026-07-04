<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/database.php';
require_once 'config/ai.php';
require_once 'ai/ConversationService.php';

try {
    $pdo = getDBConnection();

    // Forcefully ensure the column is appended to the schema
    try {
        $pdo->exec("ALTER TABLE patients ADD COLUMN emergency_active BOOLEAN DEFAULT FALSE");
    } catch (Exception $e) {
    } // Ignore if already exists

    $aiService = new ConversationService($pdo);
    $response = $aiService->processMessage(1, "I just took my Lisinopril medicine.");
    echo "SUCCESS:\n";
    print_r($response);
} catch (Exception $e) {
    echo "ERROR:\n" . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine();
}
?>