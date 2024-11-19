<?php

require_once "./models/baseModel.php";

class SupportTicketModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_USER_EMAIL = 'user_email';
    private const COLUMN_SUBJECT = 'subject';
    private const COLUMN_MESSAGE = 'message';
    private const COLUMN_STATUS = 'status'; // e.g., 'open', 'replied'
    private const COLUMN_CREATED_AT = 'created_at';
    private const COLUMN_UPDATED_AT = 'updated_at';
    private const TABLE_NAME = 'support_tickets';

    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnUserEmail(): string
    {
        return self::COLUMN_USER_EMAIL;
    }

    public static function getColumnSubject(): string
    {
        return self::COLUMN_SUBJECT;
    }

    public static function getColumnMessage(): string
    {
        return self::COLUMN_MESSAGE;
    }

    public static function getColumnStatus(): string
    {
        return self::COLUMN_STATUS;
    }

    public static function getColumnCreatedAt(): string
    {
        return self::COLUMN_CREATED_AT;
    }

    public static function getColumnUpdatedAt(): string
    {
        return self::COLUMN_UPDATED_AT;
    }

    public static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function createTable(): bool
    {
        return $this->db->execute("
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " (
                " . self::getColumnId() . " INT AUTO_INCREMENT PRIMARY KEY,
                " . self::getColumnUserEmail() . " VARCHAR(255) NOT NULL,
                " . self::getColumnSubject() . " VARCHAR(255) NOT NULL,
                " . self::getColumnMessage() . " TEXT NOT NULL,
                " . self::getColumnStatus() . " ENUM('open', 'replied') DEFAULT 'open',
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnUpdatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );
        ");
    }

    public function validateFormData(array $post_data): ?bool
    {
        $errors = [];

        // Validate 'user_email' - required and valid email
        if (empty($post_data[$this->getColumnUserEmail()]) || !filter_var($post_data[$this->getColumnUserEmail()], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "A valid email address is required.";
        }

        // Validate 'subject' - required, max length of 255 characters
        if (empty($post_data[$this->getColumnSubject()])) {
            $errors[] = "Subject is required.";
        } elseif (strlen($post_data[$this->getColumnSubject()]) > 255) {
            $errors[] = "Subject cannot exceed 255 characters.";
        }

        // Validate 'message' - required
        if (empty($post_data[$this->getColumnMessage()])) {
            $errors[] = "Message is required.";
        }

        // If there are errors, handle them
        if (!empty($errors)) {
            var_dump($errors);
            setMessage(implode(", ", $errors), 'error');
            return false;
        }

        return true;
    }

    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'user_email' => isset($data['user_email']) ? strtolower(trim($data['user_email'])) : null,
            'subject' => isset($data['subject']) ? trim($data['subject']) : null,
            'message' => $data['message'] ?? null,
            'status' => isset($data['status']) ? $data['status'] : 'open'
        ];

        // Filter out null values if $null_filter is true
        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }
}
