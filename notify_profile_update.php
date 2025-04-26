<?php
session_start();

// Only allow admin users to run this script
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "Access denied. Only administrators can run this script.";
    exit();
}

// Include database connection
require_once 'config.php';

echo "<h1>VidyaSathi Profile System Update Notifications</h1>";

// Check if notifications table exists
$check_table = $conn->query("SHOW TABLES LIKE 'notifications'");
if ($check_table->num_rows == 0) {
    echo "<p>Error: Notifications table does not exist. Please run the initial database setup first.</p>";
    exit();
}

// Get all users
$users_query = "SELECT id FROM users";
$users_result = $conn->query($users_query);

if (!$users_result) {
    echo "<p>Error querying users: " . $conn->error . "</p>";
    exit();
}

$notification_message = "Your profile has new features! Add your skills, headline, social links, and more. <a href='profile_guide.php'>Learn more</a>.";
$related_to = "profile_update";
$current_time = date('Y-m-d H:i:s');
$is_read = 0;

$success_count = 0;
$error_count = 0;

// Prepare notification insertion statement
$insert_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, related_to, is_read, created_at) VALUES (?, ?, ?, ?, ?)");
$insert_stmt->bind_param("issis", $user_id, $notification_message, $related_to, $is_read, $current_time);

// Add notification for each user
while ($user = $users_result->fetch_assoc()) {
    $user_id = $user['id'];

    if ($insert_stmt->execute()) {
        $success_count++;
    } else {
        $error_count++;
        echo "<p>Error adding notification for user ID {$user_id}: " . $conn->error . "</p>";
    }
}

$insert_stmt->close();

// Final status
echo "<h2>Notification Results</h2>";
echo "<p>Successfully added notifications for {$success_count} users.</p>";

if ($error_count > 0) {
    echo "<p>Failed to add notifications for {$error_count} users.</p>";
}

echo "<p><a href='dashboard.php'>Return to Dashboard</a></p>";

$conn->close();
?>