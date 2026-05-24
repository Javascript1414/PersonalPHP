<?php

echo "<h2>File Upload Directory Test</h2>";

// Check current directory
echo "<p>Current file: " . __FILE__ . "</p>";
echo "<p>Current directory: " . __DIR__ . "</p>";

// Relative paths
$photo_dir = __DIR__ . '/../../assets/uploads/students/';
$doc_dir = __DIR__ . '/../../assets/uploads/documents/';

echo "<h3>Photo Directory Test</h3>";
echo "<p>Path: " . $photo_dir . "</p>";

if(!is_dir($photo_dir)){
    echo "<p style='color: orange;'>Directory not found, creating...</p>";
    if(mkdir($photo_dir, 0777, true)){
        chmod($photo_dir, 0777);
        echo "<p style='color: green;'>✅ Photo directory created successfully</p>";
    }
    else{
        echo "<p style='color: red;'>❌ Failed to create photo directory</p>";
    }
}
else{
    echo "<p style='color: green;'>✅ Photo directory exists</p>";
}

echo "<p>Is writable: " . (is_writable($photo_dir) ? '✅ YES' : '❌ NO') . "</p>";

echo "<h3>Document Directory Test</h3>";
echo "<p>Path: " . $doc_dir . "</p>";

if(!is_dir($doc_dir)){
    echo "<p style='color: orange;'>Directory not found, creating...</p>";
    if(mkdir($doc_dir, 0777, true)){
        chmod($doc_dir, 0777);
        echo "<p style='color: green;'>✅ Document directory created successfully</p>";
    }
    else{
        echo "<p style='color: red;'>❌ Failed to create document directory</p>";
    }
}
else{
    echo "<p style='color: green;'>✅ Document directory exists</p>";
}

echo "<p>Is writable: " . (is_writable($doc_dir) ? '✅ YES' : '❌ NO') . "</p>";

// List files if directories exist
if(is_dir($photo_dir)){
    echo "<h3>Files in Photo Directory:</h3>";
    $files = scandir($photo_dir);
    if(count($files) > 2){
        echo "<ul>";
        foreach($files as $file){
            if($file != '.' && $file != '..'){
                echo "<li>" . $file . "</li>";
            }
        }
        echo "</ul>";
    }
    else{
        echo "<p>No files uploaded yet</p>";
    }
}

if(is_dir($doc_dir)){
    echo "<h3>Files in Document Directory:</h3>";
    $files = scandir($doc_dir);
    if(count($files) > 2){
        echo "<ul>";
        foreach($files as $file){
            if($file != '.' && $file != '..'){
                echo "<li>" . $file . "</li>";
            }
        }
        echo "</ul>";
    }
    else{
        echo "<p>No files uploaded yet</p>";
    }
}

?>
