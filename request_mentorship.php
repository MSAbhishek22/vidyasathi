<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in to request mentorship";
    header("Location: login.php");
    exit();
}

// Check if mentor_id is provided
if (!isset($_GET['mentor_id'])) {
    $_SESSION['error_message'] = "Invalid mentor request";
    header("Location: find_mentors.php");
    exit();
}

$mentor_id = intval($_GET['mentor_id']);
$student_id = $_SESSION['user_id'];

// Make sure the mentor exists and is actually a mentor
$mentor_query = $conn->prepare("SELECT id, name, branch, semester, is_mentor FROM users WHERE id = ? AND is_mentor = 1");
$mentor_query->bind_param("i", $mentor_id);
$mentor_query->execute();
$mentor_result = $mentor_query->get_result();

if ($mentor_result->num_rows === 0) {
    $_SESSION['error_message'] = "The requested mentor does not exist or is not available";
    header("Location: find_mentors.php");
    exit();
}

$mentor = $mentor_result->fetch_assoc();

// Check if the student is trying to mentor themselves
if ($mentor_id === $student_id) {
    $_SESSION['error_message'] = "You cannot request mentorship from yourself";
    header("Location: find_mentors.php");
    exit();
}

// Check if there's already a pending or active mentorship request
$check_query = $conn->prepare("SELECT id, status, created_at FROM mentorship_requests 
                              WHERE student_id = ? AND mentor_id = ? 
                              AND (status = 'pending' OR status = 'accepted')
                              ORDER BY created_at DESC LIMIT 1");
$check_query->bind_param("ii", $student_id, $mentor_id);
$check_query->execute();
$check_result = $check_query->get_result();

$existing_request = null;
if ($check_result->num_rows > 0) {
    $existing_request = $check_result->fetch_assoc();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$existing_request) {
    $subjects = isset($_POST['subjects']) ? trim($_POST['subjects']) : '';
    $goals = isset($_POST['goals']) ? trim($_POST['goals']) : '';
    $questions = isset($_POST['questions']) ? trim($_POST['questions']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    $errors = [];

    // Validate inputs
    if (empty($subjects)) {
        $errors[] = "Subjects/Topics field is required";
    }

    if (empty($goals)) {
        $errors[] = "Goals field is required";
    }

    // If all validation passes
    if (count($errors) === 0) {
        // Insert mentorship request
        $insert_query = $conn->prepare("INSERT INTO mentorship_requests 
                                      (student_id, mentor_id, subjects, goals, questions, message, status, created_at) 
                                      VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())");
        $insert_query->bind_param("iissss", $student_id, $mentor_id, $subjects, $goals, $questions, $message);

        if ($insert_query->execute()) {
            // Add a notification for the mentor
            $notification_text = $_SESSION['user_name'] . " has requested mentorship from you";
            $notification_query = $conn->prepare("INSERT INTO notifications 
                                                (user_id, notification_text, related_to, related_id, created_at) 
                                                VALUES (?, ?, 'mentorship', ?, NOW())");
            $last_id = $conn->insert_id;
            $notification_query->bind_param("isi", $mentor_id, $notification_text, $last_id);
            $notification_query->execute();

            // Add XP for requesting mentorship if the xp_activities table exists
            $table_check = $conn->query("SHOW TABLES LIKE 'xp_activities'");
            if ($table_check->num_rows > 0) {
                $xp_points = 5; // XP for requesting mentorship
                $xp_stmt = $conn->prepare("INSERT INTO xp_activities 
                                          (user_id, activity_type, xp_earned, description, related_to, related_id, created_at) 
                                          VALUES (?, 'mentorship_request', ?, ?, 'mentorship', ?, NOW())");
                $description = "Requested mentorship from " . $mentor['name'];
                $xp_stmt->bind_param("iisi", $student_id, $xp_points, $description, $last_id);
                $xp_stmt->execute();

                // Update user's XP points
                $update_xp = $conn->prepare("UPDATE users SET xp_points = xp_points + ? WHERE id = ?");
                $update_xp->bind_param("ii", $xp_points, $student_id);
                $update_xp->execute();
            }

            $_SESSION['success_message'] = "Your mentorship request has been sent successfully";
            header("Location: user_profile.php");
            exit();
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}

// Get student info
$student_query = $conn->prepare("SELECT name, branch, semester FROM users WHERE id = ?");
$student_query->bind_param("i", $student_id);
$student_query->execute();
$student_result = $student_query->get_result();
$student = $student_result->fetch_assoc();

// Set page title and include header
$page_title = "Request Mentorship";
include 'header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-graduate me-2"></i> Request Mentorship</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <?php echo strtoupper(substr($mentor['name'], 0, 1)); ?>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">Request mentorship from <?php echo htmlspecialchars($mentor['name']); ?>
                            </h5>
                            <p class="text-muted mb-0">
                                <?php if (!empty($mentor['branch'])): ?>
                                    <?php echo htmlspecialchars($mentor['branch']); ?>
                                <?php endif; ?>
                                <?php if (!empty($mentor['semester'])): ?>
                                    • Semester <?php echo htmlspecialchars($mentor['semester']); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <?php if (isset($errors) && count($errors) > 0): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($existing_request): ?>
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle me-2"></i> You already have a mentorship request</h5>
                            <?php if ($existing_request['status'] === 'pending'): ?>
                                <p>You sent a mentorship request to <?php echo htmlspecialchars($mentor['name']); ?> on
                                    <?php echo date('F d, Y', strtotime($existing_request['created_at'])); ?>.</p>
                                <p class="mb-0">Your request is pending approval. You'll be notified once the mentor responds.
                                </p>
                            <?php elseif ($existing_request['status'] === 'accepted'): ?>
                                <p>You are already being mentored by <?php echo htmlspecialchars($mentor['name']); ?> since
                                    <?php echo date('F d, Y', strtotime($existing_request['created_at'])); ?>.</p>
                                <p class="mb-0">Check your notifications for any messages from your mentor.</p>
                            <?php endif; ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="user_profile.php" class="btn btn-primary">Go to Your Profile</a>
                            <a href="find_mentors.php" class="btn btn-outline-primary ms-2">Find Other Mentors</a>
                        </div>
                    <?php else: ?>
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="subjects" class="form-label">Subjects/Topics You Need Help With <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="subjects" name="subjects" rows="2"
                                    required><?php echo isset($subjects) ? htmlspecialchars($subjects) : ''; ?></textarea>
                                <div class="form-text">List specific subjects, topics, or skills you're seeking guidance
                                    with</div>
                                <div class="invalid-feedback">Please specify the subjects/topics</div>
                            </div>

                            <div class="mb-3">
                                <label for="goals" class="form-label">Your Goals <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="goals" name="goals" rows="3"
                                    required><?php echo isset($goals) ? htmlspecialchars($goals) : ''; ?></textarea>
                                <div class="form-text">What do you hope to achieve with this mentorship? Be specific about
                                    your academic or career goals</div>
                                <div class="invalid-feedback">Please share your goals</div>
                            </div>

                            <div class="mb-3">
                                <label for="questions" class="form-label">Specific Questions</label>
                                <textarea class="form-control" id="questions" name="questions"
                                    rows="3"><?php echo isset($questions) ? htmlspecialchars($questions) : ''; ?></textarea>
                                <div class="form-text">Do you have any specific questions to start the conversation?</div>
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label">Personal Message</label>
                                <textarea class="form-control" id="message" name="message"
                                    rows="3"><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                                <div class="form-text">Add a personal message to introduce yourself to the mentor</div>
                            </div>

                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-lightbulb fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Tips for a Successful Mentorship</h5>
                                        <ul class="mb-0">
                                            <li>Be specific about what you want to learn</li>
                                            <li>Set clear expectations about the time commitment</li>
                                            <li>Come prepared with questions for your meetings</li>
                                            <li>Be respectful of your mentor's time and expertise</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Send Mentorship Request</button>
                                <a href="find_mentors.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Enable Bootstrap form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

<?php include 'footer.php'; ?>