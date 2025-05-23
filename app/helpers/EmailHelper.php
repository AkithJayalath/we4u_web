<?php
// Include PHPMailer classes manually
// require_once APPROOT . '/libraries/PHPMailer/src/Exception.php';
// require_once APPROOT . '/libraries/PHPMailer/src/PHPMailer.php';
// require_once APPROOT . '/libraries/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $subject, $message, $plainText = '', $attachments = [], $cc = [], $bcc = []) {
    // Load email configuration
    $configFile = APPROOT . '/config/mail_config.php';
    if (!file_exists($configFile)) {
        return [
            'success' => false,
            'message' => 'Email configuration file not found'
        ];
    }
    
    $config = require $configFile;
    
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['encryption'];
        $mail->Port = $config['port'];
        
        // If you have a physical address, include it in compliance with anti-spam laws
        $mail->addCustomHeader('X-Organization', 'We4u Elder Care');        
        // Set sender
        $mail->setFrom($config['from_email'], $config['from_name']);
        
        // Add recipients
        if (is_array($to)) {
            foreach ($to as $email) {
                $mail->addAddress($email);
            }
        } else {
            $mail->addAddress($to);
        }
        
        // Add CC recipients
        if (!empty($cc)) {
            foreach ($cc as $email) {
                $mail->addCC($email);
            }
        }
        
        // Add BCC recipients
        if (!empty($bcc)) {
            foreach ($bcc as $email) {
                $mail->addBCC($email);
            }
        }
        
        // Add attachments
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                if (file_exists($attachment)) {
                    $mail->addAttachment($attachment);
                }
            }
        }
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        // Set plain text version if provided
        if (!empty($plainText)) {
            $mail->AltBody = $plainText;
        } else {
            // Create a plain text version from HTML
            $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $message));
        }
        
        // Send the email
        $mail->send();
        
        return [
            'success' => true,
            'message' => 'Email sent successfully'
        ];

        flash('success', 'Email sent successfully');
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => "Email could not be sent. Mailer Error: {$mail->ErrorInfo}"
        ];
    }
}