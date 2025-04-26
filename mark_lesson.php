<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if all required parameters are present
if (!isset($_POST['lesson_id']) || !isset($_POST['course_id']) || !isset($_POST['action'])) {
    header("Location: dashboard.php");
    exit();
}

$lesson_id = $_POST['lesson_id'];
$course_id = $_POST['course_id'];
$action = $_POST['action'];

// Validate parameters
if (!is_numeric($lesson_id) || !is_numeric($course_id) || !in_array($action, ['mark', 'unmark'])) {
    header("Location: dashboard.php");
    exit();
}

// Check if user is enrolled in this course
$enrollment_sql = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
$enrollment_stmt = $conn->prepare($enrollment_sql);
$enrollment_stmt->bind_param("ii", $user_id, $course_id);
$enrollment_stmt->execute();
$enrollment_result = $enrollment_stmt->get_result();

if ($enrollment_result->num_rows === 0) {
    // User is not enrolled in this course
    header("Location: dashboard.php");
    exit();
}

// Check if lesson belongs to the course
$lesson_sql = "SELECT * FROM lessons WHERE id = ? AND course_id = ?";
$lesson_stmt = $conn->prepare($lesson_sql);
$lesson_stmt->bind_param("ii", $lesson_id, $course_id);
$lesson_stmt->execute();
$lesson_result = $lesson_stmt->get_result();

if ($lesson_result->num_rows === 0) {
    // Lesson doesn't belong to this course
    header("Location: course.php?id=" . $course_id);
    exit();
}

if ($action === 'mark') {
    // Mark the lesson as completed

    // First check if it's already marked to avoid duplicates
    $check_sql = "SELECT * FROM lesson_completion WHERE user_id = ? AND course_id = ? AND lesson_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("iii", $user_id, $course_id, $lesson_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        // Not marked yet, so mark it
        $completed_at = date("Y-m-d H:i:s");
        $mark_sql = "INSERT INTO lesson_completion (user_id, course_id, lesson_id, completed_at) VALUES (?, ?, ?, ?)";
        $mark_stmt = $conn->prepare($mark_sql);
        $mark_stmt->bind_param("iiis", $user_id, $course_id, $lesson_id, $completed_at);
        $mark_stmt->execute();

        // Update user progress statistics
        updateUserProgress($conn, $user_id, $course_id);

        // Set success message
        $_SESSION['success_message'] = "Lesson marked as completed!";
    }
} else {
    // Unmark the lesson (remove completion record)
    $unmark_sql = "DELETE FROM lesson_completion WHERE user_id = ? AND course_id = ? AND lesson_id = ?";
    $unmark_stmt = $conn->prepare($unmark_sql);
    $unmark_stmt->bind_param("iii", $user_id, $course_id, $lesson_id);
    $unmark_stmt->execute();

    // Update user progress statistics
    updateUserProgress($conn, $user_id, $course_id);

    // Set success message
    $_SESSION['success_message'] = "Lesson marked as incomplete.";
}

// Function to update user progress statistics
function updateUserProgress($conn, $user_id, $course_id)
{
    // Count total lessons in the course
    $total_sql = "SELECT COUNT(*) as total FROM lessons WHERE course_id = ?";
    $total_stmt = $conn->prepare($total_sql);
    $total_stmt->bind_param("i", $course_id);
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_row = $total_result->fetch_assoc();
    $total_lessons = $total_row['total'];

    // Count completed lessons
    $completed_sql = "SELECT COUNT(*) as completed FROM lesson_completion WHERE user_id = ? AND course_id = ?";
    $completed_stmt = $conn->prepare($completed_sql);
    $completed_stmt->bind_param("ii", $user_id, $course_id);
    $completed_stmt->execute();
    $completed_result = $completed_stmt->get_result();
    $completed_row = $completed_result->fetch_assoc();
    $completed_lessons = $completed_row['completed'];

    // Calculate progress percentage
    $progress_percentage = ($total_lessons > 0) ? round(($completed_lessons / $total_lessons) * 100) : 0;

    // Check if an enrollment record exists
    $check_sql = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $course_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    // Update the progress in enrollments table
    if ($check_result->num_rows > 0) {
        $last_activity = date("Y-m-d H:i:s");
        $update_sql = "UPDATE enrollments SET progress_percentage = ?, last_activity = ? WHERE user_id = ? AND course_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("isii", $progress_percentage, $last_activity, $user_id, $course_id);
        $update_stmt->execute();
    }

    // Check if all lessons are completed and course has not been marked as completed yet
    if ($progress_percentage == 100) {
        $completion_date = date("Y-m-d");

        // Check if completion record already exists
        $check_completion_sql = "SELECT * FROM course_completion WHERE user_id = ? AND course_id = ?";
        $check_completion_stmt = $conn->prepare($check_completion_sql);
        $check_completion_stmt->bind_param("ii", $user_id, $course_id);
        $check_completion_stmt->execute();
        $check_completion_result = $check_completion_stmt->get_result();

        if ($check_completion_result->num_rows === 0) {
            // Create completion record
            $completion_sql = "INSERT INTO course_completion (user_id, course_id, completion_date) 
                              VALUES (?, ?, ?)";
            $completion_stmt = $conn->prepare($completion_sql);
            $completion_stmt->bind_param("iis", $user_id, $course_id, $completion_date);
            $completion_stmt->execute();
        }
    } else {
        // If progress is less than 100%, remove any existing completion record
        $remove_completion_sql = "DELETE FROM course_completion WHERE user_id = ? AND course_id = ?";
        $remove_completion_stmt = $conn->prepare($remove_completion_sql);
        $remove_completion_stmt->bind_param("ii", $user_id, $course_id);
        $remove_completion_stmt->execute();
    }
}

// Redirect back to the progress page
header("Location: course_progress.php?course_id=" . $course_id);
exit();
?>