<?php
session_start();
include '../../config/db.php';

$error = "";

if(isset($_POST['login'])) {

    $student_id = trim($_POST['student_id']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM students WHERE student_id='$student_id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])) {

            $_SESSION['student_id'] = $row['student_id'];
            $_SESSION['fullname'] = $row['fullname'];

            header("Location: ../dashboard.php");
            exit();

        } else {
            $error = "❌ Wrong password";
        }

    } else {
        $error = "❌ Invalid Student ID";
    }
}
?>

<h2>Student Login</h2>

<form method="POST">

    <input type="text" name="student_id" placeholder="Enter Student ID" required><br><br>

    <input type="password" name="password" placeholder="Enter Password" required><br><br>

    <button type="submit" name="login">Login</button>

</form>

<p style="color:red;"><?php echo $error; ?></p>