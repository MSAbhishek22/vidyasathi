<?php
session_start();
require_once 'config.php';

// Get filter parameters
$branch_filter = isset($_GET['branch']) ? $_GET['branch'] : '';
$semester_filter = isset($_GET['semester']) ? $_GET['semester'] : '';
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Build SQL query based on filters
$where_conditions = ["is_mentor = 1"];
$params = [];
$param_types = '';

// Search query filter
if (!empty($search_query)) {
    $where_conditions[] = "(name LIKE ? OR bio LIKE ?)";
    $search_param = "%{$search_query}%";
    array_push($params, $search_param, $search_param);
    $param_types .= 'ss';
}

// Branch filter
if (!empty($branch_filter)) {
    $where_conditions[] = "branch = ?";
    $params[] = $branch_filter;
    $param_types .= 's';
}

// Semester filter
if (!empty($semester_filter) && $semester_filter != 'all') {
    // For mentors, we usually want seniors, so filter by semester greater than selected
    $where_conditions[] = "semester >= ?";
    $params[] = $semester_filter;
    $param_types .= 's';
}

// Build the final WHERE clause
$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get mentors
$sql = "SELECT u.id, u.name, u.role, u.bio, u.profile_image, u.branch, u.semester, u.xp_points, u.created_at,
        (SELECT COUNT(*) FROM mentorship_requests WHERE mentor_id = u.id AND status = 'accepted') as active_mentorships,
        (SELECT COUNT(*) FROM uploads WHERE uploader_id = u.id AND is_approved = 1) as resource_count
        FROM users u
        $where_clause
        ORDER BY u.xp_points DESC, active_mentorships DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$mentors = [];
while ($row = $result->fetch_assoc()) {
    $mentors[] = $row;
}

// Get unique branches for filter
$branches_query = "SELECT DISTINCT branch FROM users WHERE is_mentor = 1 AND branch IS NOT NULL AND branch != '' ORDER BY branch";
$branches_result = $conn->query($branches_query);
$branches = [];
while ($row = $branches_result->fetch_assoc()) {
    $branches[] = $row['branch'];
}

// Set page title and include header
$page_title = "Find Mentors";
include 'header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Find a Mentor</h1>
            <p class="text-muted">Connect with experienced students who can guide you through your academic journey</p>
        </div>
        <div class="col-md-4">
            <form action="find_mentors.php" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search mentors..." name="q"
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
                    <form action="find_mentors.php" method="GET">
                        <!-- Preserve search query if it exists -->
                        <?php if (!empty($search_query)): ?>
                            <input type="hidden" name="q" value="<?php echo htmlspecialchars($search_query); ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="branch" class="form-label">Branch/Department</label>
                            <select class="form-select" id="branch" name="branch">
                                <option value="">All Branches</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?php echo htmlspecialchars($branch); ?>" <?php if ($branch_filter === $branch)
                                           echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($branch); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="semester" class="form-label">Minimum Semester</label>
                            <select class="form-select" id="semester" name="semester">
                                <option value="all">All Semesters</option>
                                <?php for ($i = 3; $i <= 8; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php if ($semester_filter == $i)
                                           echo 'selected'; ?>>
                                        Semester <?php echo $i; ?>+
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <div class="form-text">Find mentors in semester 3 or above</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Apply Filters
                            </button>
                            <a href="find_mentors.php" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Why Find a Mentor?</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="me-3 text-primary">
                            <i class="fas fa-graduation-cap fa-2x"></i>
                        </div>
                        <div>
                            <h6>Academic Guidance</h6>
                            <p class="text-muted small">Get help with coursework, exams, and academic challenges.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="me-3 text-primary">
                            <i class="fas fa-route fa-2x"></i>
                        </div>
                        <div>
                            <h6>Navigate Your Path</h6>
                            <p class="text-muted small">Plan your academic journey with experienced guidance.</p>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="me-3 text-primary">
                            <i class="fas fa-network-wired fa-2x"></i>
                        </div>
                        <div>
                            <h6>Build Connections</h6>
                            <p class="text-muted small mb-0">Expand your network and learn from others' experiences.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mentors List -->
        <div class="col-lg-9">
            <?php if (count($mentors) > 0): ?>
                <div class="row g-4">
                    <?php foreach ($mentors as $mentor): ?>
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0">
                                            <?php if (!empty($mentor['profile_image']) && file_exists($mentor['profile_image'])): ?>
                                                <img src="<?php echo htmlspecialchars($mentor['profile_image']); ?>" alt="Profile"
                                                    class="rounded-circle" style="width: 64px; height: 64px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                    style="width: 64px; height: 64px; font-size: 1.5rem;">
                                                    <?php echo strtoupper(substr($mentor['name'], 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-1">
                                                <a href="user_profile.php?id=<?php echo $mentor['id']; ?>"
                                                    class="text-decoration-none text-body">
                                                    <?php echo htmlspecialchars($mentor['name']); ?>
                                                </a>
                                            </h5>
                                            <div class="text-muted small mb-2">
                                                <?php if (!empty($mentor['branch'])): ?>
                                                    <?php echo htmlspecialchars($mentor['branch']); ?>
                                                <?php endif; ?>
                                                <?php if (!empty($mentor['semester'])): ?>
                                                    • Semester <?php echo htmlspecialchars($mentor['semester']); ?>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <span class="badge bg-primary me-1">Mentor</span>
                                                <span class="badge bg-light text-dark"><?php echo $mentor['xp_points']; ?>
                                                    XP</span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!empty($mentor['bio'])): ?>
                                        <p class="card-text text-muted small">
                                            <?php
                                            $bio = htmlspecialchars($mentor['bio']);
                                            echo (strlen($bio) > 150) ? substr($bio, 0, 150) . '...' : $bio;
                                            ?>
                                        </p>
                                    <?php endif; ?>

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div class="small text-muted">
                                            <span class="me-3"><i class="fas fa-users me-1"></i>
                                                <?php echo $mentor['active_mentorships']; ?> active mentorships</span>
                                            <span><i class="fas fa-file-alt me-1"></i> <?php echo $mentor['resource_count']; ?>
                                                resources</span>
                                        </div>
                                        <a href="request_mentorship.php?mentor_id=<?php echo $mentor['id']; ?>"
                                            class="btn btn-sm btn-success">
                                            <i class="fas fa-user-graduate me-1"></i> Request Mentorship
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                        <h4>No mentors found</h4>
                        <p class="text-muted">Try adjusting your search criteria or filters</p>
                        <div class="mt-3">
                            <a href="find_mentors.php" class="btn btn-primary">Clear all filters</a>
                            <a href="become_mentor.php" class="btn btn-outline-primary ms-2">Become a Mentor</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mt-4">
                <div class="card-body bg-light">
                    <h5>Are you a senior student?</h5>
                    <p class="mb-0">Consider becoming a mentor to help juniors navigate their academic journey. Share
                        your knowledge and gain valuable experience.</p>
                    <div class="mt-3">
                        <a href="become_mentor.php" class="btn btn-outline-primary">
                            <i class="fas fa-user-graduate me-1"></i> Become a Mentor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>