<?php
require 'db.php';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Get file path
    $result = $conn->query("SELECT file_path FROM uploads WHERE id = $id");
    $row = $result->fetch_assoc();
    $filePath = $row['file_path'];

    // Delete file
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Delete record
    $conn->query("DELETE FROM uploads WHERE id = $id");
}
header("Location: review_uploads.php");
exit();
?>
