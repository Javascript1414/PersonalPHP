<?php

use PHPMailer\PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\Exception;

/* =========================
   AUTOLOAD
========================= */

require '../../vendor/autoload.php';

/* =========================
   SEND MAIL FUNCTION
========================= */

function sendStudentMail(
    $email,
    $fullname,
    $student_id,
    $password
)
{

    $mail = new PHPMailer(true);

    try
    {

        /* =========================
           SMTP SETTINGS - METHOD 1: GMAIL
        ========================= */

        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'soumyajitsantra699@gmail.com';
        $mail->Password = 'fwjm fllq qwtj tweu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Disable SSL verification for localhost testing
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom(
            'soumyajitsantra699@gmail.com',
            'Online Exam Portal'
        );

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject =
        "Registration Successful";

        $mail->Body = "

        <h2>
        Welcome To Online Exam Portal
        </h2>

        <p>
        Hello <b>$fullname</b>,
        </p>

        <p>
        Your registration has been completed successfully.
        </p>

        <h3>
        Login Details
        </h3>

        <p>
        <b>User ID:</b>
        $student_id
        </p>

        <p>
        <b>Password:</b>
        $password
        </p>

        <p>
        Your account is currently under admin verification.
        </p>

        <p>
        After approval,
        you can login to the portal.
        </p>

        <br>

        <p>
        Thank You
        </p>

        <p>
        Online Exam Portal Team
        </p>

        ";

        /* =========================
           SEND MAIL
        ========================= */

        error_log("Attempting to send mail to: " . $email);
        $mail->send();
        error_log("✅ Mail sent successfully to: " . $email);
        return true;

    }

    catch(Exception $e)
    {

        error_log('❌ Mail Error: ' . $mail->ErrorInfo);
        error_log('❌ Exception: ' . $e->getMessage());
        
        // Try alternative method - use PHP mail function
        error_log("Attempting alternative mail method...");
        
        $to = $email;
        $subject = "Registration Successful";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Online Exam Portal <noreply@examportal.local>\r\n";
        
        $body = "<h2>Welcome To Online Exam Portal</h2>
        <p>Hello <b>$fullname</b>,</p>
        <p>Your registration has been completed successfully.</p>
        <h3>Login Details</h3>
        <p><b>User ID:</b> $student_id</p>
        <p><b>Password:</b> $password</p>
        <p>Your account is currently under admin verification.</p>
        <p>Thank You<br>Online Exam Portal Team</p>";
        
        $alt_result = mail($to, $subject, $body, $headers);
        
        if($alt_result) {
            error_log("✅ Alternative mail method succeeded!");
            return true;
        } else {
            error_log("❌ Alternative mail also failed");
            return false;
        }

    }

    return true;

}

?>
<?php

function sendResetMail($email, $link) {

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'soumyajitsantra699@gmail.com';
        $mail->Password = 'fwjm fllq qwtj tweu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('yourmail@gmail.com', 'Exam Portal');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Password Reset Link";

        $mail->Body = "
            <h2>Password Reset Request</h2>
            <p>Click below link to reset your password:</p>
            <a href='$link'>$link</a>
        ";

        $mail->send();
        return true;

    } catch(Exception $e) {
        return false;
    }
}
?>
