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
$stmt = $conn->prepare("SELECT name, role, branch, semester, is_mentor, xp_points FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if user is already a mentor
$is_already_mentor = $user['is_mentor'] == 1;

// Check if user has sufficient XP (minimum 50 XP to become a mentor)
$min_xp_required = 50;
$has_sufficient_xp = $user['xp_points'] >= $min_xp_required;

// Process mentor application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_already_mentor) {
    $areas_of_expertise = isset($_POST['areas_of_expertise']) ? trim($_POST['areas_of_expertise']) : '';
    $motivation = isset($_POST['motivation']) ? trim($_POST['motivation']) : '';
    $availability = isset($_POST['availability']) ? trim($_POST['availability']) : '';
    $contact_info = isset($_POST['contact_info']) ? trim($_POST['contact_info']) : '';

    $errors = [];

    // Validate inputs
    if (empty($areas_of_expertise)) {
        $errors[] = "Areas of expertise is required";
    }

    if (empty($motivation)) {
        $errors[] = "Motivation is required";
    }

    if (empty($availability)) {
        $errors[] = "Availability information is required";
    }

    if (count($errors) === 0) {
        // First check if a pending application already exists
        $check_stmt = $conn->prepare("SELECT id FROM mentor_applications WHERE user_id = ? AND status = 'pending'");
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows === 0) {
            // No pending application, create a new one
            $insert_stmt = $conn->prepare("INSERT INTO mentor_applications (user_id, areas_of_expertise, motivation, availability, contact_info, created_at, status) VALUES (?, ?, ?, ?, ?, NOW(), 'pending')");
            $insert_stmt->bind_param("issss", $user_id, $areas_of_expertise, $motivation, $availability, $contact_info);

            if ($insert_stmt->execute()) {
                $_SESSION['success_message'] = "Your mentor application has been submitted successfully! We'll review it and get back to you soon.";
                header("Location: user_profile.php");
                exit();
            } else {
                $errors[] = "Database error: " . $conn->error;
            }
        } else {
            $errors[] = "You already have a pending mentor application";
        }
    }
}

