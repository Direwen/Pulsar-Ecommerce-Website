<?php

/**
 * ErrorHandler class to manage error handling and database transactions in a centralized way.
 * This class implements the Singleton design pattern to ensure only one instance is created.
 */
class ErrorHandler {

    // Static variable to hold the single instance of the class
    private static $instance = null;

    // Database connection instance
    private $db;

    /**
     * Private constructor to prevent direct instantiation from outside the class.
     * 
     * @param object $db_instance - Database connection instance
     */
    private function __construct($db_instance)
    {
        $this->db = $db_instance;
    }

    /**
     * Static method to get or create the single instance of the ErrorHandler class.
     * 
     * @param object $db_instance - Database connection instance
     * @return ErrorHandler - The singleton instance of the class
     */
    public static function getInstance($db_instance)
    {
        // Create the instance if it doesn't exist
        if (self::$instance === null) {
            self::$instance = new ErrorHandler($db_instance);
        }
        return self::$instance;
    }

    /**
     * Handles database operations with error handling and transaction management.
     * Rolls back the transaction in case of an error.
     * 
     * @param callable $callable - The database operation to be executed
     * @param string|null $customMessage - Custom error message (not currently used)
     * @return mixed - The result of the callable if successful, or false on failure
     */
    public function handleDbOperation($callable, $customMessage = null) {
        try {
            // Begin the database transaction
            $this->db->beginTransaction();

            // Execute the callable (user-provided database operation)
            $result = $callable();

            // Commit the transaction if successful
            $this->db->commit();

            // Return the result of the operation or true if no result
            return $result ?? true;

        } catch (Exception $error) {
            // Roll back the transaction on error
            $this->db->rollBack();

            // Log the error message for debugging purposes
            setMessage($error->getMessage(), 'error');

            // Return false to indicate failure
            return false;
        }
    }

    /**
     * Handles non-database operations with error handling.
     * 
     * @param callable $callable - The operation to be executed
     * @param string|null $customMessage - Custom error message (not currently used)
     * @return mixed - The result of the callable if successful, or false on failure
     */
    public static function handle($callable, $customMessage = null) {
        try {
            // Execute the callable (user-provided operation)
            $result = $callable();

            // Return the result of the operation or true if no result
            return $result ?? true;

        } catch (Exception $exception) {
            // Log the error message for debugging purposes
            setMessage($exception->getMessage(), 'error');

            // Return false to indicate failure
            return false;
        }
    }
}
