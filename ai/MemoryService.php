<?php
class MemoryService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPatientProfile($patientId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM patients WHERE id = ?");
        $stmt->execute([$patientId]);
        return $stmt->fetch();
    }

    public function getMedicalHistory($patientId)
    {
        $stmt = $this->pdo->prepare("SELECT condition_name, diagnosis_date, notes FROM medical_history WHERE patient_id = ?");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function getTodaysMedicines($patientId)
    {
        $stmt = $this->pdo->prepare("SELECT medicine_name, dosage, frequency, times_of_day, notes FROM medicine_schedule WHERE patient_id = ?");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function getAppointments($patientId)
    {
        $stmt = $this->pdo->prepare("SELECT doctor_name, appointment_date, location, notes FROM appointments WHERE patient_id = ? AND appointment_date >= NOW() ORDER BY appointment_date ASC LIMIT 5");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function getAIMemory($patientId)
    {
        $stmt = $this->pdo->prepare("SELECT memory_key, memory_value FROM ai_memory WHERE patient_id = ?");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function getRecentConversations($patientId, $limit = 10)
    {
        $stmt = $this->pdo->prepare("SELECT role, message, created_at FROM conversation_history WHERE patient_id = ? ORDER BY id DESC LIMIT ?");
        $stmt->bindValue(1, $patientId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return array_reverse($stmt->fetchAll());
    }

    public function saveMessage($patientId, $role, $message)
    {
        $stmt = $this->pdo->prepare("INSERT INTO conversation_history (patient_id, role, message) VALUES (?, ?, ?)");
        return $stmt->execute([$patientId, $role, $message]);
    }
}
?>