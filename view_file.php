<?php
session_start();
require_once 'config.php';

// Check if file ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid file request";
    header("Location: index.php");
    exit();
}

$file_id = intval($_GET['id']);
$is_preview = isset($_GET['preview']) && $_GET['preview'] == 1;
$is_moderator = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'moderator';

// Get file information
$query = "SELECT u.*, 
          us.username as uploader_username,
          COUNT(d.id) as download_count,
          AVG(r.rating) as avg_rating,
          COUNT(r.id) as rating_count
          FROM uploads u
          LEFT JOIN users us ON u.uploader_id = us.id
          LEFT JOIN downloads d ON u.id = d.file_id
          LEFT JOIN ratings r ON u.id = r.file_id
          WHERE u.id = ?
          GROUP BY u.id";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $file_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error_message'] = "File not found";
    header("Location: index.php");
    exit();
}

$file = $result->fetch_assoc();

// Check if the file is approved or if the current user is a moderator or the uploader
if ($file['is_approved'] != 1 && !$is_moderator && (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $file['uploader_id'])) {
    $_SESSION['error_message'] = "This file is not available for viewing";
    header("Location: index.php");
    exit();
}

// Check if the user is logged in for file downloading
$can_download = isset($_SESSION['user_id']);

// Track download if this is a direct download request (not preview)
if (isset($_GET['download']) && $_GET['download'] == 1 && $can_download) {
    // Check if this is a new download
    $check_download = $conn->prepare("SELECT id FROM downloads WHERE user_id = ? AND file_id = ?");
    $check_download->bind_param("ii", $_SESSION['user_id'], $file_id);
    $check_download->execute();
    $existing_download = $check_download->get_result();

    // Only count as a new download if user hasn't downloaded before
    if ($existing_download->num_rows == 0) {
        // Record the download
        $download_stmt = $conn->prepare("INSERT INTO downloads (file_id, user_id, download_date) VALUES (?, ?, NOW())");
        $download_stmt->bind_param("ii", $file_id, $_SESSION['user_id']);
        $download_stmt->execute();

        // Award XP to the downloader if the table exists
        $table_check = $conn->query("SHOW TABLES LIKE 'xp_activities'");
        if ($table_check->num_rows > 0) {
            $xp_points = 2; // XP for downloading a file
            $xp_stmt = $conn->prepare("INSERT INTO xp_activities (user_id, activity_type, xp_earned, description, related_to, related_id, created_at) VALUES (?, 'download', ?, ?, 'upload', ?, NOW())");
            $description = "Downloaded: {$file['title']}";
            $xp_stmt->bind_param("iisi", $_SESSION['user_id'], $xp_points, $description, $file_id);
            $xp_stmt->execute();

            // Update user's XP points
            $update_xp = $conn->prepare("UPDATE users SET xp_points = xp_points + ? WHERE id = ?");
            $update_xp->bind_param("ii", $xp_points, $_SESSION['user_id']);
            $update_xp->execute();
        }
    }

    // Serve the file for download
    $file_path = $file['file_path'];
    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        $_SESSION['error_message'] = "File not found on server";
        header("Location: view_file.php?id=" . $file_id);
        exit();
    }
}

// Get user's rating if logged in
$user_rating = 0;
if (isset($_SESSION['user_id'])) {
    $rating_stmt = $conn->prepare("SELECT rating FROM ratings WHERE user_id = ? AND file_id = ?");
    $rating_stmt->bind_param("ii", $_SESSION['user_id'], $file_id);
    $rating_stmt->execute();
    $rating_result = $rating_stmt->get_result();
    if ($rating_result->num_rows > 0) {
        $rating_row = $rating_result->fetch_assoc();
        $user_rating = $rating_row['rating'];
    }
}

