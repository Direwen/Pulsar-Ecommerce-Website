<?php

require_once "./models/baseModel.php";

class DiscountModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_CODE = 'code';
    private const COLUMN_AMOUNT = 'amount';
    private const COLUMN_MIN_AMOUNT = 'min_amount';
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

    public static function getColumnMinAmount(): string
    {
        return self::COLUMN_MIN_AMOUNT;
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
                " . self::getColumnAmount() . " DECIMAL(10, 2) NOT NULL,
                " . self::getColumnMinAmount() . " DECIMAL(10, 2) DEFAULT NULL,
                " . self::getColumnMaxUsage() . " INT DEFAULT NULL,
                " . self::getColumnUsedCount() . " INT DEFAULT 0,
                " . self::getColumnExpiredAt() . " DATE DEFAULT NULL,
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnUpdatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT UNIQUE (" . self::getColumnCode() . ")
            );
        ");
    }

    // Format data for storing in the database
    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'code' => $data['code'] ?? null,
            'amount' => $data['amount'] ?? null,
            'min_amount' => $data['min_amount'] ?? null,
            'max_usage' => $data['max_usage'] ?? null,
            'used_count' => $data['used_count'] ?? 0,
            'expired_at' => $data['expired_at'] ?? null,
        ];

        // Filter out null values if needed
        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }
}
