<?php

session_start();
include '../../config/db.php';
include '../../config/mail.php';

/* =========================
   ADMIN - RESEND EMAIL TO STUDENT
========================= */

if(isset($_GET['resend_id']) && isset($_SESSION['admin_id']))
{
    $student_id = $_GET['resend_id'];
    
    // Get student details
    $get_student = mysqli_query(
        $conn,
        "SELECT * FROM students WHERE id='$student_id'"
    );
    
    if(mysqli_num_rows($get_student) > 0)
    {
        $student = mysqli_fetch_assoc($get_student);
        
        // Send email
        $mail_sent = sendStudentMail(
            $student['email'],
            $student['fullname'],
            $student['student_id'],
            $student['password']  // This is hashed - show generic message instead
        );
        
        if($mail_sent)
        {
            echo "<script>
            alert('✅ Email resent successfully to " . $student['email'] . "');
            window.history.back();
            </script>";
        }
        else
        {
            echo "<script>
            alert('❌ Failed to resend email. Check mail configuration.');
            window.history.back();
            </script>";
        }
    }
    else
    {
        echo "<script>
        alert('Student not found');
        window.history.back();
        </script>";
    }
}

?>
