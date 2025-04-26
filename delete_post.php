<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user info
$user_id = $_SESSION['user_id'];
$is_admin = ($_SESSION['user_role'] === 'admin');
$is_moderator = ($_SESSION['user_role'] === 'moderator');

// Check if post ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = intval($_GET['id']);

// Get post information to verify ownership
$query = "SELECT user_id FROM posts WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if post exists
if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Post not found.";
    header("Location: dashboard.php");
    exit();
}

$post = $result->fetch_assoc();
$stmt->close();

// Check if user is authorized to delete this post
if ($post['user_id'] != $user_id && !$is_admin && !$is_moderator) {
    $_SESSION['error_message'] = "You don't have permission to delete this post.";
    header("Location: dashboard.php");
    exit();
}

// Perform deletion
$delete_query = "DELETE FROM posts WHERE id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $post_id);

if ($delete_stmt->execute()) {
    $_SESSION['success_message'] = "Post has been deleted successfully.";
} else {
    $_SESSION['error_message'] = "Failed to delete post. Please try again.";
}

$delete_stmt->close();
$conn->close();

// Redirect to appropriate page
if ($is_admin || $is_moderator) {
    header("Location: community.php");
} else {
    header("Location: my_posts.php");
}
exit();
?>