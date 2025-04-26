<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is a moderator
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'moderator') {
    $_SESSION['error_message'] = "You don't have permission to access this page";
    header("Location: index.php");
    exit();
}

// Handle approval/rejection actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['file_id'])) {
        $file_id = intval($_POST['file_id']);
        $action = $_POST['action'];
        $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : '';

        if ($action === 'approve') {
            // Approve the file
            $stmt = $conn->prepare("UPDATE uploads SET is_approved = 1, approved_by = ?, approved_date = NOW(), feedback = ? WHERE id = ?");
            $stmt->bind_param("isi", $_SESSION['user_id'], $feedback, $file_id);

            if ($stmt->execute()) {
                // Get file information for notification
                $file_query = $conn->prepare("SELECT title, uploader_id FROM uploads WHERE id = ?");
                $file_query->bind_param("i", $file_id);
                $file_query->execute();
                $file_result = $file_query->get_result();
                $file_data = $file_result->fetch_assoc();

                // Add notification for the uploader
                if ($file_data) {
                    $notification_text = "Your upload \"" . $file_data['title'] . "\" has been approved";
                    $notify_stmt = $conn->prepare("INSERT INTO notifications (user_id, notification_text, related_to, related_id, created_at) VALUES (?, ?, 'upload', ?, NOW())");
                    $notify_stmt->bind_param("isi", $file_data['uploader_id'], $notification_text, $file_id);
                    $notify_stmt->execute();

                    // Award XP to the uploader if the tables exist
                    $table_check = $conn->query("SHOW TABLES LIKE 'xp_activities'");
                    if ($table_check->num_rows > 0) {
                        $xp_points = 10; // XP for getting a file approved
                        $xp_stmt = $conn->prepare("INSERT INTO xp_activities (user_id, activity_type, xp_earned, description, related_to, related_id, created_at) VALUES (?, 'file_approved', ?, ?, 'upload', ?, NOW())");
                        $description = "File approved: {$file_data['title']}";
                        $xp_stmt->bind_param("iisi", $file_data['uploader_id'], $xp_points, $description, $file_id);
                        $xp_stmt->execute();

                        // Update user's XP points
                        $update_xp = $conn->prepare("UPDATE users SET xp_points = xp_points + ? WHERE id = ?");
                        $update_xp->bind_param("ii", $xp_points, $file_data['uploader_id']);
                        $update_xp->execute();
                    }
                }

                $_SESSION['success_message'] = "File has been approved successfully";
            } else {
                $_SESSION['error_message'] = "Failed to approve file: " . $conn->error;
            }
        } elseif ($action === 'reject') {
            // Reject the file
            if (empty($feedback)) {
                $_SESSION['error_message'] = "Feedback is required when rejecting a file";
            } else {
                // Get file information for notification
                $file_query = $conn->prepare("SELECT title, uploader_id, file_path FROM uploads WHERE id = ?");
                $file_query->bind_param("i", $file_id);
                $file_query->execute();
                $file_result = $file_query->get_result();
                $file_data = $file_result->fetch_assoc();

                $stmt = $conn->prepare("UPDATE uploads SET is_approved = 2, approved_by = ?, approved_date = NOW(), feedback = ? WHERE id = ?");
                $stmt->bind_param("isi", $_SESSION['user_id'], $feedback, $file_id);

                if ($stmt->execute()) {
                    // Add notification for the uploader
                    if ($file_data) {
                        $notification_text = "Your upload \"" . $file_data['title'] . "\" has been rejected. Reason: " . $feedback;
                        $notify_stmt = $conn->prepare("INSERT INTO notifications (user_id, notification_text, related_to, related_id, created_at) VALUES (?, ?, 'upload', ?, NOW())");
                        $notify_stmt->bind_param("isi", $file_data['uploader_id'], $notification_text, $file_id);
                        $notify_stmt->execute();
                    }

                    $_SESSION['success_message'] = "File has been rejected successfully";
                } else {
                    $_SESSION['error_message'] = "Failed to reject file: " . $conn->error;
                }
            }
        }
    }

    // Redirect to prevent form resubmission
    header("Location: moderate_uploads.php");
    exit();
}

