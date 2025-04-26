<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "vidyasathi";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<link rel="stylesheet" href="css/style.css">
