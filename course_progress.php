<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if course_id is provided
if (!isset($_GET['course_id']) || !is_numeric($_GET['course_id'])) {
    header("Location: dashboard.php");
    exit();
}

$course_id = $_GET['course_id'];
$page_title = "Course Progress";

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

// Get course details
$course_sql = "SELECT * FROM courses WHERE id = ?";
$course_stmt = $conn->prepare($course_sql);
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course_result = $course_stmt->get_result();
$course = $course_result->fetch_assoc();

// Get all lessons for this course
$lessons_sql = "SELECT * FROM lessons WHERE course_id = ? ORDER BY lesson_order ASC";
$lessons_stmt = $conn->prepare($lessons_sql);
$lessons_stmt->bind_param("i", $course_id);
$lessons_stmt->execute();
$lessons_result = $lessons_stmt->get_result();
$lessons = [];
while ($row = $lessons_result->fetch_assoc()) {
    $lessons[] = $row;
}

// Get completed lessons for this user
$completed_sql = "SELECT lesson_id FROM lesson_completion WHERE user_id = ? AND course_id = ?";
$completed_stmt = $conn->prepare($completed_sql);
$completed_stmt->bind_param("ii", $user_id, $course_id);
$completed_stmt->execute();
$completed_result = $completed_stmt->get_result();
$completed_lessons = [];
while ($row = $completed_result->fetch_assoc()) {
    $completed_lessons[] = $row['lesson_id'];
}

// Calculate progress percentage
$total_lessons = count($lessons);
$completed_count = count($completed_lessons);
$progress_percentage = ($total_lessons > 0) ? round(($completed_count / $total_lessons) * 100) : 0;

include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a
                            href="course.php?id=<?php echo $course_id; ?>"><?php echo htmlspecialchars($course['title']); ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Progress</li>
                </ol>
            </nav>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?php echo htmlspecialchars($course['title']); ?> - Course Progress</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Your Progress: <?php echo $progress_percentage; ?>% Complete</h6>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: <?php echo $progress_percentage; ?>%"
                                    aria-valuenow="<?php echo $progress_percentage; ?>" aria-valuemin="0"
                                    aria-valuemax="100">
                                    <?php echo $progress_percentage; ?>%
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-0">
                                <strong>Lessons Completed:</strong> <?php echo $completed_count; ?> of
                                <?php echo $total_lessons; ?>
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Lesson Title</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lessons)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No lessons available for this course yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($lessons as $index => $lesson): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($lesson['title']); ?></td>
                                            <td>
                                                <?php if (in_array($lesson['id'], $completed_lessons)): ?>
                                                    <span class="badge bg-success">Completed</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="lesson.php?id=<?php echo $lesson['id']; ?>"
                                                    class="btn btn-sm btn-primary">
                                                    <?php if (in_array($lesson['id'], $completed_lessons)): ?>
                                                        <i class="fas fa-redo me-1"></i>Review
                                                    <?php else: ?>
                                                        <i class="fas fa-play me-1"></i>Start
                                                    <?php endif; ?>
                                                </a>

                                                <?php if (in_array($lesson['id'], $completed_lessons)): ?>
                                                    <form method="post" action="mark_lesson.php" class="d-inline">
                                                        <input type="hidden" name="lesson_id" value="<?php echo $lesson['id']; ?>">
                                                        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                                                        <input type="hidden" name="action" value="unmark">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fas fa-times me-1"></i>Mark as Incomplete
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="post" action="mark_lesson.php" class="d-inline">
                                                        <input type="hidden" name="lesson_id" value="<?php echo $lesson['id']; ?>">
                                                        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                                                        <input type="hidden" name="action" value="mark">
                                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-check me-1"></i>Mark as Complete
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <a href="course.php?id=<?php echo $course_id; ?>" class="btn btn-outline-primary me-2">
                    <i class="fas fa-arrow-left me-1"></i>Back to Course
                </a>
                <a href="certificate.php?course_id=<?php echo $course_id; ?>"
                    class="btn btn-success <?php echo ($progress_percentage < 100) ? 'disabled' : ''; ?>">
                    <i class="fas fa-certificate me-1"></i>Get Certificate
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>