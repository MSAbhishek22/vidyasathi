<?php
session_start();
require_once 'config.php';

// Check for correct role and access
if (
    !isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) ||
    ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'moderator')
) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Get status filter
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'pending';
$subject_filter = isset($_GET['subject']) ? $_GET['subject'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Build query based on filters
$query_conditions = [];
$query_params = [];
$param_types = "";

// Status filter
if ($status_filter === 'pending') {
    $query_conditions[] = "approved = 0";
} elseif ($status_filter === 'approved') {
    $query_conditions[] = "approved = 1";
} elseif ($status_filter === 'rejected') {
    $query_conditions[] = "approved = -1";
}

// Subject filter
if (!empty($subject_filter)) {
    $query_conditions[] = "subject = ?";
    $query_params[] = $subject_filter;
    $param_types .= "s";
}

// Category filter
if (!empty($category_filter)) {
    $query_conditions[] = "category = ?";
    $query_params[] = $category_filter;
    $param_types .= "s";
}

// Build the final query
$base_sql = "SELECT * FROM uploads";
if (!empty($query_conditions)) {
    $base_sql .= " WHERE " . implode(" AND ", $query_conditions);
}
$base_sql .= " ORDER BY upload_date DESC";

// Execute query
$stmt = $conn->prepare($base_sql);
if (!empty($query_params)) {
    $stmt->bind_param($param_types, ...$query_params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get unique subjects for filter dropdown
$subject_query = "SELECT DISTINCT subject FROM uploads ORDER BY subject";
$subject_result = $conn->query($subject_query);
$subjects = [];
while ($row = $subject_result->fetch_assoc()) {
    $subjects[] = $row['subject'];
}

// Page title and include header
$page_title = "Review Uploads";
include 'header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3">Review Student Uploads</h1>
            <p class="text-muted">Review and approve or reject uploaded academic resources</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                                <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>
                                    Pending Review</option>
                                <option value="approved" <?php echo $status_filter === 'approved' ? 'selected' : ''; ?>>
                                    Approved</option>
                                <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>
                                    Rejected</option>
                                <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="subject" class="form-label">Subject</label>
                            <select class="form-select" id="subject" name="subject" onchange="this.form.submit()">
                                <option value="">All Subjects</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?php echo htmlspecialchars($subject); ?>" <?php echo $subject_filter === $subject ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($subject); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <option value="Notes" <?php echo $category_filter === 'Notes' ? 'selected' : ''; ?>>Notes
                                </option>
                                <option value="PYQ" <?php echo $category_filter === 'PYQ' ? 'selected' : ''; ?>>Previous
                                    Year Questions</option>
                                <option value="Assignment" <?php echo $category_filter === 'Assignment' ? 'selected' : ''; ?>>Assignment</option>
                                <option value="Book" <?php echo $category_filter === 'Book' ? 'selected' : ''; ?>>Book
                                </option>
                                <option value="Other" <?php echo $category_filter === 'Other' ? 'selected' : ''; ?>>Other
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <a href="review_uploads.php" class="btn btn-outline-secondary d-block">Reset Filters</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results count -->
    <div class="row mb-3">
        <div class="col-md-12">
            <p class="text-muted"><?php echo $result->num_rows; ?> resources found</p>
        </div>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">
            No resources found matching your filters.
        </div>
    <?php else: ?>
        <!-- Upload table -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover border">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Subject / Semester</th>
                                <th>Category</th>
                                <th>Uploader</th>
                                <th>Uploaded On</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($upload = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#previewModal<?php echo $upload['id']; ?>">
                                            <?php echo htmlspecialchars($upload['title']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($upload['subject']); ?>
                                        <?php if (!empty($upload['semester'])): ?>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($upload['semester']); ?>
                                                Sem</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span
                                            class="badge bg-secondary"><?php echo htmlspecialchars($upload['category']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($upload['uploader_name']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($upload['upload_date'])); ?></td>
                                    <td>
                                        <?php if ($upload['approved'] == 1): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php elseif ($upload['approved'] == -1): ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#previewModal<?php echo $upload['id']; ?>">
                                                <i class="fas fa-eye"></i> Preview
                                            </a>

                                            <?php if ($upload['approved'] == 0): ?>
                                                <form action="update_status.php" method="POST" class="d-inline ms-1"
                                                    onsubmit="return confirm('Are you sure you want to approve this file?');">
                                                    <input type="hidden" name="file_id" value="<?php echo $upload['id']; ?>">
                                                    <button type="submit" name="status" value="approved"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                </form>

                                                <form action="update_status.php" method="POST" class="d-inline ms-1"
                                                    onsubmit="return confirm('Are you sure you want to reject this file?');">
                                                    <input type="hidden" name="file_id" value="<?php echo $upload['id']; ?>">
                                                    <button type="submit" name="status" value="rejected"
                                                        class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form action="update_status.php" method="POST" class="d-inline ms-1">
                                                    <input type="hidden" name="file_id" value="<?php echo $upload['id']; ?>">
                                                    <input type="hidden" name="reset" value="1">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-undo"></i> Reset Status
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Preview Modal -->
                                <div class="modal fade" id="previewModal<?php echo $upload['id']; ?>" tabindex="-1"
                                    aria-labelledby="previewModalLabel<?php echo $upload['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="previewModalLabel<?php echo $upload['id']; ?>">
                                                    <?php echo htmlspecialchars($upload['title']); ?>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <p><strong>Uploader:</strong>
                                                            <?php echo htmlspecialchars($upload['uploader_name']); ?></p>
                                                        <p><strong>Subject:</strong>
                                                            <?php echo htmlspecialchars($upload['subject']); ?></p>
                                                        <p><strong>Semester:</strong>
                                                            <?php echo htmlspecialchars($upload['semester'] ?? 'Not specified'); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Category:</strong>
                                                            <?php echo htmlspecialchars($upload['category']); ?></p>
                                                        <p><strong>Uploaded:</strong>
                                                            <?php echo date('F d, Y', strtotime($upload['upload_date'])); ?></p>
                                                        <p><strong>File Size:</strong>
                                                            <?php echo round($upload['file_size'] / 1024, 2); ?> KB</p>
                                                    </div>
                                                </div>

                                                <?php if (!empty($upload['description'])): ?>
                                                    <div class="mb-3">
                                                        <h6>Description:</h6>
                                                        <p class="text-muted">
                                                            <?php echo nl2br(htmlspecialchars($upload['description'])); ?></p>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="mb-3">
                                                    <h6>File Preview:</h6>
                                                    <?php
                                                    $file_path = $upload['file_path'];
                                                    $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

                                                    if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                        echo '<img src="' . htmlspecialchars($file_path) . '" class="img-fluid rounded" alt="Preview">';
                                                    } elseif ($file_extension === 'pdf') {
                                                        echo '<div class="ratio ratio-16x9">
                                                                <iframe src="' . htmlspecialchars($file_path) . '" allowfullscreen></iframe>
                                                              </div>';
                                                    } else {
                                                        echo '<div class="text-center p-5 bg-light rounded">
                                                                <i class="fas fa-file fa-4x mb-3 text-primary"></i>
                                                                <p>Preview not available for this file type</p>
                                                              </div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="<?php echo htmlspecialchars($upload['file_path']); ?>"
                                                    class="btn btn-primary" download>
                                                    <i class="fas fa-download"></i> Download
                                                </a>

                                                <?php if ($upload['approved'] == 0): ?>
                                                    <form action="update_status.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="file_id" value="<?php echo $upload['id']; ?>">
                                                        <button type="submit" name="status" value="approved"
                                                            class="btn btn-success">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    </form>

                                                    <form action="update_status.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="file_id" value="<?php echo $upload['id']; ?>">
                                                        <button type="submit" name="status" value="rejected" class="btn btn-danger">
                                                            <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>