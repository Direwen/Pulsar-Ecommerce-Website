<?php

require_once "./models/baseModel.php";

class SessionModel extends BaseModel
{

    // Define constants for column names and table name
    private const COLUMN_ID = 'id';
    private const COLUMN_USER_ID = 'user_id';
    private const COLUMN_TOKEN = 'token';
    private const COLUMN_CREATED_AT = 'created_at';
    private const COLUMN_EXPIRED_AT = 'expired_at';
    private const TABLE_NAME = 'sessions'; // New constant for table name

    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnUserId(): string
    {
        return self::COLUMN_USER_ID;
    }

    public static function getColumnToken(): string
    {
        return self::COLUMN_TOKEN;
    }

    public static function getColumnCreatedAt(): string
    {
        return self::COLUMN_CREATED_AT;
    }

    public static function getColumnExpiredAt(): string
    {
        return self::COLUMN_EXPIRED_AT;
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
                " . self::getColumnUserId() . " INT NOT NULL,
                " . self::getColumnToken() . " VARCHAR(255) UNIQUE NOT NULL,
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnExpiredAt() . " TIMESTAMP NULL,
                CONSTRAINT fk_user FOREIGN KEY (" . self::getColumnUserId() . ") REFERENCES " . UserModel::getTableName() . "(" . UserModel::getColumnId() . ") ON DELETE CASCADE
            )
        ");
    }

    // protected function createRaw($data): bool
    // {
    //     return $this->db->execute(
    //         "INSERT INTO " . self::getTableName() . " (" . self::getColumnUserId() . ", " . self::getColumnToken() . ", " . self::getColumnExpiredAt() . ") VALUES (:user_id, :token, :expired_at)",
    //         [
    //             ':user_id' => $data['user_id'],
    //             ':token' => $data['token'],
    //             ':expired_at' => $data['expired_at']
    //         ]
    //     );
    // }

    public function getUserByToken($token): ?array
    {
        $session = $this->db->fetch(
            "SELECT u.id, u.email FROM " . self::getTableName() . " s JOIN users u ON s." . self::getColumnUserId() . " = u.id WHERE s." . self::getColumnToken() . " = :token",
            [
                ':token' => $token
            ]
        );

        return $session ?? null;
    }

    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'user_id' => $data['user_id'] ?? null,
            'token' => isset($data['token']) ? trim($data['token']) : null,
            'expired_at' => $data['expired_at'] ?? null
        ];

        // Filter out null values to keep only the provided attributes
        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }
}
