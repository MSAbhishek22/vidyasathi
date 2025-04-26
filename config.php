<?php
// Database Configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "vidyasathi";

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

// Define base URL (adjust if needed)
$base_url = "http://localhost/vidyasathi/";

// Define timezone
date_default_timezone_set('Asia/Kolkata');

// Common functions (can be expanded as needed)
function redirect($path)
{
    global $base_url;
    header("Location: " . $base_url . $path);
    exit();
}

// Session timeout (in seconds)
$session_timeout = 3600; // 1 hour
?>