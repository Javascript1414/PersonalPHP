<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Debug</h2>";

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "exam_portal",
    3307
);

if(!$conn){
    echo "<p style='color: red;'><b>❌ Connection Failed:</b> " . mysqli_connect_error() . "</p>";
    die();
}

echo "<p style='color: green;'><b>✅ Connected to database successfully!</b></p>";

// Check students table
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'students'");

if(mysqli_num_rows($table_check) > 0)
{
    echo "<p style='color: green;'>✅ Students table exists</p>";
    
    // Count records
    $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM students");
    $result = mysqli_fetch_assoc($count);
    
    echo "<p>Total students registered: <b>" . $result['total'] . "</b></p>";
    
    // Show recent students
    echo "<h3>Recent Registrations:</h3>";
    $recent = mysqli_query($conn, "SELECT id, student_id, fullname, email, status, created_at FROM students ORDER BY created_at DESC LIMIT 10");
    
    if(mysqli_num_rows($recent) > 0)
    {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Student ID</th><th>Name</th><th>Email</th><th>Status</th><th>Created</th></tr>";
        
        while($row = mysqli_fetch_assoc($recent))
        {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['student_id'] . "</td>";
            echo "<td>" . $row['fullname'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else
    {
        echo "<p style='color: orange;'>No students registered yet</p>";
    }
}
else
{
    echo "<p style='color: red;'>❌ Students table NOT found</p>";
}

mysqli_close($conn);

?>
