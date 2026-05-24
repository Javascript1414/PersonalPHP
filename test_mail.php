<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    echo "<h2>PHPMailer Connection Test</h2>";
    
    $mail->SMTPDebug = 3; // Verbose debug output
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'soumyajitsantra699@gmail.com';
    $mail->Password = 'fwjm fllq qwtj tweu';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    
    echo "<p style='color: blue;'>Connecting to Gmail SMTP...</p>";
    
    $mail->setFrom('soumyajitsantra699@gmail.com', 'Online Exam Portal');
    $mail->addAddress('soumyajitsantra699@gmail.com', 'Test Recipient');
    
    $mail->isHTML(true);
    $mail->Subject = 'Test Email - ExamPortal';
    $mail->Body = '<h3>This is a test email</h3><p>If you see this, mail configuration is working!</p>';
    
    echo "<p style='color: blue;'>Sending test email...</p>";
    $mail->send();
    
    echo "<p style='color: green;'><b>✅ Success! Email sent successfully</b></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><b>❌ Mail Error:</b></p>";
    echo "<pre style='color: red;'>" . $mail->ErrorInfo . "</pre>";
    echo "<p><b>Exception:</b></p>";
    echo "<pre style='color: red;'>" . $e->getMessage() . "</pre>";
}

?>
