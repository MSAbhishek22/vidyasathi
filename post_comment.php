<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to comment";
    header('Location: community.php');
    exit();
}

// Check if form was submitted with comment data
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['post_id']) || !isset($_POST['comment']) || empty($_POST['comment'])) {
    $_SESSION['error'] = "Invalid comment data";
    header('Location: community.php');
    exit();
}

$post_id = $_POST['post_id'];
$user_id = $_SESSION['user_id'];
$comment = trim($_POST['comment']);
$parent_comment_id = isset($_POST['parent_comment_id']) && !empty($_POST['parent_comment_id']) ? $_POST['parent_comment_id'] : null;

// Verify post exists
$stmt = $conn->prepare("SELECT id FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Post not found";
    header('Location: community.php');
    exit();
}

// Verify parent comment exists if replying to a comment
if ($parent_comment_id !== null) {
    $stmt = $conn->prepare("SELECT id FROM post_comments WHERE id = ? AND post_id = ?");
    $stmt->bind_param("ii", $parent_comment_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Parent comment not found";
        header('Location: view_post.php?id=' . $post_id);
        exit();
    }
}

// Insert comment
$stmt = $conn->prepare("INSERT INTO post_comments (post_id, user_id, parent_comment_id, parent_id, comment, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$parent_id = $parent_comment_id; // Set parent_id to same value as parent_comment_id for compatibility
$stmt->bind_param("iiiis", $post_id, $user_id, $parent_comment_id, $parent_id, $comment);

if ($stmt->execute()) {
    $_SESSION['success'] = "Comment added successfully";
} else {
    $_SESSION['error'] = "Error posting comment: " . $conn->error;
}

// Redirect back to post or community
if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
    header('Location: ' . $_POST['redirect_url']);
} else {
    header('Location: view_post.php?id=' . $post_id);
}
exit();
?>