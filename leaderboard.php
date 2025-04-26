<?php
session_start();
require_once 'config.php';

// Number of users to display per category
$limit = 10;

// Get top contributors by XP points
$top_contributors_query = "SELECT id, name, role, branch, semester, xp_points, profile_image, is_mentor,
                         (SELECT COUNT(*) FROM uploads WHERE uploader_id = users.id AND is_approved = 1) as uploads_count
                         FROM users 
                         ORDER BY xp_points DESC, uploads_count DESC
                         LIMIT ?";
$stmt = $conn->prepare($top_contributors_query);
$stmt->bind_param("i", $limit);
$stmt->execute();
$top_contributors_result = $stmt->get_result();

// Get top uploaders
$top_uploaders_query = "SELECT u.id, u.name, u.role, u.branch, u.semester, u.xp_points, u.profile_image, u.is_mentor,
                        COUNT(up.id) as uploads_count
                        FROM users u
                        INNER JOIN uploads up ON u.id = up.uploader_id
                        WHERE up.is_approved = 1
                        GROUP BY u.id
                        ORDER BY uploads_count DESC, u.xp_points DESC
                        LIMIT ?";
$stmt = $conn->prepare($top_uploaders_query);
$stmt->bind_param("i", $limit);
$stmt->execute();
$top_uploaders_result = $stmt->get_result();

// Get top mentors
$top_mentors_query = "SELECT u.id, u.name, u.role, u.branch, u.semester, u.xp_points, u.profile_image, 
                     COUNT(mr.id) as mentorships_count
                     FROM users u
                     INNER JOIN mentorship_requests mr ON u.id = mr.mentor_id
                     WHERE mr.status = 'accepted'
                     GROUP BY u.id
                     ORDER BY mentorships_count DESC, u.xp_points DESC
                     LIMIT ?";
$stmt = $conn->prepare($top_mentors_query);
$stmt->bind_param("i", $limit);
$stmt->execute();
$top_mentors_result = $stmt->get_result();

// Calculate user's rank if logged in
$user_rank = null;
$user_percentile = null;
if (isset($_SESSION['user_id'])) {
    $rank_query = "SELECT count(*) as better_than_me FROM users WHERE xp_points > (SELECT xp_points FROM users WHERE id = ?)";
    $stmt = $conn->prepare($rank_query);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $rank_data = $result->fetch_assoc();
    $user_rank = $rank_data['better_than_me'] + 1;

    // Get total users for percentile calculation
    $total_users_query = "SELECT COUNT(*) as total FROM users";
    $total_result = $conn->query($total_users_query);
    $total_data = $total_result->fetch_assoc();
    $total_users = $total_data['total'];

    // Calculate percentile
    if ($total_users > 0) {
        $user_percentile = 100 - (($user_rank / $total_users) * 100);
    }
}

// Get top contributors this month
$current_month = date('Y-m-01');
$top_monthly_query = "SELECT u.id, u.name, u.role, u.branch, u.profile_image,
                     SUM(x.xp_earned) as monthly_xp
                     FROM users u
                     INNER JOIN xp_activities x ON u.id = x.user_id
                     WHERE x.created_at >= ?
                     GROUP BY u.id
                     ORDER BY monthly_xp DESC
                     LIMIT ?";
$stmt = $conn->prepare($top_monthly_query);
$stmt->bind_param("si", $current_month, $limit);
$stmt->execute();
$top_monthly_result = $stmt->get_result();