// Handle rating submission
if (isset($_POST['rating']) && isset($_SESSION['user_id'])) {
    $rating = intval($_POST['rating']);
    if ($rating >= 1 && $rating <= 5) {
        // Check if user has already rated
        $check_rating = $conn->prepare("SELECT id FROM ratings WHERE user_id = ? AND file_id = ?");
        $check_rating->bind_param("ii", $_SESSION['user_id'], $file_id);
        $check_rating->execute();
        $existing_rating = $check_rating->get_result();

        if ($existing_rating->num_rows > 0) {
            // Update existing rating
            $existing_row = $existing_rating->fetch_assoc();
            $update_rating = $conn->prepare("UPDATE ratings SET rating = ?, updated_at = NOW() WHERE id = ?");
            $update_rating->bind_param("ii", $rating, $existing_row['id']);
            $update_rating->execute();
        } else {
            // Insert new rating
            $insert_rating = $conn->prepare("INSERT INTO ratings (file_id, user_id, rating, created_at) VALUES (?, ?, ?, NOW())");
            $insert_rating->bind_param("iii", $file_id, $_SESSION['user_id'], $rating);
            $insert_rating->execute();

            // Award XP for first rating if the table exists
            $table_check = $conn->query("SHOW TABLES LIKE 'xp_activities'");
            if ($table_check->num_rows > 0) {
                $xp_points = 1; // XP for rating a file
                $xp_stmt = $conn->prepare("INSERT INTO xp_activities (user_id, activity_type, xp_earned, description, related_to, related_id, created_at) VALUES (?, 'rating', ?, ?, 'upload', ?, NOW())");
                $description = "Rated: {$file['title']}";
                $xp_stmt->bind_param("iisi", $_SESSION['user_id'], $xp_points, $description, $file_id);
                $xp_stmt->execute();

                // Update user's XP points
                $update_xp = $conn->prepare("UPDATE users SET xp_points = xp_points + ? WHERE id = ?");
                $update_xp->bind_param("ii", $xp_points, $_SESSION['user_id']);
                $update_xp->execute();
            }
        }

        $_SESSION['success_message'] = "Your rating has been saved";
        header("Location: view_file.php?id=" . $file_id);
        exit();
    }
}

// Set page title and include header
$page_title = "View File: " . $file['title'];
include 'header.php';

