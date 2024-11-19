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
        $this->mailer->Username = 'c41d71bf6cfe9f';
        $this->mailer->Password = 'f885355b7a757b';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;
        $this->mailer->Port = 2525;
        $this->mailer->setFrom('fakepulsar@gmail.com', 'Pulsar Gaming Gears');
        $this->mailer->addAddress('fakepulsar@gmail.com', 'Pulsar Support');
        $this->mailer->addReplyTo('fakepulsar@gmail.com', 'Pulsar Support');
        $this->mailer->isHTML(true);
    }

    // Public method to send mail
    public function sendMail(string $to, array $details)
    {
        $this->mailer->clearAddresses();
        $this->mailer->addAddress($to);
        $subject = $details["subject"] ?? "Pulsar Gaming Gear Notification";
        $bodyContent = $details["body"] ?? "No content provided by the sender.";

        $this->mailer->Subject = $subject;
        $this->mailer->Body = "
        <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #24292c;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 20px;
                        border: 1px solid #f5f4f6;
                        border-radius: 10px;
                        background-color: #f5f4f6;
                    }
                    .header {
                        text-align: center;
                        background-color: #1878b8;
                        color: #ffffff;
                        padding: 10px 0;
                        font-size: 24px;
                        border-radius: 4px;
                    }
                    .content {
                        margin-top: 20px;
                        margin-bottom: 20px;
                    }
                    .footer {
                        margin-top: 20px;
                        font-size: 12px;
                        text-align: center;
                        color: #888888;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        Pulsar Gaming Gear
                    </div>
                    <div class='content'>
                        <p>Dear Customer,</p>
                        $bodyContent
                        <p>Thanks,<br>Pulsar Customer Support</p>
                        
                    </div>
                    <div class='footer'>
                        &copy; 2024 Pulsar Gaming Gear. All rights reserved.
                    </div>
                </div>
            </body>
        </html>
    ";

        $this->mailer->isHTML(true);
        return $this->mailer->send();
    }

}
