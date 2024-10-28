<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

class MailService
{
    // Static property to hold the singleton instance
    private static $instance = null;

    // Instance property to hold the PHPMailer object
    private $mailer;

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->configureMailer();
    }

    // Public method to get the singleton instance
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new MailService();
        }
        return self::$instance;
    }

    // Private method to configure PHPMailer
    private function configureMailer()
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = 'sandbox.smtp.mailtrap.io';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username   = 'c41d71bf6cfe9f';                      
        $this->mailer->Password   = 'f885355b7a757b';                      
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
        $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;
        $this->mailer->Port = 2525;
        $this->mailer->setFrom('fakepulsar@gmail.com', 'Pulsar Gaming Gears');
        $this->mailer->addAddress('fakepulsar@gmail.com', 'Pulsar Support');
        $this->mailer->addReplyTo('fakepulsar@gmail.com', 'Pulsar Support');
        $this->mailer->isHTML(true);
    }

    // Public method to send OTP
    public function sendOtp($to, $otp)
    {
        $this->mailer->clearAddresses();
        $this->mailer->addAddress($to);
        $this->mailer->Subject = "Your OTP Code";
        $this->mailer->Body    = "Your OTP code is: <b>$otp</b>";
        return $this->mailer->send();
    }
}
