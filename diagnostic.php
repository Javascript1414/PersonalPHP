<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>📋 Complete System Diagnostic</h2>";

// 1. Database Connection
echo "<h3>1. Database Connection</h3>";
$conn = mysqli_connect("localhost", "root", "", "exam_portal", 3307);
if($conn){
    echo "<p style='color: green;'>✅ Database connected on port 3307</p>";
}
else{
    echo "<p style='color: red;'>❌ Database connection failed: " . mysqli_connect_error() . "</p>";
}

// 2. Upload Directories
echo "<h3>2. Upload Directories</h3>";

$base_path = __DIR__;
$photo_dir = $base_path . '/assets/uploads/students/';
$doc_dir = $base_path . '/assets/uploads/documents/';

echo "<p><b>Photo Directory:</b> " . $photo_dir . "</p>";
if(is_dir($photo_dir) && is_writable($photo_dir)){
    echo "<p style='color: green;'>✅ Photo directory exists and is writable</p>";
}
elseif(!is_dir($photo_dir)){
    echo "<p style='color: orange;'>⚠️ Creating photo directory...</p>";
    if(@mkdir($photo_dir, 0777, true)){
        @chmod($photo_dir, 0777);
        echo "<p style='color: green;'>✅ Photo directory created</p>";
    }
    else{
        echo "<p style='color: red;'>❌ Cannot create photo directory</p>";
    }
}
else{
    echo "<p style='color: red;'>❌ Photo directory not writable</p>";
}

echo "<p><b>Document Directory:</b> " . $doc_dir . "</p>";
if(is_dir($doc_dir) && is_writable($doc_dir)){
    echo "<p style='color: green;'>✅ Document directory exists and is writable</p>";
}
elseif(!is_dir($doc_dir)){
    echo "<p style='color: orange;'>⚠️ Creating document directory...</p>";
    if(@mkdir($doc_dir, 0777, true)){
        @chmod($doc_dir, 0777);
        echo "<p style='color: green;'>✅ Document directory created</p>";
    }
    else{
        echo "<p style='color: red;'>❌ Cannot create document directory</p>";
    }
}
else{
    echo "<p style='color: red;'>❌ Document directory not writable</p>";
}

// 3. Students Table
echo "<h3>3. Students Table</h3>";
if($conn){
    $check = mysqli_query($conn, "SHOW TABLES LIKE 'students'");
    if(mysqli_num_rows($check) > 0){
        echo "<p style='color: green;'>✅ Students table exists</p>";
        
        $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM students");
        $row = mysqli_fetch_assoc($count);
        echo "<p>Total students: <b>" . $row['total'] . "</b></p>";
    }
    else{
        echo "<p style='color: red;'>❌ Students table not found</p>";
    }
}

// 4. PHP Configuration
echo "<h3>4. PHP Configuration</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Max Upload Size: " . ini_get('upload_max_filesize') . "</p>";
echo "<p>Post Max Size: " . ini_get('post_max_size') . "</p>";

// 5. PHPMailer
echo "<h3>5. PHPMailer Configuration</h3>";
if(file_exists(__DIR__ . '/vendor/autoload.php')){
    echo "<p style='color: green;'>✅ PHPMailer vendor file found</p>";
}
else{
    echo "<p style='color: red;'>❌ PHPMailer vendor file not found</p>";
}

// 6. Recent Registrations
echo "<h3>6. Recent Registrations</h3>";
if($conn){
    $recent = mysqli_query($conn, "SELECT id, student_id, fullname, email, status, photo, document_file FROM students ORDER BY created_at DESC LIMIT 5");
    
    if(mysqli_num_rows($recent) > 0){
        echo "<table border='1' cellpadding='10' style='width:100%;'>";
        echo "<tr><th>ID</th><th>Student ID</th><th>Name</th><th>Email</th><th>Photo</th><th>Document</th></tr>";
        
        while($row = mysqli_fetch_assoc($recent)){
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['student_id'] . "</td>";
            echo "<td>" . $row['fullname'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . ($row['photo'] ? '✅ ' . $row['photo'] : '❌ None') . "</td>";
            echo "<td>" . ($row['document_file'] ? '✅ ' . $row['document_file'] : '❌ None') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else{
        echo "<p style='color: orange;'>No registrations yet</p>";
    }
}

// 7. List uploaded files
echo "<h3>7. Uploaded Files</h3>";

if(is_dir($photo_dir)){
    echo "<h4>Photos:</h4>";
    $files = array_diff(scandir($photo_dir), ['.', '..']);
    if(count($files) > 0){
        echo "<ul>";
        foreach($files as $file){
            echo "<li>" . $file . "</li>";
        }
        echo "</ul>";
    }
    else{
        echo "<p>No photos uploaded yet</p>";
    }
}

if(is_dir($doc_dir)){
    echo "<h4>Documents:</h4>";
    $files = array_diff(scandir($doc_dir), ['.', '..']);
    if(count($files) > 0){
        echo "<ul>";
        foreach($files as $file){
            echo "<li>" . $file . "</li>";
        }
        echo "</ul>";
    }
    else{
        echo "<p>No documents uploaded yet</p>";
    }
}

if($conn){
    mysqli_close($conn);
}

?>
