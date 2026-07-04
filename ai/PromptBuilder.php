<?php
class PromptBuilder
{
    public function buildSystemPrompt($profile, $medicalHistory, $medicines, $appointments, $aiMemory)
    {
        $prompt = "You are Companio AI, a stateful AI Caregiver for an elderly patient.\n";

        $prompt .= "\n--- PATIENT PROFILE ---\n";
        if ($profile) {
            $prompt .= "Name: {$profile['first_name']} {$profile['last_name']}\n";
            if ($profile['notes'])
                $prompt .= "Notes: {$profile['notes']}\n";
        }

        $prompt .= "\n--- AI MEMORY (PREFERENCES) ---\n";
        if (empty($aiMemory))
            $prompt .= "No explicit preferences learned yet.\n";
        foreach ($aiMemory as $mem) {
            $prompt .= "- {$mem['memory_key']}: {$mem['memory_value']}\n";
        }

        $prompt .= "\n--- MEDICAL HISTORY ---\n";
        if (empty($medicalHistory))
            $prompt .= "No medical history recorded.\n";
        foreach ($medicalHistory as $mh) {
            $prompt .= "- {$mh['condition_name']}\n";
        }

        $prompt .= "\n--- TODAY'S MEDICINES ---\n";
        if (empty($medicines))
            $prompt .= "No active medicines.\n";
        foreach ($medicines as $med) {
            $prompt .= "- {$med['medicine_name']} ({$med['dosage']} at {$med['times_of_day']})\n";
        }

        $prompt .= "\n--- UPCOMING APPOINTMENTS ---\n";
        if (empty($appointments))
            $prompt .= "No appointments.\n";
        foreach ($appointments as $app) {
            $prompt .= "- {$app['doctor_name']} on {$app['appointment_date']}\n";
        }

        $prompt .= "\n--- AGENCY INSTRUCTIONS (CRITICAL) ---\n";
        $prompt .= "You are an autonomous dynamic AI Agent connected directly to the physical UI dashboard of the Elderly patient.\n";
        $prompt .= "You MUST respond strictly in valid JSON format ONLY, containing no markdown or other text.\n";
        $prompt .= "JSON Schema Requirement:\n";
        $prompt .= "{\n";
        $prompt .= "  \"message\": \"1 or 2 small friendly sentences to speak out loud to the patient.\",\n";
        $prompt .= "  \"action\": \"none\", // Set to 'sos_on' IF they mention an emergency/pain. Set to 'med_taken' IF they confirm taking a pill. Otherwise 'none'.\n";
        $prompt .= "  \"medicine_name\": \"\" // ONLY IF action is med_taken, write the name of the medicine they took.\n";
        $prompt .= "}\n";
        return $prompt;
    }

    public function buildPayload($systemPrompt, $chatHistory, $newMessage)
    {
        $messages = [];

        $messages[] = [
            "role" => "system",
            "content" => $systemPrompt
        ];

        foreach ($chatHistory as $chat) {
            $role = ($chat['role'] === 'model' || $chat['role'] === 'assistant') ? 'assistant' : 'user';
            $messages[] = [
                "role" => $role,
                "content" => $chat['message']
            ];
        }

        $messages[] = [
            "role" => "user",
            "content" => $newMessage
        ];

        return [
            "model" => "llama-3.1-8b-instant",
            "messages" => $messages,
            "temperature" => 0.7,
            "response_format" => ["type" => "json_object"]
        ];
    }
}
?>