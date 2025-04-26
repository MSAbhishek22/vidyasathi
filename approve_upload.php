<?php
require 'db.php';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "UPDATE uploads SET approved = 1 WHERE id = $id";
    $conn->query($sql);
}
header("Location: review_uploads.php");
exit();
?>
