<?php
// Include PHPMailer classes manually
// require_once APPROOT . '/libraries/PHPMailer/src/Exception.php';
// require_once APPROOT . '/libraries/PHPMailer/src/PHPMailer.php';
// require_once APPROOT . '/libraries/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send an email using PHPMailer
 * 
 * @param string|array $to Recipient email address(es)
 * @param string $subject Email subject
 * @param string $message Email message (HTML)
 * @param string $plainText Plain text version of the message (optional)
 * @param array $attachments Array of file paths to attach (optional)
 * @param array $cc Array of CC email addresses (optional)
 * @param array $bcc Array of BCC email addresses (optional)
 * @return array ['success' => bool, 'message' => string]
 */
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
        
        // IMPORTANT: Add these lines to improve deliverability
        $mail->XMailer = 'We4u Mailer';                      // Custom X-Mailer header
        $mail->CharSet = 'UTF-8';                            // Specify character set
        $mail->Encoding = 'base64';                          // Use base64 encoding
        $mail->Priority = 1;                                 // Set high priority
        $mail->addCustomHeader('List-Unsubscribe', '<mailto:' . $config['from_email'] . '?subject=Unsubscribe>'); // Add unsubscribe header
        
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
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => "Email could not be sent. Mailer Error: {$mail->ErrorInfo}"
        ];
    }
}

/**
 * Send a notification email with a predefined template
 * 
 * @param string $to Recipient email address
 * @param string $templateName Name of the email template to use
 * @param array $data Data to populate the template with
 * @return array ['success' => bool, 'message' => string]
 */
function sendTemplatedEmail($to, $templateName, $data = []) {
    // Define the path to email templates
    $templatePath = APPROOT . '/views/emails/' . $templateName . '.php';
    
    // Check if template exists
    if (!file_exists($templatePath)) {
        return [
            'success' => false,
            'message' => "Email template '{$templateName}' not found"
        ];
    }
    
    // Extract data to make variables available in the template
    extract($data);
    
    // Start output buffering to capture the template content
    ob_start();
    include $templatePath;
    $message = ob_get_clean();
    
    // Get the subject from the data or use a default
    $subject = $data['subject'] ?? 'Notification from We4u';
    
    // Send the email
    return sendEmail($to, $subject, $message);
}