<?php

include 'config/db.php';

echo "<h2>Database Connection Test</h2>";

if($conn){
    echo "<p style='color: green;'>✅ Database Connected Successfully</p>";
    
    // Check if students table exists
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'students'");
    
    if(mysqli_num_rows($table_check) > 0){
        echo "<p style='color: green;'>✅ Students Table Exists</p>";
        
        // Show table structure
        $structure = mysqli_query($conn, "DESCRIBE students");
        echo "<h3>Table Structure:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while($row = mysqli_fetch_assoc($structure)){
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else{
        echo "<p style='color: red;'>❌ Students Table NOT Found</p>";
    }
}
else{
    echo "<p style='color: red;'>❌ Database Connection Failed</p>";
}

?>
