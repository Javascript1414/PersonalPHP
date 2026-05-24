<?php
session_start();

include '../../config/db.php';
include '../../config/mail.php';

/* =========================
   CAPTCHA GENERATE
========================= */
if(!isset($_SESSION['captcha'])){
    $_SESSION['captcha'] = rand(1000,9999);
}
$captcha = $_SESSION['captcha'];

/* =========================
   REGISTER PROCESS
========================= */
if(isset($_POST['register'])){

    $student_id = trim($_POST['student_id']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_captcha = $_POST['captcha'];

    $validation_error = "";

    /* =========================
       CAPTCHA CHECK
    ========================= */
    if($user_captcha != $_SESSION['captcha']){
        $validation_error = "Invalid Captcha";
    }

    /* =========================
       EMAIL VALIDATION
    ========================= */
    elseif(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)){
        $validation_error = "Invalid Email Format";
    }

    /* =========================
       PHONE VALIDATION
    ========================= */
    elseif(!preg_match("/^[6-9]\d{9}$/", $phone)){
        $validation_error = "Invalid Phone Number";
    }

    /* =========================
       STUDENT ID VALIDATION
    ========================= */
    elseif(!preg_match("/^CITS\/[A-Z]+\/[A-Z]\/\d{2}-\d{2}\/\d{4}$/", $student_id)){
        $validation_error = "Invalid Student ID Format";
    }

    /* =========================
       PASSWORD MATCH
    ========================= */
    elseif($password != $confirm_password){
        $validation_error = "Passwords do not match";
    }

    /* =========================
       PASSWORD STRENGTH
    ========================= */
    elseif(!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}$/", $password)){
        $validation_error = "Weak Password";
    }

    /* =========================
       SHOW ERROR
    ========================= */
    if(!empty($validation_error)){
        echo "<script>alert('$validation_error');</script>";
    }
    else{

        /* =========================
           CHECK EMAIL EXISTS
        ========================= */
        $check = mysqli_query($conn, "SELECT student_id FROM students WHERE email='$email'");
        if(mysqli_num_rows($check) > 0){
            echo "<script>alert('Email already exists');</script>";
        }
        else{

            $hash_password = password_hash($password, PASSWORD_DEFAULT);

            /* =========================
               PHOTO UPLOAD
            ========================= */
            $photo_name = "";
            if(!empty($_FILES['photo']['name'])){
                $photo_name = time().'_'.$_FILES['photo']['name'];
                move_uploaded_file($_FILES['photo']['tmp_name'], "../../assets/uploads/students/".$photo_name);
            }

            /* =========================
               DOCUMENT UPLOAD
            ========================= */
            $document_name = "";
            if(!empty($_FILES['document']['name'])){
                $document_name = time().'_'.$_FILES['document']['name'];
                move_uploaded_file($_FILES['document']['tmp_name'], "../../assets/uploads/documents/".$document_name);
            }

            /* =========================
               INSERT DATA
            ========================= */
            $sql = "INSERT INTO students 
            (student_id, fullname, email, phone, password, photo, document_file, status)
            VALUES 
            ('$student_id', '$fullname', '$email', '$phone', '$hash_password', '$photo_name', '$document_name', 'pending')";

            $run = mysqli_query($conn, $sql);

            if($run){

                /* =========================
                   SEND EMAIL (NO PASSWORD IN FUTURE RECOMMENDED)
                ========================= */
                sendStudentMail($email, $fullname, $student_id, $password);

                echo "<script>
                alert('Registration Successful! Await admin approval.');
                window.location.href='login.php';
                </script>";
            }
            else{
                echo "<script>alert('Database Error');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Student Registration</title>

<!-- Bootstrap -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

<!-- Custom CSS -->

<link rel="stylesheet"
href="../../assets/css/auth.css">

</head>

<body>

<div class="container">

<div class="row justify-content-center align-items-center min-vh-100">

<div class="col-md-6">

<div class="card shadow-lg border-0 p-4 rounded-4">

<div class="text-center mb-4">

<h2 class="fw-bold text-primary">

Student Registration

</h2>

<p class="text-muted">

Online Exam Portal

</p>

</div>

<form method="POST"
enctype="multipart/form-data">

<!-- STUDENT ID -->

<div class="mb-3">

<label class="form-label">

Student ID

</label>

<input type="text"
name="student_id"
class="form-control"
placeholder="Enter Student ID"
required>

</div>

<!-- FULL NAME -->

<div class="mb-3">

<label class="form-label">

Full Name

</label>

<input type="text"
name="fullname"
class="form-control"
placeholder="Enter Full Name"
required>

</div>

<!-- EMAIL -->

<div class="mb-3">

<label class="form-label">

Email

</label>

<input type="email"
name="email"
class="form-control"
placeholder="Enter Email"
required>

</div>

<!-- PHONE -->

<div class="mb-3">

<label class="form-label">

Phone Number

</label>

<input type="text"
name="phone"
class="form-control"
placeholder="Enter Phone Number"
required>

</div>

<!-- PASSWORD -->

<div class="mb-3">

<label class="form-label">

Password

</label>

<input type="password"
name="password"
class="form-control"
placeholder="Enter Password"
required>

</div>

<!-- CONFIRM PASSWORD -->

<div class="mb-3">

<label class="form-label">

Confirm Password

</label>

<input type="password"
name="confirm_password"
class="form-control"
placeholder="Confirm Password"
required>

</div>

<!-- PHOTO -->

<div class="mb-3">

<label class="form-label">

Upload Photo

</label>

<input type="file"
name="photo"
class="form-control"
required>

</div>

<!-- DOCUMENT -->

<div class="mb-3">

<label class="form-label">

Upload Document

</label>

<input type="file"
name="document"
class="form-control"
required>

</div>

<!-- CAPTCHA -->

<div class="mb-3">

<label class="form-label">

Captcha Verification

</label>

<h4 class="text-primary fw-bold">

<?php echo $captcha; ?>

</h4>

<input type="text"
name="captcha"
class="form-control"
placeholder="Enter Captcha"
required>

</div>

<!-- BUTTON -->

<div class="d-grid">

<button type="submit"
name="register"
class="btn btn-primary btn-lg">

Register

</button>

</div>

<!-- LOGIN -->

<div class="text-center mt-3">

<a href="login.php">

Already have an account?
Login

</a>

</div>

</form>

</div>

</div>

</div>

</div>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>