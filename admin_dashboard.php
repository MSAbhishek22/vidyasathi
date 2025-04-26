<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    $_SESSION['error_message'] = "You don't have permission to access this page";
    header("Location: index.php");
    exit();
}

// Get total users count by role
$users_by_role_query = "SELECT role, COUNT(*) as count FROM users GROUP BY role ORDER BY count DESC";
$users_by_role_result = $conn->query($users_by_role_query);
$users_by_role = [];
$total_users = 0;
while ($row = $users_by_role_result->fetch_assoc()) {
    $users_by_role[] = $row;
    $total_users += $row['count'];
}

// Get total approved uploads
$uploads_query = "SELECT COUNT(*) as total, SUM(CASE WHEN is_approved = 1 THEN 1 ELSE 0 END) as approved, 
                 SUM(CASE WHEN is_approved = 0 THEN 1 ELSE 0 END) as pending,
                 SUM(CASE WHEN is_approved = 2 THEN 1 ELSE 0 END) as rejected
                 FROM uploads";
$uploads_result = $conn->query($uploads_query);
$uploads_data = $uploads_result->fetch_assoc();

// Get uploads per category
$category_query = "SELECT category, COUNT(*) as count FROM uploads WHERE is_approved = 1 GROUP BY category ORDER BY count DESC";
$category_result = $conn->query($category_query);
$categories = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row;
}

// Get most popular subjects
$subject_query = "SELECT subject, COUNT(*) as count FROM uploads WHERE is_approved = 1 GROUP BY subject ORDER BY count DESC LIMIT 10";
$subject_result = $conn->query($subject_query);
$subjects = [];
while ($row = $subject_result->fetch_assoc()) {
    $subjects[] = $row;
}

// Get top contributors
$contributors_query = "SELECT u.id, u.name, u.role, u.xp_points, COUNT(up.id) as upload_count 
                      FROM users u 
                      LEFT JOIN uploads up ON u.id = up.uploader_id AND up.is_approved = 1 
                      GROUP BY u.id 
                      ORDER BY upload_count DESC, u.xp_points DESC 
                      LIMIT 5";
$contributors_result = $conn->query($contributors_query);

// Get total mentorships
$mentorship_query = "SELECT COUNT(*) as total, 
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                    FROM mentorship_requests";
$mentorship_result = $conn->query($mentorship_query);
$mentorship_data = $mentorship_result->fetch_assoc();

// Get recent uploads
$recent_uploads_query = "SELECT u.id, u.title, u.category, u.upload_date, u.is_approved, us.name as uploader_name 
                        FROM uploads u 
                        JOIN users us ON u.uploader_id = us.id 
                        ORDER BY u.upload_date DESC 
                        LIMIT 5";
$recent_uploads_result = $conn->query($recent_uploads_query);

// Get total downloads
$downloads_query = "SELECT COUNT(*) as total FROM downloads";
$downloads_result = $conn->query($downloads_query);
$downloads_data = $downloads_result->fetch_assoc();

// Get recent activity from xp_activities
$recent_activity_query = "SELECT xa.id, xa.activity_type, xa.xp_earned, xa.description, xa.created_at, u.name as user_name 
                         FROM xp_activities xa 
                         JOIN users u ON xa.user_id = u.id 
                         ORDER BY xa.created_at DESC 
                         LIMIT 10";
$recent_activity_result = $conn->query($recent_activity_query);

