<?php
include("../config/db.php");

$loginLink = "";

if(isset($_POST['register'])){

    $college_id = $_POST['college_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $dob = $_POST['dob'];
    $aadhaar = $_POST['aadhaar'];

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. PASSWORD MATCH CHECK
    if($password !== $confirm_password){
        echo "<script>alert('Password and Confirm Password do not match ❌');</script>";
        exit();
    }

    // 2. AADHAAR VALIDATION (12 digit + numeric)
    if(!preg_match('/^[0-9]{12}$/', $aadhaar)){
        echo "<script>alert('Invalid Aadhaar Number(Must be 12 digits)');</script>";
        exit();
    }

    // 2.5 EMAIL VALIDATION
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "<script>alert('Invalid Email');</script>";
        exit();
    }

    // 2.6 MOBILE VALIDATION
    if(!preg_match('/^[0-9]{10}$/', $mobile)){
        echo "<script>alert('Invalid Mobile Number ❌ (Must be 10 digits)');</script>";
        exit();
    }

    // 3. HASH PASSWORD
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 4. DUPLICATE CHECK
    $check = mysqli_query($conn,"SELECT * FROM students WHERE college_id='$college_id'");

    if(!$check){
        echo "<script>alert('Database error: " . mysqli_error($conn) . "');</script>";
        exit();
    }

    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('College ID already exists ❌');</script>";
        exit();
    }

    $check_aadhaar = mysqli_query($conn,"SELECT * FROM students WHERE aadhaar='$aadhaar'");

    if(!$check_aadhaar){
        echo "<script>alert('Database error: " . mysqli_error($conn) . "');</script>";
        exit();
    }

    if(mysqli_num_rows($check_aadhaar) > 0){
        echo "<script>alert('Aadhaar already exists ❌');</script>";
        exit();
    }

    $result = mysqli_query($conn,"INSERT INTO students
    (college_id,name,email,mobile,dob,aadhaar,password)
    VALUES
    ('$college_id','$name','$email','$mobile','$dob','$aadhaar','$hashed_password')");

    if($result){
        // LOGIN LINK GENERATE
        $loginLink = "http://localhost/PersonalPHP/Student/login.php?cid=".$college_id;
    } else {
        echo "<script>alert('Registration failed: " . mysqli_error($conn) . "');</script>";
    }
}
?>












<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Register</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="reg.css">


</head>

<body>

<div class="register-box">

<h3 class="text-center mb-3">Student Registration</h3>

<form id="registerForm" method="post" action="">

    <input type="text" name="college_id" id="college_id" class="form-control mb-2" placeholder="College ID" required>

    <input type="text" name="name" id="name" class="form-control mb-2" placeholder="Full Name" required>

    <input type="email" name="email" id="email" class="form-control mb-2" placeholder="Email" required>

    <input type="tel" name="mobile" id="mobile" class="form-control mb-2" placeholder="Mobile" required>

    

    
      <input type="date" name="dob" id="dob" class="form-control mb-2" required>
      <input type="number" name="aadhaar" id="aadhaar" class="form-control mb-2" placeholder="Aadhaar" required>

    <input type="password" name="password" id="password" class="form-control mb-2" placeholder="Password" required>

    <input type="password" name="confirm_password" id="confirm_password" class="form-control mb-3" placeholder="Confirm Password" required>

    

    <!-- CAPTCHA BOX -->
<div class="mb-2 p-2 bg-dark rounded text-center">

    <span id="captchaText" style="font-weight:bold; letter-spacing:2px;"></span>

    <button type="button" class="btn btn-sm btn-warning ms-2" onclick="generateCaptcha()">↻</button>

</div>

<input type="text" id="captchaInput" class="form-control mb-3" placeholder="Enter CAPTCHA" required>
<button type="submit" name="register" class="btn btn-custom">Register</button>

</form>
<?php if($loginLink != "") { ?>
<div class="alert alert-success mt-3">
    Registration Successful<br>
    Your Login Link: <br>
    <a href="<?php echo $loginLink; ?>">
        Click Here to Login
    </a>
</div>
<?php } ?>

<p class="text-center mt-3">
Already have account? 
<a href="login.php" class="link">Login</a>
</p>

</div>

<!-- JS -->
<script>
let captchaValue = "";

// CAPTCHA generate function
function generateCaptcha(){
    let chars = "ABCDEFGHJKLMNPQRSTUVWXYZ123456789!@#$*&^!~`,.";
    captchaValue = "";

    for(let i=0;i<5;i++){
        captchaValue += chars.charAt(Math.floor(Math.random()*chars.length));
    }

    document.getElementById("captchaText").innerText = captchaValue;
}

// form submit
document.getElementById("registerForm").addEventListener("submit", function(e){
    let pass = document.getElementById("password").value;
    let confirm = document.getElementById("confirm_password").value;
    let captchaInput = document.getElementById("captchaInput").value;

    if(pass !== confirm){
        alert("Password does not match ❌");
        e.preventDefault();
        return;
    }

    if(captchaInput !== captchaValue){
        alert("Invalid CAPTCHA ❌");
        e.preventDefault();
        generateCaptcha();
        return;
    }

    // Allow the form to submit when validation succeeds
    alert("Form Verified submitting registration...");
});

// first time load captcha
generateCaptcha();

</script>


</body>
</html>


