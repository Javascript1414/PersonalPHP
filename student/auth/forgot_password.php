<?php
include '../../config/db.php';
include '../../config/mail.php';

$msg = "";

if(isset($_POST['send'])) {

    $student_id = trim($_POST['student_id']);

    $query = "SELECT * FROM students WHERE student_id='$student_id'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1) {

        $token = bin2hex(random_bytes(50));

        // save token in DB
        $update = "UPDATE students SET reset_token='$token' WHERE student_id='$student_id'";
        mysqli_query($conn, $update);

        $reset_link = "http://localhost/student/auth/reset_password.php?token=$token";

        // email fetch karo
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];

        $subject = "Password Reset Link";
        $message = "Click to reset password: $reset_link";

       sendResetMail($email, $row['fullname'], "", "");

        $msg = "Reset link sent to registered email ✔";

    } else {
        $msg = "Invalid Student ID ❌";
    }
}
?>

<h2>Forgot Password</h2>

<form method="POST">
    <input type="text" name="student_id" placeholder="Enter Student ID" required>
    <button type="submit" name="send">Send Reset Link</button>
</form>

<p><?php echo $msg; ?></p>