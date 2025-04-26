<?php
session_start();

// Only allow admin users to run this update
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "Access denied. Only administrators can run this update.";
    exit();
}

// Include database connection
require_once 'config.php';

echo "<h1>VidyaSathi Profile System Update</h1>";

// Check if users table exists
$check_table = $conn->query("SHOW TABLES LIKE 'users'");
if ($check_table->num_rows == 0) {
    echo "<p>Error: Users table does not exist. Please run the initial database setup first.</p>";
    exit();
}

// Add new columns to users table if they don't exist
$alter_queries = [
    "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `headline` varchar(255) DEFAULT NULL",
    "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `skills` text DEFAULT NULL",
    "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `linkedin` varchar(255) DEFAULT NULL",
    "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `github` varchar(255) DEFAULT NULL",
    "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `discord` varchar(255) DEFAULT NULL",
    "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `instagram` varchar(255) DEFAULT NULL",
    "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `twitter` varchar(255) DEFAULT NULL"
];

$success = true;

foreach ($alter_queries as $query) {
    if ($conn->query($query) === TRUE) {
        echo "<p>Success: " . $query . "</p>";
    } else {
        echo "<p>Error: " . $query . "<br>" . $conn->error . "</p>";
        $success = false;
    }
}

// Create uploads directory for profile images if it doesn't exist
$profile_dir = "uploads/profiles";
if (!file_exists($profile_dir)) {
    if (mkdir($profile_dir, 0777, true)) {
        echo "<p>Success: Created directory for profile images at " . $profile_dir . "</p>";
    } else {
        echo "<p>Error: Could not create directory at " . $profile_dir . ". Please create it manually.</p>";
        $success = false;
    }
}

// Final status
if ($success) {
    echo "<h2>Update completed successfully!</h2>";
    echo "<p>The profile system has been updated with new fields for headline, skills, and social media links.</p>";
    echo "<p><a href='dashboard.php'>Return to Dashboard</a></p>";
} else {
    echo "<h2>Update completed with errors</h2>";
    echo "<p>Please check the error messages above and fix any issues.</p>";
    echo "<p><a href='dashboard.php'>Return to Dashboard</a></p>";
}

$conn->close();
?>