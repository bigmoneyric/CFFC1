<?php
include 'dbconn.php'; // 

// Create uploads folder if not exists
$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $caption = $_POST['caption'];
    $caption = $conn->real_escape_string($caption);

    $fileName = basename($_FILES["photo"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $fileName; // avoid duplicate names

    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allowed file formats
    $allowTypes = array('jpg','jpeg','png','gif');

    if (in_array($fileType, $allowTypes)) {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
            // Insert into DB
            $sql = "INSERT INTO gallery (image_path, caption) VALUES ('$targetFilePath', '$caption')";
            if ($conn->query($sql) === TRUE) {
                echo "<p style='color:green;'>✅ Photo uploaded successfully!</p>";
            } else {
                echo "<p style='color:red;'>❌ Database Error: " . $conn->error . "</p>";
            }
        } else {
