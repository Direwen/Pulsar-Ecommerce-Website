<?php

class ErrorHandler {

    private static $instance = null;
    private $db;

    private function __construct($db_instance)
    {
        $this->db = $db_instance;
    }

    public static function getInstance($db_instance)
    {
        if (self::$instance === null) self::$instance = new ErrorHandler($db_instance);
        return self::$instance;
    }

    public function handleDbOperation($callable, $customMessage = null) {
        try {
            $this->db->beginTransaction();
            $result = $callable();
            $this->db->commit();
            return $result ?? true;
    
        } catch (Exception $error) {
            $this->db->rollBack();
            setMessage($error->getMessage(), 'error');
            return false;
        }
    }
    
    public static function handle($callable, $customMessage = null) {
        try {
            $result = $callable();
            return $result ?? true;
        } catch (Exception $exception) {

            setMessage($exception->getMessage(), 'error');           
            // Return false to indicate an error
            return false;
        }
    }
}