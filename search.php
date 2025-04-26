<?php
session_start();
require_once 'config.php';

// Get filter parameters from URL
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$subject_filter = isset($_GET['subject']) ? $_GET['subject'] : '';
$semester_filter = isset($_GET['semester']) ? $_GET['semester'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build SQL query based on filters
$where_conditions = [];
$params = [];
$param_types = '';

// Always show only approved files
$where_conditions[] = "u.is_approved = 1";

// Search query filter
if (!empty($search_query)) {
    $where_conditions[] = "(u.title LIKE ? OR u.description LIKE ? OR u.subject LIKE ?)";
    $search_param = "%{$search_query}%";
    array_push($params, $search_param, $search_param, $search_param);
    $param_types .= 'sss';
}

// Subject filter
if (!empty($subject_filter)) {
    $where_conditions[] = "u.subject = ?";
    $params[] = $subject_filter;
    $param_types .= 's';
}

// Semester filter
if (!empty($semester_filter)) {
    $where_conditions[] = "u.semester = ?";
    $params[] = $semester_filter;
    $param_types .= 's';
}

// Category filter
if (!empty($category_filter)) {
    $where_conditions[] = "u.category = ?";
    $params[] = $category_filter;
    $param_types .= 's';
}

// Build the final WHERE clause
$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Determine the ORDER BY clause based on sort_by
switch ($sort_by) {
    case 'oldest':
        $order_by = "ORDER BY u.upload_date ASC";
        break;
    case 'downloads':
        $order_by = "ORDER BY download_count DESC";
        break;
    case 'rating':
        $order_by = "ORDER BY avg_rating DESC";
        break;
    case 'newest':
    default:
        $order_by = "ORDER BY u.upload_date DESC";
        break;
}

// Build the complete query
$sql = "SELECT u.id, u.title, u.subject, u.category, u.semester, u.description, 
        u.file_path, u.file_type, u.uploader_name, u.upload_date,
        COUNT(DISTINCT d.id) AS download_count,
        AVG(r.rating) AS avg_rating,
        COUNT(DISTINCT r.id) AS rating_count
        FROM uploads u
        LEFT JOIN downloads d ON u.id = d.file_id
        LEFT JOIN ratings r ON u.id = r.file_id
        $where_clause
        GROUP BY u.id
        $order_by
        LIMIT 100";

// Execute the query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$results = $stmt->get_result();
$total_results = $results->num_rows;

// Get unique subjects for filter dropdowns
$subjects_query = "SELECT DISTINCT subject FROM uploads WHERE is_approved = 1 ORDER BY subject";
$subjects_result = $conn->query($subjects_query);
$subjects = [];
while ($row = $subjects_result->fetch_assoc()) {
    if (!empty($row['subject'])) {
        $subjects[] = $row['subject'];
    }
}

// Get unique semesters
$semesters_query = "SELECT DISTINCT semester FROM uploads WHERE is_approved = 1 AND semester != '' ORDER BY semester";
$semesters_result = $conn->query($semesters_query);
$semesters = [];
while ($row = $semesters_result->fetch_assoc()) {
    if (!empty($row['semester'])) {
        $semesters[] = $row['semester'];
    }
}

// Categories for filter
$categories = ['Notes', 'Assignments', 'Previous Year Questions', 'Books', 'Other'];

// Set page title and include header
$page_title = !empty($search_query) ? "Search: " . htmlspecialchars($search_query) : "Browse Resources";
include 'header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <?php if (!empty($search_query)): ?>
                    Search Results for "<?php echo htmlspecialchars($search_query); ?>"
                <?php else: ?>
                    Browse Learning Resources
                <?php endif; ?>
            </h1>
            <p class="text-muted">
                <?php echo $total_results; ?> resources found
                <?php if (!empty($subject_filter)): ?>
                    in subject "<?php echo htmlspecialchars($subject_filter); ?>"
                <?php endif; ?>
                <?php if (!empty($category_filter)): ?>
                    of type "<?php echo htmlspecialchars($category_filter); ?>"
                <?php endif; ?>
                <?php if (!empty($semester_filter)): ?>
                    for semester "<?php echo htmlspecialchars($semester_filter); ?>"
                <?php endif; ?>
            </p>
        </div>
        <div class="col-md-4">
            <form action="search.php" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search resources..." name="q"
                        value="<?php echo htmlspecialchars($search_query); ?>" aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i> Filters</h5>
                </div>
                <div class="card-body">
                    <form action="search.php" method="GET">
                        <!-- Preserve search query if it exists -->
                        <?php if (!empty($search_query)): ?>
                            <input type="hidden" name="q" value="<?php echo htmlspecialchars($search_query); ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <select class="form-select" id="subject" name="subject">
                                <option value="">All Subjects</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?php echo htmlspecialchars($subject); ?>" <?php if ($subject_filter === $subject)
                                           echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($subject); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select" id="semester" name="semester">
                                <option value="">All Semesters</option>
                                <?php foreach ($semesters as $semester): ?>
                                    <option value="<?php echo htmlspecialchars($semester); ?>" <?php if ($semester_filter === $semester)
                                           echo 'selected'; ?>>
                                        Semester <?php echo htmlspecialchars($semester); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Resource Type</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">All Types</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category); ?>" <?php if ($category_filter === $category)
                                           echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($category); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sort" class="form-label">Sort By</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="newest" <?php if ($sort_by === 'newest')
                                    echo 'selected'; ?>>Newest First
                                </option>
                                <option value="oldest" <?php if ($sort_by === 'oldest')
                                    echo 'selected'; ?>>Oldest First
                                </option>
                                <option value="downloads" <?php if ($sort_by === 'downloads')
                                    echo 'selected'; ?>>Most
                                    Downloaded</option>
                                <option value="rating" <?php if ($sort_by === 'rating')
                                    echo 'selected'; ?>>Highest Rated
                                </option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Apply Filters
                            </button>
                            <a href="search.php" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (!empty($subjects)): ?>
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Popular Subjects</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php
                        $popular_subjects_query = "SELECT subject, COUNT(*) as count 
                                                  FROM uploads 
                                                  WHERE is_approved = 1 
                                                  GROUP BY subject 
                                                  ORDER BY count DESC 
                                                  LIMIT 5";
                        $popular_subjects = $conn->query($popular_subjects_query);
                        while ($subject = $popular_subjects->fetch_assoc()):
                            ?>
                            <a href="search.php?subject=<?php echo urlencode($subject['subject']); ?>"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($subject['subject']); ?>
                                <span class="badge bg-primary rounded-pill"><?php echo $subject['count']; ?></span>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Search Results -->
        <div class="col-lg-9">
            <?php if ($total_results > 0): ?>
                <div class="row g-4">
                    <?php while ($resource = $results->fetch_assoc()): ?>
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <span
                                            class="badge bg-primary"><?php echo htmlspecialchars($resource['category']); ?></span>
                                        <?php if (!empty($resource['semester'])): ?>
                                            <span class="badge bg-info">Semester
                                                <?php echo htmlspecialchars($resource['semester']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <small
                                        class="text-muted"><?php echo date('M d, Y', strtotime($resource['upload_date'])); ?></small>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="view_file.php?id=<?php echo $resource['id']; ?>"
                                            class="text-decoration-none text-body stretched-link">
                                            <?php echo htmlspecialchars($resource['title']); ?>
                                        </a>
                                    </h5>

                                    <div class="mb-2">
                                        <span
                                            class="badge bg-secondary"><?php echo htmlspecialchars($resource['subject']); ?></span>
                                        <span
                                            class="badge bg-light text-dark"><?php echo strtoupper($resource['file_type']); ?></span>
                                    </div>

                                    <?php if (!empty($resource['description'])): ?>
                                        <p class="card-text small text-muted">
                                            <?php
                                            $desc = htmlspecialchars($resource['description']);
                                            echo (strlen($desc) > 100) ? substr($desc, 0, 100) . '...' : $desc;
                                            ?>
                                        </p>
                                    <?php endif; ?>

                                    <div class="d-flex justify-content-between align-items-center mt-2 small text-muted">
                                        <div>
                                            <i class="fas fa-user me-1"></i>
                                            <?php echo htmlspecialchars($resource['uploader_name']); ?>
                                        </div>
                                        <div>
                                            <span class="me-2"><i class="fas fa-download me-1"></i>
                                                <?php echo $resource['download_count']; ?></span>

                                            <?php if ($resource['rating_count'] > 0): ?>
                                                <span><i class="fas fa-star text-warning me-1"></i>
                                                    <?php echo number_format($resource['avg_rating'], 1); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>No resources found</h4>
                        <p class="text-muted">Try adjusting your search criteria or filters</p>
                        <a href="search.php" class="btn btn-primary">Clear all filters</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>