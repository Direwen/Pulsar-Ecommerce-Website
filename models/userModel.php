<?php

require_once "./models/baseModel.php";

class UserModel extends BaseModel
{

    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_EMAIL = 'email';
    private const COLUMN_LAST_LOGGED_IN_AT = 'last_logged_in_at';
    private const COLUMN_IS_ACTIVE = 'is_active';
    private const COLUMN_ROLE = 'role';
    private const COLUMN_CREATED_AT = 'created_at';
    private const TABLE_NAME = 'users';

    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnEmail(): string
    {
        return self::COLUMN_EMAIL;
    }

    public static function getColumnLastLoggedInAt(): string
    {
        return self::COLUMN_LAST_LOGGED_IN_AT;
    }

    public static function getColumnIsActive(): string
    {
        return self::COLUMN_IS_ACTIVE;
    }

    public static function getColumnRole(): string
    {
        return self::COLUMN_ROLE;
    }

    public static function getColumnCreatedAt(): string
    {
        return self::COLUMN_CREATED_AT;
    }

    public static function getTableName(): string // New getter for table name
    {
        return self::TABLE_NAME;
    }

    public function createTable(): bool
    {
        return $this->db->execute(" 
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " ( 
                " . self::getColumnId() . " INT AUTO_INCREMENT PRIMARY KEY,
                " . self::getColumnEmail() . " VARCHAR(255) NOT NULL UNIQUE,
                " . self::getColumnLastLoggedInAt() . " TIMESTAMP NULL,
                " . self::getColumnIsActive() . " BOOLEAN DEFAULT TRUE,
                " . self::getColumnRole() . " ENUM('admin', 'user') DEFAULT 'user',
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_email (" . self::getColumnEmail() . ")
            );
        ");
    }

    protected function createRaw($data): bool
    {
        return $this->db->execute(
            "INSERT INTO " . self::getTableName() . " (" . self::getColumnEmail() . ", " . self::getColumnLastLoggedInAt() . ", " . self::getColumnIsActive() . ", " . self::getColumnRole() . ") VALUES (:email, :last_logged_in_at, :is_active, :role)",
            [
                ':email' => strtolower(trim($data['email'])),
                ':last_logged_in_at' => $data['last_logged_in_at'] ?? null,
                ':is_active' => $data['is_active'] ?? true,
                ':role' => $data['role'] ?? 'user'
            ]
        );
    }
}
