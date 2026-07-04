<?php
require_once __DIR__ . '/MemoryService.php';
require_once __DIR__ . '/AIService.php';
require_once __DIR__ . '/../config/ai.php';

class SummaryService
{
    private $pdo;
    private $memoryService;
    private $aiService;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->memoryService = new MemoryService($pdo);
        $this->aiService = new AIService(GROQ_API_KEY);
    }

    public function generateCaregiverSummary($patientId)
    {
        $profile = $this->memoryService->getPatientProfile($patientId);
        $history = $this->memoryService->getRecentConversations($patientId, 100);

        $log_stmt = $this->pdo->prepare("SELECT medicine_name FROM medicine_logs WHERE patient_id = ? AND DATE(taken_at) = CURDATE()");
        $log_stmt->execute([$patientId]);
        $takenMedicines = $log_stmt->fetchAll(PDO::FETCH_COLUMN);

        $medsObj = $this->memoryService->getTodaysMedicines($patientId);
        $allMedicines = array_column($medsObj, 'medicine_name');
        $missedMedicines = array_diff($allMedicines, $takenMedicines);

        $prompt = "You are Companion AI backend processor. Generate a concise Daily Caregiver Summary based on today's database logs.\n\n";
        $prompt .= "Medicines Taken: " . (empty($takenMedicines) ? "None" : implode(", ", $takenMedicines)) . "\n";
        $prompt .= "Missed Medicines: " . (empty($missedMedicines) ? "None" : implode(", ", $missedMedicines)) . "\n\n";
        $prompt .= "Raw Conversation History for Today:\n";

        if (empty($history)) {
            $prompt .= "[No conversations recorded today]\n";
        } else {
            foreach ($history as $c) {
                // Formatting history older->newer
                $prompt .= $c['role'] . ": " . $c['message'] . "\n";
            }
        }

        $prompt .= "\nBased strictly on the logs, write a 1-paragraph Caregiver Summary that specifically outlines:\n1. Adherence to medicine today.\n2. Detected mood.\n3. Mentioned symptoms.\n4. Caregiver recommendations.";

        $payload = [
            "model" => "llama-3.1-8b-instant",
            "messages" => [
                ["role" => "user", "content" => $prompt]
            ]
        ];

        $aiSummary = $this->aiService->generateResponse($payload);

        // Save generated summary directly to DB
        $stmt = $this->pdo->prepare("INSERT INTO daily_summary (patient_id, summary_date, content) VALUES (?, CURDATE(), ?)");
        $stmt->execute([$patientId, $aiSummary]);

        return $aiSummary;
    }
}
?>