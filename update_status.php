<?php
session_start();
require_once 'config.php';

// Check for correct role and access
$valid_roles = ['admin', 'moderator'];

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $valid_roles)) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_id = intval($_POST['file_id']);

    // Handle reset status
    if (isset($_POST['reset']) && $_POST['reset'] == 1) {
        $approved = 0; // Reset to pending
        $action_type = 'reset';
    } else {
        $status_input = $_POST['status'];
        // Status: 1 = approved, -1 = rejected
        $approved = ($status_input === 'approved') ? 1 : -1;
        $action_type = $status_input;
    }

    // Update uploads table
    $stmt = $conn->prepare("UPDATE uploads SET approved = ?, approved_by = ?, approval_date = NOW() WHERE id = ?");
    $approver_id = $_SESSION['user_id'];
    $stmt->bind_param("iii", $approved, $approver_id, $file_id);

    if ($stmt->execute()) {
        // Fetch upload information for notification
        $upload_stmt = $conn->prepare("SELECT uploader_id, title, category FROM uploads WHERE id = ?");
        $upload_stmt->bind_param("i", $file_id);
        $upload_stmt->execute();
        $upload_result = $upload_stmt->get_result();
        $upload_data = $upload_result->fetch_assoc();

        if ($upload_data) {
            $uploader_id = $upload_data['uploader_id'];
            $upload_title = $upload_data['title'];

            // Create notification message based on action
            if ($action_type === 'approved') {
                $notification_message = "Your upload \"" . substr($upload_title, 0, 40) . "...\" has been approved ✅";
            } elseif ($action_type === 'rejected') {
                $notification_message = "Your upload \"" . substr($upload_title, 0, 40) . "...\" has been rejected ❌";
            } else {
                $notification_message = "Your upload \"" . substr($upload_title, 0, 40) . "...\" status has been reset to pending";
            }

            // Check if notifications table exists
            $check_table = $conn->query("SHOW TABLES LIKE 'notifications'");
            if ($check_table->num_rows > 0) {
                // Insert notification
                $notify_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, related_to, related_id, created_at) VALUES (?, ?, 'upload', ?, NOW())");
                $notify_stmt->bind_param("isi", $uploader_id, $notification_message, $file_id);
                $notify_stmt->execute();
            }

            // Set session message
            $_SESSION['status_message'] = "File " . ucfirst($action_type) . " successfully and notification sent to uploader.";
            $_SESSION['status_type'] = "success";
        } else {
            $_SESSION['status_message'] = "File status updated but could not find uploader details.";
            $_SESSION['status_type'] = "warning";
        }
    } else {
        $_SESSION['status_message'] = "Error updating file status: " . $conn->error;
        $_SESSION['status_type'] = "danger";
    }

    // Redirect back to review page
    header("Location: review_uploads.php");
    exit();
} else {
    // If not a POST request, redirect to review page
    header("Location: review_uploads.php");
    exit();
}
?>