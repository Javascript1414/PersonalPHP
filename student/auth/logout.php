<?php
session_start();

// session destroy karo
session_unset();
session_destroy();

// login page pe redirect
header("Location: login.php");
exit();
?>