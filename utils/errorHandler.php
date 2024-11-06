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
    
            // Display the main error information in a red table
            echo '<table style="border-collapse: collapse; width: 100%; background-color: #FF0000; color: #fff;">';
            echo '<tr><th style="text-align: left; padding: 8px; border: 1px solid #ddd;">File</th><th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Line</th><th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Message</th><th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Code</th></tr>';
            echo '<tr>';
            echo '<td style="text-align: left; padding: 8px; border: 1px solid #ddd;">' . $exception->getFile() . '</td>';
            echo '<td style="text-align: left; padding: 8px; border: 1px solid #ddd;">' . $exception->getLine() . '</td>';
            echo '<td style="text-align: left; padding: 8px; border: 1px solid #ddd;">' . ($customMessage ?? $exception->getMessage()) . '</td>';
            echo '<td style="text-align: left; padding: 8px; border: 1px solid #ddd;">' . $exception->getCode() . '</td>';
            echo '</tr>';
            echo '</table>';
    
            // Display the stack trace in a darker red table
            echo '<table style="border-collapse: collapse; width: 100%; background-color: #8B0000; color: #fff;">';
            echo '<tr><th style="text-align: left; padding: 8px; border: 1px solid #ddd;">File</th><th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Line</th><th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Function</th><th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Args</th></tr>';
    
            $trace = $exception->getTrace();
            foreach ($trace as $frame) {
                echo '<tr>';
                echo '<td style="text-align: left; padding: 8px; border: 1px solid #ddd;">' . ($frame['file'] ?? '') . '</td>';
                echo '<td style="text-align: left; padding: 8px; border: 1px solid #ddd;">' . ($frame['line'] ?? '') . '</td>';
                echo '<td style="text-align: left; padding: 8px; border: 1px solid #ddd;">' . ($frame['function'] ?? '') . '</td>';
                echo '<td style="text-align: left; padding: 8px; border: 1px solid #ddd;">' . (isset($frame['args']) ? print_r($frame['args'], true) : '') . '</td>';
                echo '</tr>';
            }
    
            echo '</table>';
    
            // Return false to indicate an error
            return false;
        }
    }
}