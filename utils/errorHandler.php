<?php

class ErrorHandler {
    
    public static function handle($callable, $customMessage = null) {
        try {
            $result = $callable();
            return $result ?? true;
        } catch (Exception $exception) {
            // Log the error with more details (optional, but recommended)
            error_log("Error in handle function: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "\nStack Trace:\n" . $exception->getTraceAsString());
    
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