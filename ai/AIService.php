<?php
class AIService
{
    private $apiKey;
    // Targeting Official Groq REST Endpoint for completely free usage
    private $endpoint = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function generateResponse($payload)
    {
        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error)
            return "API Network Error.";

        $decoded = json_decode($response, true);

        // Target ChatGPT Specific Array Schema
        if (isset($decoded['choices'][0]['message']['content'])) {
            $cleanText = str_replace('*', '', $decoded['choices'][0]['message']['content']);
            return trim($cleanText);
        }

        return "API ERROR: " . print_r($decoded, true);
    }
}
?>