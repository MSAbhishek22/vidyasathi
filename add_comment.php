<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to comment.']);
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

// Validate required fields
if (!isset($_POST['post_id']) || empty($_POST['post_id']) || !isset($_POST['comment']) || empty($_POST['comment'])) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit();
}

$post_id = $_POST['post_id'];
$user_id = $_SESSION['user_id'];
$comment_text = trim($_POST['comment']);

// Check if post exists
$stmt = $conn->prepare("SELECT id FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
if ($stmt->rowCount() == 0) {
    echo json_encode(['success' => false, 'message' => 'Post not found.']);
    exit();
}

// Get user information for response
$user_query = $conn->prepare("SELECT name, profile_image, role FROM users WHERE id = ?");
$user_query->execute([$user_id]);
$user_data = $user_query->fetch(PDO::FETCH_ASSOC);

// Insert comment
try {
    $stmt = $conn->prepare("INSERT INTO post_comments (post_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$post_id, $user_id, $comment_text]);

    // Create response data
    $comment_data = [
        'id' => $conn->lastInsertId(),
        'post_id' => $post_id,
        'user_id' => $user_id,
        'user_name' => $user_data['name'],
        'profile_image' => $user_data['profile_image'],
        'user_role' => $user_data['role'],
        'comment' => $comment_text,
        'formatted_date' => date('M d, Y \a\t h:i A')
    ];

    echo json_encode([
        'success' => true,
        'message' => 'Comment added successfully.',
        'comment' => $comment_data
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error adding comment: ' . $e->getMessage()]);
}