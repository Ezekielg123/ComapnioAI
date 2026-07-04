<?php
require_once __DIR__ . '/MemoryService.php';
require_once __DIR__ . '/PromptBuilder.php';
require_once __DIR__ . '/AIService.php';
require_once __DIR__ . '/../config/ai.php';

class ConversationService
{
    private $memory;
    private $builder;
    private $ai;
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->memory = new MemoryService($pdo);
        $this->builder = new PromptBuilder();
        $this->ai = new AIService(GROQ_API_KEY);
    }

    public function processMessage($patientId, $message)
    {
        $profile = $this->memory->getPatientProfile($patientId);
        $medicalHistory = $this->memory->getMedicalHistory($patientId);
        $medicines = $this->memory->getTodaysMedicines($patientId);
        $appointments = $this->memory->getAppointments($patientId);
        $aiMemory = $this->memory->getAIMemory($patientId);

        $systemPrompt = $this->builder->buildSystemPrompt($profile, $medicalHistory, $medicines, $appointments, $aiMemory);
        $history = $this->memory->getRecentConversations($patientId, 10);
        $payload = $this->builder->buildPayload($systemPrompt, $history, $message);

        $aiResponse = $this->ai->generateResponse($payload);

        $json = json_decode($aiResponse, true);

        $responseText = "I am processing your request. One moment please.";
        $action = "none";

        if ($json && isset($json['message'])) {
            $responseText = $json['message'];
            if (isset($json['action']))
                $action = $json['action'];

            // AGENTIC EXECUTION: Physically bend environment dynamically
            if ($action === 'sos_on') {
                $stmt = $this->pdo->prepare("UPDATE patients SET emergency_active = TRUE WHERE id = ?");
                $stmt->execute([$patientId]);
            }

            if ($action === 'med_taken' && !empty($json['medicine_name'])) {
                $stmt = $this->pdo->prepare("INSERT INTO medicine_logs (patient_id, medicine_name) VALUES (?, ?)");
                $stmt->execute([$patientId, $json['medicine_name']]);
            }
        } else {
            // Fallback parsing just in case Groq leaked outer texts
            $responseText = $aiResponse;
        }

        // Save cleanly to history logs avoiding JSON pollution
        $this->memory->saveMessage($patientId, 'user', $message);
        $this->memory->saveMessage($patientId, 'model', $responseText);

        return ['text' => $responseText, 'action' => $action];
    }
}
?>