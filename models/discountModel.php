<?php

require_once "./models/baseModel.php";

class DiscountModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_CODE = 'code';
    private const COLUMN_AMOUNT = 'amount';
    private const COLUMN_MAX_USAGE = 'max_usage';
    private const COLUMN_USED_COUNT = 'used_count';
    private const COLUMN_EXPIRED_AT = 'expired_at';
    private const COLUMN_CREATED_AT = 'created_at';
    private const COLUMN_UPDATED_AT = 'updated_at';
    private const TABLE_NAME = 'discounts';

    // Getter methods for column names
    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnCode(): string
    {
        return self::COLUMN_CODE;
    }

    public static function getColumnAmount(): string
    {
        return self::COLUMN_AMOUNT;
    }

    public static function getColumnMaxUsage(): string
    {
        return self::COLUMN_MAX_USAGE;
    }

    public static function getColumnUsedCount(): string
    {
        return self::COLUMN_USED_COUNT;
    }

    public static function getColumnExpiredAt(): string
    {
        return self::COLUMN_EXPIRED_AT;
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

    // Create table if it doesn't exist
    public function createTable(): bool
    {
        return $this->db->execute("
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " (
                " . self::getColumnId() . " INT AUTO_INCREMENT PRIMARY KEY,
                " . self::getColumnCode() . " VARCHAR(255) UNIQUE NOT NULL,
                " . self::getColumnAmount() . " INT NOT NULL,
                " . self::getColumnMaxUsage() . " INT NOT NULL DEFAULT 5,
                " . self::getColumnUsedCount() . " INT DEFAULT 0,
                " . self::getColumnExpiredAt() . " TIMESTAMP NULL,
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnUpdatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );
        ");
    }

    // Format data for storing in the database
    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'code' => isset($data['code']) ? strtoupper($data['code']) : null,
            'amount' => $data['amount'] ?? null,
            'max_usage' => $data['max_usage'] ?? null,
            'used_count' => $data['used_count'] ?? null,
            'expired_at' => isset($data['expired_at']) ? strtotime($data['expired_at']) : null,
        ];

        // Filter out null values if needed
        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }

    public function validateDiscount(array $record): bool
    {
        // Check if 'expired_at' is set and if the discount is still valid
        if ($record[self::getColumnExpiredAt()] > 0 && $record[self::getColumnExpiredAt()] < time()) {

            return false; // Discount has expired
        }

        // Check if 'max_usage' is set and if there are usages left
        if (($record[self::getColumnUsedCount()] ?? 0) >= $record[self::getColumnMaxUsage()]) {

            return false; // No usages left
        }

        return true; // Discount is valid
    }

    public function validateFormData(array $post_data): ?bool
    {
        $errors = [];

        // Validate 'code' - required and must be unique
        if (!isset($post_data[$this->getColumnCode()]) || empty($post_data[$this->getColumnCode()]) || strlen($post_data[$this->getColumnCode()]) > 255) {
            $errors[] = "Discount code is required and must not exceed 255 characters.";
        }

        // Validate 'amount' - required, must be a valid number and positive
        if (!isset($post_data[$this->getColumnAmount()]) || !is_numeric($post_data[$this->getColumnAmount()]) || $post_data[$this->getColumnAmount()] < 1 || $post_data[$this->getColumnAmount()] > 100) {
            $errors[] = "Amount is required, must be a valid number, and must be between 1 and 100 inclusive.";
        }

        // Validate 'max_usage' - required, must be a valid positive integer
        if (!isset($post_data[$this->getColumnMaxUsage()]) || !is_numeric($post_data[$this->getColumnMaxUsage()]) || $post_data[$this->getColumnMaxUsage()] <= 0) {
            $errors[] = "Max usage is required and must be a valid positive integer.";
        }

        // Validate 'expired_at' - optional, must be a valid date if provided
        if (!empty($post_data[$this->getColumnExpiredAt()]) && !strtotime($post_data[$this->getColumnExpiredAt()])) {
            $errors[] = "Expired date must be a valid date.";
        }

        // Return errors if any
        if (!empty($errors)) {
            setMessage(implode(", ", $errors), 'error');
            return false;
        }

        // Return true if no errors
        return true;
    }
}