// Set page title and include header
$page_title = "Admin Dashboard";
include 'header.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Admin Dashboard</h1>
                <div>
                    <a href="moderate_uploads.php" class="btn btn-primary">
                        <i class="fas fa-tasks me-1"></i> Moderate Uploads
                    </a>
                    <a href="manage_users.php" class="btn btn-outline-primary ms-2">
                        <i class="fas fa-users me-1"></i> Manage Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_users; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved Resources</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $uploads_data['approved']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Active Mentorships</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $mentorship_data['accepted']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Uploads</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $uploads_data['pending']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Users by Role -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Users by Role</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie mb-4">
                        <canvas id="userRolesChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <?php foreach ($users_by_role as $role_data): ?>
                            <span class="mr-2">
                                <i class="fas fa-circle"
                                    style="color: <?php echo getRandomColor($role_data['role']); ?>"></i>
                                <?php echo ucfirst($role_data['role']); ?>: <?php echo $role_data['count']; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resources by Category -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Resources by Category</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie mb-4">
                        <canvas id="categoriesChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <?php foreach ($categories as $category): ?>
                            <span class="mr-2">
                                <i class="fas fa-circle"
                                    style="color: <?php echo getRandomColor($category['category']); ?>"></i>
                                <?php echo $category['category']; ?>: <?php echo $category['count']; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Status -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">Upload Status</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie mb-4">
                        <canvas id="uploadsStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Approved:
                            <?php echo $uploads_data['approved']; ?>
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Pending: <?php echo $uploads_data['pending']; ?>
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Rejected: <?php echo $uploads_data['rejected']; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Most Popular Subjects -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Most Popular Subjects</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Resources</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_subject_resources = 0;
                                foreach ($subjects as $subject) {
                                    $total_subject_resources += $subject['count'];
                                }

                                foreach ($subjects as $subject):
                                    $percentage = ($total_subject_resources > 0) ? round(($subject['count'] / $total_subject_resources) * 100) : 0;
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($subject['subject']); ?></td>
                                        <td><?php echo $subject['count']; ?></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: <?php echo $percentage; ?>%"
                                                    aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    <?php echo $percentage; ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Contributors -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Top Contributors</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>Uploads</th>
                                    <th>XP Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($contributor = $contributors_result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <a href="user_profile.php?id=<?php echo $contributor['id']; ?>"
                                                class="text-decoration-none">
                                                <?php echo htmlspecialchars($contributor['name']); ?>
                                            </a>
                                        </td>
                                        <td><?php echo ucfirst(htmlspecialchars($contributor['role'])); ?></td>
                                        <td><?php echo $contributor['upload_count']; ?></td>
                                        <td><?php echo $contributor['xp_points']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Uploads -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Recent Uploads</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Uploader</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($upload = $recent_uploads_result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <a href="view_file.php?id=<?php echo $upload['id']; ?>"
                                                class="text-decoration-none">
                                                <?php echo htmlspecialchars($upload['title']); ?>
                                            </a>
                                        </td>
                                        <td><?php echo htmlspecialchars($upload['uploader_name']); ?></td>
                                        <td><?php echo htmlspecialchars($upload['category']); ?></td>
                                        <td>
                                            <?php if ($upload['is_approved'] == 1): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php elseif ($upload['is_approved'] == 2): ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php while ($activity = $recent_activity_result->fetch_assoc()): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($activity['description']); ?></h6>
                                    <small><?php echo date('M d, g:i a', strtotime($activity['created_at'])); ?></small>
                                </div>
                                <p class="mb-1">
                                    <small>
                                        <strong><?php echo htmlspecialchars($activity['user_name']); ?></strong> earned
                                        <span class="badge bg-primary"><?php echo $activity['xp_earned']; ?> XP</span> for
                                        <?php echo $activity['activity_type']; ?>
                                    </small>
                                </p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Function to generate random colors based on seed
    function getRandomColor(seed) {
        let hash = 0;
        for (let i = 0; i < seed.length; i++) {
            hash = seed.charCodeAt(i) + ((hash << 5) - hash);
        }
        let color = '#';
        for (let i = 0; i < 3; i++) {
            const value = (hash >> (i * 8)) & 0xFF;
            color += ('00' + value.toString(16)).substr(-2);
        }
        return color;
    }

    // Users by Role Chart
    const userRolesCtx = document.getElementById('userRolesChart').getContext('2d');
    const userRolesChart = new Chart(userRolesCtx, {
        type: 'doughnut',
        data: {
            labels: [
                <?php foreach ($users_by_role as $role): ?>
                            '<?php echo ucfirst($role['role']); ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                data: [
                    <?php foreach ($users_by_role as $role): ?>
                                <?php echo $role['count']; ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: [
                    <?php foreach ($users_by_role as $role): ?>
                                '<?php echo getRandomColor($role['role']); ?>',
                    <?php endforeach; ?>
                ],
                hoverBackgroundColor: [
                    <?php foreach ($users_by_role as $role): ?>
                                '<?php echo getRandomColor($role['role']); ?>',
                    <?php endforeach; ?>
                ],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });

    // Categories Chart
    const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    const categoriesChart = new Chart(categoriesCtx, {
        type: 'doughnut',
        data: {
            labels: [
                <?php foreach ($categories as $category): ?>
                            '<?php echo $category['category']; ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                data: [
                    <?php foreach ($categories as $category): ?>
                                <?php echo $category['count']; ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: [
                    <?php foreach ($categories as $category): ?>
                                '<?php echo getRandomColor($category['category']); ?>',
                    <?php endforeach; ?>
                ],
                hoverBackgroundColor: [
                    <?php foreach ($categories as $category): ?>
                                '<?php echo getRandomColor($category['category']); ?>',
                    <?php endforeach; ?>
                ],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });

    // Uploads Status Chart
    const uploadsStatusCtx = document.getElementById('uploadsStatusChart').getContext('2d');
    const uploadsStatusChart = new Chart(uploadsStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{
                data: [
                    <?php echo $uploads_data['approved']; ?>,
                    <?php echo $uploads_data['pending']; ?>,
                    <?php echo $uploads_data['rejected']; ?>
                ],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#17a673', '#dda20a', '#be2617'],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
</script>

<?php
// Helper function to generate random color based on seed
function getRandomColor($seed)
{
    $hash = 0;
    for ($i = 0; $i < strlen($seed); $i++) {
        $hash = ord($seed[$i]) + (($hash << 5) - $hash);
    }
    $color = '#';
    for ($i = 0; $i < 3; $i++) {
        $value = ($hash >> ($i * 8)) & 0xFF;
        $color .= str_pad(dechex($value), 2, "0", STR_PAD_LEFT);
    }
    return $color;
}

include 'footer.php';
?>