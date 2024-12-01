<?php

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $host = 'localhost';
        $db = 'pulsar_ecommerce';
        $user = 'root';
        $pass = '';
        $port = '3308';
        $charset = 'utf8mb4';
        $data_source = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

        $this->pdo = new PDO($data_source, $user, $pass);
    }

    public static function getInstance()
    {
        if (self::$instance === null) self::$instance = new Database();
        return self::$instance;
    }

    public function beginTransaction()
    {
        if (!$this->pdo->inTransaction()) {
            $this->pdo->beginTransaction();
        }
    }

    public function commit()
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        }
    }

    public function rollBack()
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }

    // Use for INSERT, UPDATE, DELETE queries
    public function execute($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Fetch a single record (for SELECT queries)
    public function fetch($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Fetch as an associative array
    }

    // Fetch multiple records (optional)
    public function fetchAll($sql, $params = [], $limit = null, $offset = null)
    {

        // If limit and offset are provided, append them to the SQL query
        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch all results as an associative array
    }
}