// Check if there's a pending application
$application_status = null;
$check_application = $conn->prepare("SELECT status, created_at, updated_at FROM mentor_applications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$check_application->bind_param("i", $user_id);
$check_application->execute();
$application_result = $check_application->get_result();

if ($application_result->num_rows > 0) {
    $application = $application_result->fetch_assoc();
    $application_status = $application['status'];
    $application_date = $application['created_at'];
    $updated_date = $application['updated_at'];
}

// Set page title and include header
$page_title = "Become a Mentor";
include 'header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-graduate me-2"></i> Become a Mentor</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Share Your Knowledge and Guide Others</h5>
                        <p>As a mentor, you'll have the opportunity to help juniors navigate their academic journey,
                            share your experiences, and make a positive impact on their education.</p>
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

                    <?php if ($is_already_mentor): ?>
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle me-2"></i> You're already a mentor!</h5>
                            <p class="mb-0">Thank you for helping other students. You can view and manage your mentorship
                                requests from your profile page.</p>
                        </div>
                        <div class="text-center mt-4">
                            <a href="user_profile.php" class="btn btn-primary">Go to Your Profile</a>
                        </div>
                    <?php elseif ($application_status === 'pending'): ?>
                        <div class="alert alert-info">
                            <h5><i class="fas fa-hourglass-half me-2"></i> Application Under Review</h5>
                            <p>You've already submitted a mentor application on
                                <?php echo date('F d, Y', strtotime($application_date)); ?>.</p>
                            <p class="mb-0">Our team is currently reviewing your application. We'll notify you once a
                                decision has been made.</p>
                        </div>
                        <div class="text-center mt-4">
                            <a href="user_profile.php" class="btn btn-primary">Back to Profile</a>
                        </div>
                    <?php elseif ($application_status === 'rejected'): ?>
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-circle me-2"></i> Previous Application Not Approved</h5>
                            <p>Your previous application from <?php echo date('F d, Y', strtotime($application_date)); ?>
                                was not approved.</p>
                            <p class="mb-0">You can submit a new application after gaining more experience and contributing
                                more resources to the platform.</p>
                        </div>
                        <div class="text-center mt-4">
                            <a href="user_profile.php" class="btn btn-primary">Back to Profile</a>
                        </div>
                    <?php elseif (!$has_sufficient_xp): ?>
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle me-2"></i> More Experience Needed</h5>
                            <p>To become a mentor, you need to have at least <?php echo $min_xp_required; ?> XP points. You
                                currently have <?php echo $user['xp_points']; ?> XP points.</p>
                            <p class="mb-0">Keep contributing to the platform by uploading resources, helping others, and
                                actively participating in discussions to earn more XP.</p>
                        </div>
                        <div class="text-center mt-4">
                            <a href="upload.php" class="btn btn-primary me-2">Upload Resources</a>
                            <a href="search.php" class="btn btn-outline-primary">Browse Resources</a>
                        </div>
                    <?php else: ?>
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="areas_of_expertise" class="form-label">Areas of Expertise <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="areas_of_expertise" name="areas_of_expertise" rows="3"
                                    required><?php echo isset($areas_of_expertise) ? htmlspecialchars($areas_of_expertise) : ''; ?></textarea>
                                <div class="form-text">List subjects, technologies, or skills you're proficient in and can
                                    help others with</div>
                                <div class="invalid-feedback">Please provide your areas of expertise</div>
                            </div>

                            <div class="mb-3">
                                <label for="motivation" class="form-label">Why do you want to be a mentor? <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="motivation" name="motivation" rows="3"
                                    required><?php echo isset($motivation) ? htmlspecialchars($motivation) : ''; ?></textarea>
                                <div class="form-text">Explain your motivation to help other students and what you hope to
                                    achieve as a mentor</div>
                                <div class="invalid-feedback">Please share your motivation</div>
                            </div>

                            <div class="mb-3">
                                <label for="availability" class="form-label">Availability <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="availability" name="availability" rows="2"
                                    required><?php echo isset($availability) ? htmlspecialchars($availability) : ''; ?></textarea>
                                <div class="form-text">How many hours per week can you dedicate to mentoring? Which days are
                                    you available?</div>
                                <div class="invalid-feedback">Please provide your availability</div>
                            </div>

                            <div class="mb-4">
                                <label for="contact_info" class="form-label">Preferred Contact Method</label>
                                <textarea class="form-control" id="contact_info" name="contact_info"
                                    rows="2"><?php echo isset($contact_info) ? htmlspecialchars($contact_info) : ''; ?></textarea>
                                <div class="form-text">How would you prefer students to reach out to you? (e.g., Email,
                                    WhatsApp, Discord, etc.)</div>
                            </div>

                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Before You Apply</h5>
                                        <p class="mb-0">Becoming a mentor is a responsibility. Students will rely on your
                                            guidance and expertise. Please ensure you can commit the time and effort
                                            required to help others effectively.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Submit Mentor Application</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Benefits of Being a Mentor</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex">
                                    <div class="me-3 text-primary">
                                        <i class="fas fa-award fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5>Recognition</h5>
                                        <p class="text-muted">Gain recognition as a valuable contributor to the
                                            educational community.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex">
                                    <div class="me-3 text-primary">
                                        <i class="fas fa-brain fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5>Strengthen Your Knowledge</h5>
                                        <p class="text-muted">Teaching others reinforces your own understanding of
                                            subjects.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex">
                                    <div class="me-3 text-primary">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5>Build Your Network</h5>
                                        <p class="text-muted">Connect with like-minded individuals and expand your
                                            professional network.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex">
                                    <div class="me-3 text-primary">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5>Develop Leadership Skills</h5>
                                        <p class="text-muted">Enhance your communication, leadership, and interpersonal
                                            skills.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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