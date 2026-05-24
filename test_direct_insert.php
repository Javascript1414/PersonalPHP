<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Registration Test - Direct Insert</h2>";

// Connection test
$conn = mysqli_connect("localhost", "root", "", "exam_portal", 3307);
if(!$conn){
    die("<p style='color:red;'>❌ Connection failed: " . mysqli_connect_error() . "</p>");
}
echo "<p style='color:green;'>✅ Connected to database</p>";

// Simulated registration data
$student_id = "CITS/CSA/Y/26-27/TEST";
$fullname = "Test User Direct";
$email = "test@examportal.com";
$phone = "9876543210";
$password = password_hash("Test@12345", PASSWORD_DEFAULT);
$photo = "test_photo.jpg";
$document = "test_doc.pdf";

// Direct insert query
$sql = "INSERT INTO students 
(student_id, fullname, email, phone, password, photo, document_file, status) 
VALUES 
('$student_id', '$fullname', '$email', '$phone', '$password', '$photo', '$document', 'pending')";

echo "<p><b>Query:</b></p>";
echo "<pre>" . htmlspecialchars($sql) . "</pre>";

echo "<p><b>Executing...</b></p>";

if(mysqli_query($conn, $sql)){
    echo "<p style='color:green;'><b>✅ INSERT SUCCESSFUL!</b></p>";
    echo "<p>Inserted ID: " . mysqli_insert_id($conn) . "</p>";
}
else{
    echo "<p style='color:red;'><b>❌ INSERT FAILED!</b></p>";
    echo "<p>Error: " . mysqli_error($conn) . "</p>";
}

// Show recent records
echo "<h3>Recent Records:</h3>";
$result = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC LIMIT 5");

if(mysqli_num_rows($result) > 0){
    echo "<table border='1' cellpadding='10' style='width:100%;'>";
    echo "<tr><th>ID</th><th>Student ID</th><th>Name</th><th>Email</th><th>Status</th></tr>";
    
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['student_id'] . "</td>";
        echo "<td>" . $row['fullname'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
else{
    echo "<p style='color:orange;'>No records found</p>";
}

mysqli_close($conn);

?>
