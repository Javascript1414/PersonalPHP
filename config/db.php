<?php


$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "exam_portal",
    3307
);

if(!$conn){

    die("❌ Database Connection Failed: " . mysqli_connect_error());

}

?>