// Set page title and include header
$page_title = "Leaderboard";
include 'header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Leaderboard</h1>
            <p class="text-muted">Recognizing top contributors in our community</p>
        </div>
    </div>

    <?php if (isset($_SESSION['user_id']) && isset($user_rank)): ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm bg-light">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-auto">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <?php if (isset($_SESSION['profile_image']) && file_exists($_SESSION['profile_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="Profile"
                                                class="rounded-circle" style="width: 54px; height: 54px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                style="width: 54px; height: 54px; font-size: 1.2rem;">
                                                <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-0">Your Ranking</h5>
                                        <div class="text-muted">How you compare to others</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="row text-center my-3 my-md-0">
                                    <div class="col-4">
                                        <div class="h2 mb-0 fw-bold"><?php echo $user_rank; ?></div>
                                        <div class="small text-muted">Rank</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="h2 mb-0 fw-bold"><?php echo $_SESSION['xp_points'] ?? 0; ?></div>
                                        <div class="small text-muted">XP Points</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="h2 mb-0 fw-bold"><?php echo round($user_percentile); ?>%</div>
                                        <div class="small text-muted">Percentile</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="leaderboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="top-contributors-tab" data-bs-toggle="tab"
                        data-bs-target="#top-contributors" type="button" role="tab" aria-controls="top-contributors"
                        aria-selected="true">
                        Top Contributors
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="top-uploaders-tab" data-bs-toggle="tab" data-bs-target="#top-uploaders"
                        type="button" role="tab" aria-controls="top-uploaders" aria-selected="false">
                        Top Uploaders
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="top-mentors-tab" data-bs-toggle="tab" data-bs-target="#top-mentors"
                        type="button" role="tab" aria-controls="top-mentors" aria-selected="false">
                        Top Mentors
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="monthly-leaders-tab" data-bs-toggle="tab"
                        data-bs-target="#monthly-leaders" type="button" role="tab" aria-controls="monthly-leaders"
                        aria-selected="false">
                        This Month
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="leaderboardTabContent">
        <!-- Top Contributors Tab -->
        <div class="tab-pane fade show active" id="top-contributors" role="tabpanel"
            aria-labelledby="top-contributors-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Top Contributors by XP</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80">Rank</th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Branch</th>
                                        <th class="text-center">Uploads</th>
                                        <th class="text-end">XP Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $rank = 1; ?>
                                    <?php while ($user = $top_contributors_result->fetch_assoc()): ?>
                                        <tr <?php echo (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id']) ? 'class="table-primary"' : ''; ?>>
                                            <td>
                                                <?php
                                                if ($rank <= 3) {
                                                    $badge_color = ['text-warning', 'text-secondary', 'text-danger'][$rank - 1];
                                                    echo '<i class="fas fa-trophy ' . $badge_color . ' fa-lg"></i>';
                                                } else {
                                                    echo $rank;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <?php if (!empty($user['profile_image']) && file_exists($user['profile_image'])): ?>
                                                            <img src="<?php echo htmlspecialchars($user['profile_image']); ?>"
                                                                alt="Profile" class="rounded-circle"
                                                                style="width: 42px; height: 42px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                                style="width: 42px; height: 42px; font-size: 1rem;">
                                                                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">
                                                            <a href="user_profile.php?id=<?php echo $user['id']; ?>"
                                                                class="text-decoration-none text-body">
                                                                <?php echo htmlspecialchars($user['name']); ?>
                                                            </a>
                                                        </h6>
                                                        <?php if ($user['is_mentor']): ?>
                                                            <span class="badge bg-success">Mentor</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo ucfirst(htmlspecialchars($user['role'])); ?></td>
                                            <td><?php echo htmlspecialchars($user['branch'] ?? 'N/A'); ?></td>
                                            <td class="text-center"><?php echo $user['uploads_count']; ?></td>
                                            <td class="text-end fw-bold"><?php echo $user['xp_points']; ?> XP</td>
                                        </tr>
                                        <?php $rank++; ?>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Uploaders Tab -->
        <div class="tab-pane fade" id="top-uploaders" role="tabpanel" aria-labelledby="top-uploaders-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Top Resource Contributors</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80">Rank</th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Branch</th>
                                        <th class="text-center">Resources</th>
                                        <th class="text-end">XP Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $rank = 1; ?>
                                    <?php while ($user = $top_uploaders_result->fetch_assoc()): ?>
                                        <tr <?php echo (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id']) ? 'class="table-primary"' : ''; ?>>
                                            <td>
                                                <?php
                                                if ($rank <= 3) {
                                                    $badge_color = ['text-warning', 'text-secondary', 'text-danger'][$rank - 1];
                                                    echo '<i class="fas fa-trophy ' . $badge_color . ' fa-lg"></i>';
                                                } else {
                                                    echo $rank;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <?php if (!empty($user['profile_image']) && file_exists($user['profile_image'])): ?>
                                                            <img src="<?php echo htmlspecialchars($user['profile_image']); ?>"
                                                                alt="Profile" class="rounded-circle"
                                                                style="width: 42px; height: 42px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                                style="width: 42px; height: 42px; font-size: 1rem;">
                                                                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">
                                                            <a href="user_profile.php?id=<?php echo $user['id']; ?>"
                                                                class="text-decoration-none text-body">
                                                                <?php echo htmlspecialchars($user['name']); ?>
                                                            </a>
                                                        </h6>
                                                        <?php if ($user['is_mentor']): ?>
                                                            <span class="badge bg-success">Mentor</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo ucfirst(htmlspecialchars($user['role'])); ?></td>
                                            <td><?php echo htmlspecialchars($user['branch'] ?? 'N/A'); ?></td>
                                            <td class="text-center fw-bold"><?php echo $user['uploads_count']; ?></td>
                                            <td class="text-end"><?php echo $user['xp_points']; ?> XP</td>
                                        </tr>
                                        <?php $rank++; ?>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Mentors Tab -->
        <div class="tab-pane fade" id="top-mentors" role="tabpanel" aria-labelledby="top-mentors-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Top Mentors</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80">Rank</th>
                                        <th>Mentor</th>
                                        <th>Branch</th>
                                        <th>Semester</th>
                                        <th class="text-center">Active Mentorships</th>
                                        <th class="text-end">XP Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($top_mentors_result->num_rows === 0): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-user-graduate fa-2x text-muted mb-3"></i>
                                                <p class="mb-0">No active mentorships yet</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $rank = 1; ?>
                                        <?php while ($user = $top_mentors_result->fetch_assoc()): ?>
                                            <tr <?php echo (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id']) ? 'class="table-primary"' : ''; ?>>
                                                <td>
                                                    <?php
                                                    if ($rank <= 3) {
                                                        $badge_color = ['text-warning', 'text-secondary', 'text-danger'][$rank - 1];
                                                        echo '<i class="fas fa-trophy ' . $badge_color . ' fa-lg"></i>';
                                                    } else {
                                                        echo $rank;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <?php if (!empty($user['profile_image']) && file_exists($user['profile_image'])): ?>
                                                                <img src="<?php echo htmlspecialchars($user['profile_image']); ?>"
                                                                    alt="Profile" class="rounded-circle"
                                                                    style="width: 42px; height: 42px; object-fit: cover;">
                                                            <?php else: ?>
                                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                                    style="width: 42px; height: 42px; font-size: 1rem;">
                                                                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="ms-3">
                                                            <h6 class="mb-0">
                                                                <a href="user_profile.php?id=<?php echo $user['id']; ?>"
                                                                    class="text-decoration-none text-body">
                                                                    <?php echo htmlspecialchars($user['name']); ?>
                                                                </a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($user['branch'] ?? 'N/A'); ?></td>
                                                <td><?php echo $user['semester'] ? 'Semester ' . htmlspecialchars($user['semester']) : 'N/A'; ?>
                                                </td>
                                                <td class="text-center fw-bold"><?php echo $user['mentorships_count']; ?></td>
                                                <td class="text-end"><?php echo $user['xp_points']; ?> XP</td>
                                            </tr>
                                            <?php $rank++; ?>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Leaders Tab -->
        <div class="tab-pane fade" id="monthly-leaders" role="tabpanel" aria-labelledby="monthly-leaders-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Top Contributors This Month</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80">Rank</th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Branch</th>
                                        <th class="text-end">XP This Month</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($top_monthly_result->num_rows === 0): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-chart-line fa-2x text-muted mb-3"></i>
                                                <p class="mb-0">No XP earned this month yet</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $rank = 1; ?>
                                        <?php while ($user = $top_monthly_result->fetch_assoc()): ?>
                                            <tr <?php echo (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id']) ? 'class="table-primary"' : ''; ?>>
                                                <td>
                                                    <?php
                                                    if ($rank <= 3) {
                                                        $badge_color = ['text-warning', 'text-secondary', 'text-danger'][$rank - 1];
                                                        echo '<i class="fas fa-trophy ' . $badge_color . ' fa-lg"></i>';
                                                    } else {
                                                        echo $rank;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <?php if (!empty($user['profile_image']) && file_exists($user['profile_image'])): ?>
                                                                <img src="<?php echo htmlspecialchars($user['profile_image']); ?>"
                                                                    alt="Profile" class="rounded-circle"
                                                                    style="width: 42px; height: 42px; object-fit: cover;">
                                                            <?php else: ?>
                                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                                    style="width: 42px; height: 42px; font-size: 1rem;">
                                                                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="ms-3">
                                                            <h6 class="mb-0">
                                                                <a href="user_profile.php?id=<?php echo $user['id']; ?>"
                                                                    class="text-decoration-none text-body">
                                                                    <?php echo htmlspecialchars($user['name']); ?>
                                                                </a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo ucfirst(htmlspecialchars($user['role'])); ?></td>
                                                <td><?php echo htmlspecialchars($user['branch'] ?? 'N/A'); ?></td>
                                                <td class="text-end fw-bold"><?php echo $user['monthly_xp']; ?> XP</td>
                                            </tr>
                                            <?php $rank++; ?>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">How to Earn XP Points</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex mb-3">
                        <div class="badge bg-primary rounded-circle p-2 d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-upload fa-lg"></i>
                        </div>
                        <div>
                            <h6>Upload Resources</h6>
                            <p class="text-muted small">Earn 5 XP for each resource you upload that gets approved</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="badge bg-success rounded-circle p-2 d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-star fa-lg"></i>
                        </div>
                        <div>
                            <h6>Rate & Review</h6>
                            <p class="text-muted small">Earn 1 XP for each resource you rate</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="d-flex mb-3">
                        <div class="badge bg-info rounded-circle p-2 d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-download fa-lg"></i>
                        </div>
                        <div>
                            <h6>Download Resources</h6>
                            <p class="text-muted small">Earn 2 XP for each unique resource you download</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="badge bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-user-graduate fa-lg"></i>
                        </div>
                        <div>
                            <h6>Mentorship</h6>
                            <p class="text-muted small">Earn 5 XP for requesting mentorship, 15 XP for mentoring others
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>