// Function to get file extension
function getFileExtension($filename)
{
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

// Function to check if file is previewable
function isPreviewable($extension)
{
    $previewable_types = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
    return in_array($extension, $previewable_types);
}

// Get file extension
$file_extension = getFileExtension($file['file_path']);
$can_preview = isPreviewable($file_extension);
?>

<div class="container mt-4">
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

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <?php if ($file['is_approved'] == 1): ?>
                            <span class="badge bg-success">Approved</span>
                        <?php elseif ($file['is_approved'] == 2): ?>
                            <span class="badge bg-danger">Rejected</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Pending Review</span>
                        <?php endif; ?>
                        <span class="ms-2 text-muted">Uploaded on
                            <?php echo date('M d, Y', strtotime($file['upload_date'])); ?></span>
                    </div>

                    <?php if ($can_download): ?>
                        <a href="view_file.php?id=<?php echo $file_id; ?>&download=1" class="btn btn-primary btn-sm">
                            <i class="fas fa-download me-1"></i> Download
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-secondary btn-sm">
                            <i class="fas fa-sign-in-alt me-1"></i> Login to Download
                        </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <h4 class="card-title"><?php echo htmlspecialchars($file['title']); ?></h4>

                    <div class="mb-3">
                        <span class="badge bg-primary"><?php echo htmlspecialchars($file['category']); ?></span>
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($file['subject']); ?></span>
                        <?php if (!empty($file['semester'])): ?>
                            <span class="badge bg-info">Semester <?php echo $file['semester']; ?></span>
                        <?php endif; ?>
                    </div>

                    <p class="card-text">
                        <?php if (!empty($file['description'])): ?>
                            <?php echo nl2br(htmlspecialchars($file['description'])); ?>
                        <?php else: ?>
                            <span class="text-muted">No description provided</span>
                        <?php endif; ?>
                    </p>

                    <div class="d-flex flex-wrap mt-3 text-muted small">
                        <div class="me-3 mb-2"><i class="fas fa-user me-1"></i> Uploaded by
                            <?php echo htmlspecialchars($file['uploader_name']); ?></div>
                        <div class="me-3 mb-2"><i class="fas fa-file me-1"></i>
                            <?php echo strtoupper($file_extension); ?> File</div>
                        <div class="me-3 mb-2"><i class="fas fa-weight me-1"></i>
                            <?php echo round($file['file_size'] / 1024, 2); ?> KB</div>
                        <div class="me-3 mb-2"><i class="fas fa-download me-1"></i>
                            <?php echo $file['download_count']; ?> Downloads</div>
                        <div class="mb-2">
                            <i class="fas fa-star me-1"></i>
                            <?php if ($file['avg_rating']): ?>
                                <?php echo number_format($file['avg_rating'], 1); ?>/5 (<?php echo $file['rating_count']; ?>
                                ratings)
                            <?php else: ?>
                                No ratings yet
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($can_preview && $file['is_approved'] == 1 || $is_preview && $is_moderator): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-eye me-2"></i> File Preview</h5>
                    </div>
                    <div class="card-body">
                        <?php if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                            <img src="<?php echo $file['file_path']; ?>" class="img-fluid"
                                alt="<?php echo htmlspecialchars($file['title']); ?>">
                        <?php elseif ($file_extension === 'pdf'): ?>
                            <div class="ratio ratio-16x9" style="min-height: 500px;">
                                <iframe src="<?php echo $file['file_path']; ?>" allowfullscreen></iframe>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif (!$can_preview): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Preview is not available for this file type. Please download the
                    file to view it.
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <?php if (isset($_SESSION['user_id']) && $file['is_approved'] == 1): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-star me-2"></i> Rate This File</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3 text-center">
                                <div class="rating-stars">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>"
                                            <?php echo ($user_rating == $i) ? 'checked' : ''; ?>>
                                        <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> stars"><i
                                                class="fas fa-star"></i></label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit Rating</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($file['feedback'] && ($is_moderator || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $file['uploader_id']))): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-comment me-2"></i> Moderator Feedback</h5>
                    </div>
                    <div class="card-body">
                        <p><?php echo nl2br(htmlspecialchars($file['feedback'])); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i> Related Resources</h5>
                </div>
                <div class="card-body">
                    <?php
                    // Get related files (same subject/category)
                    $related_query = "SELECT id, title, category, subject 
                                      FROM uploads 
                                      WHERE is_approved = 1 
                                      AND id != ? 
                                      AND (subject = ? OR category = ?) 
                                      ORDER BY upload_date DESC 
                                      LIMIT 5";
                    $related_stmt = $conn->prepare($related_query);
                    $related_stmt->bind_param("iss", $file_id, $file['subject'], $file['category']);
                    $related_stmt->execute();
                    $related_result = $related_stmt->get_result();

                    if ($related_result->num_rows > 0):
                        ?>
                        <ul class="list-group list-group-flush">
                            <?php while ($related = $related_result->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <a href="view_file.php?id=<?php echo $related['id']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($related['title']); ?>
                                    </a>
                                    <div class="small text-muted">
                                        <?php echo htmlspecialchars($related['category']); ?> /
                                        <?php echo htmlspecialchars($related['subject']); ?>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No related resources found</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Star Rating CSS */
    .rating-stars {
        display: inline-flex;
        flex-direction: row-reverse;
        justify-content: center;
    }

    .rating-stars input {
        display: none;
    }

    .rating-stars label {
        cursor: pointer;
        font-size: 2rem;
        color: #ccc;
        margin: 0 2px;
    }

    .rating-stars input:checked~label,
    .rating-stars label:hover,
    .rating-stars label:hover~label {
        color: #f8d32a;
    }

    .rating-stars input:checked+label:hover,
    .rating-stars input:checked~label:hover,
    .rating-stars input:checked~label:hover~label,
    .rating-stars label:hover~input:checked~label {
        color: #f8d32a;
    }
</style>

<?php include 'footer.php'; ?>