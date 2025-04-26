<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Define allowed file types and maximum file size (5MB)
$allowed_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'zip'];
$max_file_size = 5 * 1024 * 1024; // 5MB in bytes

// Get categories for the dropdown
$categories = ['Notes', 'Assignments', 'Previous Year Questions', 'Books', 'Other'];

// Process upload form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $subject = trim($_POST['subject']);
    $category = $_POST['category'];
    $semester = $_POST['semester'];
    $description = trim($_POST['description']);
    $errors = [];

    // Validate inputs
    if (empty($title)) {
        $errors[] = "Title is required";
    }

    if (empty($subject)) {
        $errors[] = "Subject is required";
    }

    if (empty($category) || !in_array($category, $categories)) {
        $errors[] = "Please select a valid category";
    }

    // Check if file was uploaded
    if (!isset($_FILES['file']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = "Please select a file to upload";
    } else {
        $file = $_FILES['file'];

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $upload_error_messages = [
                UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
                UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form",
                UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded",
                UPLOAD_ERR_NO_FILE => "No file was uploaded",
                UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder",
                UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk",
                UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload"
            ];
            $errors[] = "Upload error: " . ($upload_error_messages[$file['error']] ?? "Unknown error");
        } else {
            // Check file size
            if ($file['size'] > $max_file_size) {
                $errors[] = "File size too large. Maximum size is 5MB";
            }

            // Check file type
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($file_extension, $allowed_types)) {
                $errors[] = "Invalid file type. Allowed types: " . implode(', ', $allowed_types);
            }
        }
    }

    // If no errors, process the upload
    if (empty($errors)) {
        // Create upload directory if it doesn't exist
        $upload_dir = "uploads/" . date("Y/m/");
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate a unique filename
        $new_filename = time() . '_' . uniqid() . '_' . $file['name'];
        $file_path = $upload_dir . $new_filename;

        // Move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Save file information to database
            $stmt = $conn->prepare("INSERT INTO uploads (uploader_id, uploader_name, title, subject, semester, category, description, file_path, file_size, file_type, upload_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param(
                "isssssssss",
                $_SESSION['user_id'],
                $_SESSION['user_name'],
                $title,
                $subject,
                $semester,
                $category,
                $description,
                $file_path,
                $file['size'],
                $file_extension
            );

            if ($stmt->execute()) {
                $upload_id = $conn->insert_id;

                // Add XP points if the xp_activities table exists
                $table_check = $conn->query("SHOW TABLES LIKE 'xp_activities'");
                if ($table_check->num_rows > 0) {
                    $xp_points = 5; // XP for uploading
                    $xp_stmt = $conn->prepare("INSERT INTO xp_activities (user_id, activity_type, xp_earned, description, related_to, related_id, created_at) VALUES (?, 'upload', ?, ?, 'upload', ?, NOW())");
                    $description = "Uploaded {$category}: {$title}";
                    $xp_stmt->bind_param("iisi", $_SESSION['user_id'], $xp_points, $description, $upload_id);
                    $xp_stmt->execute();

                    // Update user's XP points
                    $update_xp = $conn->prepare("UPDATE users SET xp_points = xp_points + ? WHERE id = ?");
                    $update_xp->bind_param("ii", $xp_points, $_SESSION['user_id']);
                    $update_xp->execute();
                }

                $_SESSION['success_message'] = "File uploaded successfully and pending approval";
                header("Location: my_uploads.php");
                exit();
            } else {
                $errors[] = "Database error: " . $conn->error;
                // Delete the uploaded file if database insertion fails
                unlink($file_path);
            }
        } else {
            $errors[] = "Failed to move uploaded file";
        }
    }
}

// Set page title and include header
$page_title = "Upload Learning Materials";
include 'header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-upload me-2"></i> Upload Learning Materials</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required
                                value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>">
                            <div class="invalid-feedback">Please provide a title</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label">Subject <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subject" name="subject" required
                                    value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>">
                                <div class="invalid-feedback">Please provide a subject</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="" disabled <?php echo !isset($category) ? 'selected' : ''; ?>>Select
                                        category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo (isset($category) && $category === $cat) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Please select a category</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select" id="semester" name="semester">
                                <option value="" <?php echo !isset($semester) ? 'selected' : ''; ?>>Select semester
                                    (optional)</option>
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo (isset($semester) && $semester == $i) ? 'selected' : ''; ?>>
                                        Semester <?php echo $i; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"
                                rows="3"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                            <div class="form-text">Add details about this material to help others understand what it
                                contains</div>
                        </div>

                        <div class="mb-4">
                            <label for="file" class="form-label">File <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="file" name="file" required>
                                <label class="input-group-text" for="file"><i class="fas fa-file-upload"></i></label>
                            </div>
                            <div class="form-text">
                                Allowed file types: <?php echo implode(', ', $allowed_types); ?><br>
                                Maximum file size: 5MB
                            </div>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i> Your upload will be reviewed by a moderator before
                            it becomes publicly available.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-cloud-upload-alt me-2"></i> Upload Material
                            </button>
                        </div>
                    </form>
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