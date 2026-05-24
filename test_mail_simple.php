<?php

echo "<h2>Email Configuration Test</h2>";

// Test 1: Simple PHP mail function
echo "<h3>Test 1: PHP mail() function</h3>";

$to = 'soumyajitsantra699@gmail.com';
$subject = 'PHP Mail Test';
$message = '<html><body>';
$message .= '<h3>This is PHP mail test</h3>';
$message .= '<p>If you see this, mail is working!</p>';
$message .= '</body></html>';

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: ExamPortal <noreply@examportal>\r\n";

$result = mail($to, $subject, $message, $headers);

if($result) {
    echo "<p style='color: green;'><b>✅ PHP mail() succeeded - Check your inbox!</b></p>";
} else {
    echo "<p style='color: red;'><b>❌ PHP mail() failed</b></p>";
}

// Test 2: Show XAMPP mail configuration
echo "<h3>Test 2: XAMPP Mail Configuration</h3>";
echo "<pre>";
echo "sendmail_path: " . ini_get('sendmail_path') . "\n";
echo "mail.log: " . ini_get('mail.log') . "\n";
echo "mail.add_x_header: " . ini_get('mail.add_x_header') . "\n";
echo "</pre>";

?>