// Get pending uploads (is_approved = 0)
$query = "SELECT u.id, u.title, u.subject, u.category, u.semester, u.description, 
          u.file_path, u.file_type, u.file_size, u.uploader_name, u.upload_date,
          COUNT(d.id) as download_count
          FROM uploads u
          LEFT JOIN downloads d ON u.id = d.file_id
          WHERE u.is_approved = 0
          GROUP BY u.id
          ORDER BY u.upload_date DESC";
$result = $conn->query($query);

// Set page title and include header
$page_title = "Moderate Uploads";
include 'header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tasks me-2"></i> Moderate Uploads</h2>
        <div>
            <a href="moderation_history.php" class="btn btn-outline-secondary">
                <i class="fas fa-history me-1"></i> Moderation History
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <div class="row">
            <?php while ($file = $result->fetch_assoc()): ?>
                <div class="col-lg-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="badge bg-warning text-dark">Pending Review</span>
                            <small class="text-muted">Uploaded
                                <?php echo date('M d, Y', strtotime($file['upload_date'])); ?></small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="card-title"><?php echo htmlspecialchars($file['title']); ?></h5>

                                    <div class="mb-3">
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($file['category']); ?></span>
                                        <span
                                            class="badge bg-secondary"><?php echo htmlspecialchars($file['subject']); ?></span>
                                        <?php if (!empty($file['semester'])): ?>
                                            <span class="badge bg-info">Semester <?php echo $file['semester']; ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <p class="card-text">
                                        <?php if (!empty($file['description'])): ?>
                                            <?php echo htmlspecialchars($file['description']); ?>
                                        <?php else: ?>
                                            <span class="text-muted">No description provided</span>
                                        <?php endif; ?>
                                    </p>

                                    <div class="d-flex mt-3 text-muted small">
                                        <div class="me-3"><i class="fas fa-user me-1"></i>
                                            <?php echo htmlspecialchars($file['uploader_name']); ?></div>
                                        <div class="me-3"><i class="fas fa-file me-1"></i>
                                            <?php echo strtoupper($file['file_type']); ?></div>
                                        <div><i class="fas fa-weight me-1"></i>
                                            <?php echo round($file['file_size'] / 1024, 2); ?> KB</div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-column align-items-end justify-content-between">
                                    <div class="mb-3">
                                        <a href="view_file.php?id=<?php echo $file['id']; ?>&preview=1"
                                            class="btn btn-outline-primary btn-sm" target="_blank">
                                            <i class="fas fa-eye me-1"></i> Preview File
                                        </a>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#approveModal<?php echo $file['id']; ?>">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#rejectModal<?php echo $file['id']; ?>">
                                            <i class="fas fa-times me-1"></i> Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Approve Modal -->
                <div class="modal fade" id="approveModal<?php echo $file['id']; ?>" tabindex="-1"
                    aria-labelledby="approveModalLabel<?php echo $file['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <input type="hidden" name="file_id" value="<?php echo $file['id']; ?>">
                                <input type="hidden" name="action" value="approve">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="approveModalLabel<?php echo $file['id']; ?>">Approve Upload</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to approve
                                        "<strong><?php echo htmlspecialchars($file['title']); ?></strong>"?</p>
                                    <div class="mb-3">
                                        <label for="feedback<?php echo $file['id']; ?>" class="form-label">Feedback
                                            (optional)</label>
                                        <textarea class="form-control" id="feedback<?php echo $file['id']; ?>" name="feedback"
                                            rows="3" placeholder="Any feedback for the uploader"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-1"></i> Approve
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div class="modal fade" id="rejectModal<?php echo $file['id']; ?>" tabindex="-1"
                    aria-labelledby="rejectModalLabel<?php echo $file['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <input type="hidden" name="file_id" value="<?php echo $file['id']; ?>">
                                <input type="hidden" name="action" value="reject">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="rejectModalLabel<?php echo $file['id']; ?>">Reject Upload</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to reject
                                        "<strong><?php echo htmlspecialchars($file['title']); ?></strong>"?</p>
                                    <div class="mb-3">
                                        <label for="reject_feedback<?php echo $file['id']; ?>" class="form-label">Reason for
                                            rejection <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="reject_feedback<?php echo $file['id']; ?>"
                                            name="feedback" rows="3" placeholder="Explain why this file is being rejected"
                                            required></textarea>
                                        <div class="form-text">This feedback will be sent to the uploader.</div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times me-1"></i> Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> There are no pending uploads to review at this time.
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>