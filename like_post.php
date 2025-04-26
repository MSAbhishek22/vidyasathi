<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to like posts.";
    header('Location: community.php');
    exit();
}

// Check if post ID is provided
if (!isset($_GET['post_id']) || empty($_GET['post_id'])) {
    $_SESSION['error'] = "Invalid post ID";
    header('Location: community.php');
    exit();
}

$post_id = $_GET['post_id'];
$user_id = $_SESSION['user_id'];
$redirect_url = isset($_GET['redirect_url']) ? $_GET['redirect_url'] : "view_post.php?id=$post_id";

// Check if post exists
$stmt = $conn->prepare("SELECT id FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    $_SESSION['error'] = "Post not found";
    header('Location: community.php');
    exit();
}

// Check if user already liked the post
$stmt = $conn->prepare("SELECT id FROM post_likes WHERE post_id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$already_liked = $result->num_rows > 0;

// Toggle like status
if ($already_liked) {
    // Remove like
    $stmt = $conn->prepare("DELETE FROM post_likes WHERE post_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $_SESSION['success'] = "You unliked the post.";
} else {
    // Add like
    $stmt = $conn->prepare("INSERT INTO post_likes (post_id, user_id, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $_SESSION['success'] = "You liked the post.";
}

// Redirect back to the post
header("Location: $redirect_url");
exit();
?>