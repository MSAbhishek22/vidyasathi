<?php
session_start();
require_once 'config.php';

// Check if user ID is provided in the URL
if (isset($_GET['id'])) {
    $profile_id = intval($_GET['id']);
} elseif (isset($_SESSION['user_id'])) {
    // Default to current user's profile if no ID is provided
    $profile_id = $_SESSION['user_id'];
} else {
    // Redirect to login if not logged in and no ID provided
    header('Location: login.php');
    exit();
}

// Get user information
$stmt = $conn->prepare("SELECT id, name, email, role, bio, headline, skills, profile_image, 
                       linkedin, github, discord, instagram, twitter,
                       branch, semester, xp_points, is_mentor, created_at 
                       FROM users WHERE id = ?");
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // User not found
    header('Location: index.php');
    exit();
}

$user = $result->fetch_assoc();

// Check if profile is viewable
$is_owner = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profile_id;
$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
$is_moderator = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'moderator';

// Get user's uploads (only approved ones unless viewing own profile)
if ($is_owner || $is_admin || $is_moderator) {
    $uploads_query = "SELECT id, title, subject, category, semester, file_path, is_approved, 
                     upload_date, download_count, 
                     (SELECT COUNT(*) FROM ratings WHERE file_id = uploads.id) as rating_count,
                     (SELECT AVG(rating) FROM ratings WHERE file_id = uploads.id) as avg_rating
                     FROM uploads 
                     WHERE uploader_id = ? 
                     ORDER BY upload_date DESC";
} else {
    $uploads_query = "SELECT id, title, subject, category, semester, file_path, is_approved, 
                     upload_date, download_count,
                     (SELECT COUNT(*) FROM ratings WHERE file_id = uploads.id) as rating_count,
                     (SELECT AVG(rating) FROM ratings WHERE file_id = uploads.id) as avg_rating 
                     FROM uploads 
                     WHERE uploader_id = ? AND is_approved = 1
                     ORDER BY upload_date DESC";
}

$uploads_stmt = $conn->prepare($uploads_query);
$uploads_stmt->bind_param("i", $profile_id);
$uploads_stmt->execute();
$uploads_result = $uploads_stmt->get_result();

// Get user's XP activity
$xp_query = "SELECT activity_type, xp_earned, description, created_at 
             FROM xp_activities 
             WHERE user_id = ? 
             ORDER BY created_at DESC 
             LIMIT 10";
$xp_stmt = $conn->prepare($xp_query);
$xp_stmt->bind_param("i", $profile_id);
$xp_stmt->execute();
$xp_result = $xp_stmt->get_result();

// Calculate statistics
$total_uploads = $uploads_result->num_rows;
$approved_uploads = 0;
$total_downloads = 0;
$total_ratings = 0;

// Reset the result pointer
$uploads_result->data_seek(0);
while ($upload = $uploads_result->fetch_assoc()) {
    if ($upload['is_approved'] == 1) {
        $approved_uploads++;
    }
    $total_downloads += $upload['download_count'];
    $total_ratings += $upload['rating_count'];
}
// Reset the result pointer again for later use
$uploads_result->data_seek(0);

// Set page title and include header
$page_title = "Profile: " . htmlspecialchars($user['name']);
include 'header.php';
?>

<!-- Profile Header -->
<div class="bg-gradient-to-r from-primary to-secondary py-8 mb-4">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-white">
            <?php echo htmlspecialchars($user['name']); ?>'s Profile
        </h1>
        <?php if (!empty($user['headline'])): ?>
            <p class="text-white text-lg opacity-90"><?php echo htmlspecialchars($user['headline']); ?></p>
        <?php else: ?>
            <p class="text-light">Explore profile information and contributions</p>
        <?php endif; ?>
    </div>
</div>

<div class="container mx-auto px-4 my-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Information -->
        <div class="lg:col-span-1">
            <div class="card shadow-lg mb-6 border border-gray-800 hover:border-primary transition-all">
                <div class="card-body text-center p-6">
                    <div class="mb-6">
                        <?php if (!empty($user['profile_image']) && file_exists($user['profile_image'])): ?>
                            <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile"
                                class="rounded-full img-thumbnail mx-auto shadow-lg border-4 border-primary"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-full bg-primary text-white d-flex align-items-center justify-content-center mx-auto shadow-lg"
                                style="width: 150px; height: 150px; font-size: 3.5rem; line-height: 150px;">
                                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($user['name']); ?></h2>

                    <div class="mb-4">
                        <span
                            class="badge bg-primary text-white px-3 py-1 rounded-full text-sm font-semibold"><?php echo ucfirst(htmlspecialchars($user['role'])); ?></span>
                        <?php if ($user['is_mentor']): ?>
                            <span
                                class="badge bg-success text-white px-3 py-1 rounded-full text-sm font-semibold ml-2">Mentor</span>
                        <?php endif; ?>
                    </div>

                    <div class="text-gray-400 mb-6">
                        <?php if (!empty($user['branch'])): ?>
                            <div class="mb-1"><?php echo htmlspecialchars($user['branch']); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($user['semester'])): ?>
                            <div>Semester <?php echo htmlspecialchars($user['semester']); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Social Media Links -->
                    <?php if (
                        !empty($user['linkedin']) || !empty($user['github']) || !empty($user['discord']) ||
                        !empty($user['instagram']) || !empty($user['twitter'])
                    ): ?>
                        <div class="flex justify-center space-x-3 mb-6">
                            <?php if (!empty($user['linkedin'])): ?>
                                <a href="<?php echo htmlspecialchars($user['linkedin']); ?>" target="_blank"
                                    class="text-blue-500 hover:text-blue-400 transition-colors" title="LinkedIn">
                                    <i class="fab fa-linkedin fa-lg"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($user['github'])): ?>
                                <a href="<?php echo htmlspecialchars($user['github']); ?>" target="_blank"
                                    class="text-gray-400 hover:text-white transition-colors" title="GitHub">
                                    <i class="fab fa-github fa-lg"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($user['discord'])): ?>
                                <a href="#" onclick="alert('Discord: <?php echo htmlspecialchars($user['discord']); ?>')"
                                    class="text-indigo-400 hover:text-indigo-300 transition-colors"
                                    title="Discord: <?php echo htmlspecialchars($user['discord']); ?>">
                                    <i class="fab fa-discord fa-lg"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($user['instagram'])): ?>
                                <a href="https://instagram.com/<?php echo htmlspecialchars($user['instagram']); ?>"
                                    target="_blank" class="text-pink-500 hover:text-pink-400 transition-colors"
                                    title="Instagram">
                                    <i class="fab fa-instagram fa-lg"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($user['twitter'])): ?>
                                <a href="https://twitter.com/<?php echo htmlspecialchars($user['twitter']); ?>" target="_blank"
                                    class="text-blue-400 hover:text-blue-300 transition-colors" title="Twitter/X">
                                    <i class="fab fa-twitter fa-lg"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-3 gap-2 mb-6 border-t border-b border-gray-800 py-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-primary"><?php echo $user['xp_points']; ?></div>
                            <div class="text-xs text-gray-400">XP Points</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-primary"><?php echo $approved_uploads; ?></div>
                            <div class="text-xs text-gray-400">Uploads</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-primary"><?php echo $total_downloads; ?></div>
                            <div class="text-xs text-gray-400">Downloads</div>
                        </div>
                    </div>

                    <div class="text-sm text-gray-400 mb-6">
                        <i class="fas fa-calendar-alt mr-2"></i> Member since
                        <?php echo date('M Y', strtotime($user['created_at'])); ?>
                    </div>

                    <?php if ($is_owner): ?>
                        <a href="edit_profile.php" class="btn btn-primary w-full py-3">
                            <i class="fas fa-edit mr-2"></i> Edit Profile
                        </a>
                    <?php elseif (!$is_owner && isset($_SESSION['user_id'])): ?>
                        <div class="flex flex-col space-y-3">
                        <?php if ($user['is_mentor']): ?>
                            <a href="request_mentorship.php?mentor_id=<?php echo $user['id']; ?>"
                                    class="btn btn-success w-full py-3">
                                    <i class="fas fa-user-graduate mr-2"></i> Request Mentorship
                                </a>
                            <?php endif; ?>
                            <a href="send_message.php?to=<?php echo $user['id']; ?>" class="btn btn-primary w-full py-3">
                                <i class="fas fa-envelope mr-2"></i> Send Message
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Skills Section (if available) -->
            <?php if (!empty($user['skills'])): ?>
                <div class="card shadow-lg mb-6 border border-gray-800">
                    <div class="card-header bg-dark p-4 border-b border-gray-800">
                        <h3 class="text-xl font-bold mb-0">Skills</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="flex flex-wrap gap-2">
                            <?php
                            $skills = explode(',', $user['skills']);
                            foreach ($skills as $skill):
                                $skill = trim($skill);
                                if (!empty($skill)):
                                    ?>
                                    <span class="bg-dark-accent text-primary px-3 py-1 rounded-full text-sm">
                                        <?php echo htmlspecialchars($skill); ?>
                                    </span>
                                <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- About Section (if bio is available) -->
            <?php if (!empty($user['bio'])): ?>
                <div class="card shadow-lg mb-6 border border-gray-800">
                    <div class="card-header bg-dark p-4 border-b border-gray-800">
                        <h3 class="text-xl font-bold mb-0">About</h3>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-gray-300"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- User Content - Right Side -->
        <div class="lg:col-span-2">
            <!-- Stats Card -->
            <div class="card shadow-lg mb-6 border border-gray-800">
                <div class="card-header bg-dark p-4 border-b border-gray-800">
                    <h3 class="text-xl font-bold mb-0">Activity Stats</h3>
                </div>
                <div class="card-body p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div
                            class="bg-dark rounded-lg p-4 text-center border border-gray-800 hover:border-primary transition-all">
                            <div class="text-3xl font-bold text-primary mb-2"><?php echo $total_uploads; ?></div>
                            <div class="text-gray-400">Total Uploads</div>
                        </div>
                        <div
                            class="bg-dark rounded-lg p-4 text-center border border-gray-800 hover:border-primary transition-all">
                            <div class="text-3xl font-bold text-primary mb-2"><?php echo $total_downloads; ?></div>
                            <div class="text-gray-400">Total Downloads</div>
                        </div>
                        <div
                            class="bg-dark rounded-lg p-4 text-center border border-gray-800 hover:border-primary transition-all">
                            <div class="text-3xl font-bold text-primary mb-2"><?php echo $total_ratings; ?></div>
                            <div class="text-gray-400">Ratings Received</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs for different sections -->
            <div class="mb-6">
                <div class="border-b border-gray-800 flex overflow-x-auto">
                    <button
                        class="px-4 py-2 font-medium text-sm focus:outline-none border-b-2 border-primary text-primary"
                        onclick="switchTab('uploads')">
                        Uploaded Resources
                    </button>
                    <button class="px-4 py-2 font-medium text-sm focus:outline-none hover:text-primary"
                        onclick="switchTab('activities')">
                        Recent Activities
                    </button>
                </div>
            </div>

            <!-- Uploads Tab Content -->
            <div id="uploads-tab" class="card shadow-lg border border-gray-800">
                <div class="card-header bg-dark p-4 border-b border-gray-800">
                    <h3 class="text-xl font-bold mb-0"><?php echo htmlspecialchars($user['name']); ?>'s Uploads</h3>
                </div>

                <?php if ($uploads_result->num_rows > 0): ?>
                    <div class="card-body p-0">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-dark">
                                    <tr class="border-b border-gray-800">
                                        <th class="px-4 py-3 text-left">Title</th>
                                        <th class="px-4 py-3 text-left">Subject</th>
                                        <th class="px-4 py-3 text-left">Category</th>
                                        <th class="px-4 py-3 text-center">Downloads</th>
                                        <th class="px-4 py-3 text-center">Rating</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($upload = $uploads_result->fetch_assoc()): ?>
                                        <tr class="border-b border-gray-800 hover:bg-dark transition-all">
                                            <td class="px-4 py-3">
                                                <a href="view_file.php?id=<?php echo $upload['id']; ?>"
                                                    class="text-primary hover:underline">
                                                    <?php echo htmlspecialchars($upload['title']); ?>
                                                </a>
                                            </td>
                                            <td class="px-4 py-3"><?php echo htmlspecialchars($upload['subject']); ?></td>
                                            <td class="px-4 py-3"><?php echo htmlspecialchars($upload['category']); ?></td>
                                            <td class="px-4 py-3 text-center"><?php echo $upload['download_count']; ?></td>
                                            <td class="px-4 py-3 text-center">
                                                <?php
                                                $rating = round($upload['avg_rating'], 1);
                                                echo $rating > 0 ? $rating . '/5' : 'N/A';
                                                ?>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <a href="view_file.php?id=<?php echo $upload['id']; ?>"
                                                    class="text-primary hover:text-primary-hover">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card-body p-6 text-center">
                        <p class="text-gray-400">No uploads found.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Activities Tab Content (Hidden by default) -->
            <div id="activities-tab" class="card shadow-lg border border-gray-800" style="display: none;">
                <div class="card-header bg-dark p-4 border-b border-gray-800">
                    <h3 class="text-xl font-bold mb-0">Recent Activities</h3>
                </div>

                <?php if ($xp_result->num_rows > 0): ?>
                    <div class="card-body p-0">
                        <div class="divide-y divide-gray-800">
                                        <?php
                            // Reset the result pointer
                            $xp_result->data_seek(0);
                            while ($activity = $xp_result->fetch_assoc()):
                                        $icon = '';
                                        switch ($activity['activity_type']) {
                                            case 'upload':
                                                $icon = 'fa-upload text-primary';
                                                break;
                                            case 'download':
                                                $icon = 'fa-download text-success';
                                                break;
                                            case 'rating':
                                                $icon = 'fa-star text-warning';
                                                break;
                                            case 'mentorship':
                                                $icon = 'fa-user-graduate text-info';
                                                break;
                                            default:
                                        $icon = 'fa-check-circle text-primary';
                                }
                                ?>
                                <div class="p-4 hover:bg-dark transition-all">
                                    <div class="flex items-start">
                                        <div class="mr-4">
                                            <span class="flex items-center justify-center bg-dark rounded-full p-3">
                                                <i class="fas <?php echo $icon; ?> text-2xl"></i>
                                            </span>
                                    </div>
                                        <div class="flex-1">
                                            <p class="text-gray-300"><?php echo htmlspecialchars($activity['description']); ?>
                                            </p>
                                            <p class="text-sm text-gray-400 mt-1">
                                                <i class="fas fa-clock mr-1"></i>
                                    <?php echo date('M d, Y', strtotime($activity['created_at'])); ?>
                                            </p>
                                            <div class="mt-2 inline-block bg-dark text-primary text-xs px-2 py-1 rounded-full">
                                                <i class="fas fa-plus mr-1"></i>
                                                <?php echo $activity['xp_earned']; ?> XP
                                            </div>
                                    </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card-body p-6 text-center">
                        <p class="text-gray-400">No recent activities found.</p>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // Hide all tabs
        document.getElementById('uploads-tab').style.display = 'none';
        document.getElementById('activities-tab').style.display = 'none';

        // Show selected tab
        document.getElementById(tabName + '-tab').style.display = 'block';

        // Update tab button styles
        const tabButtons = document.querySelectorAll('.border-b button');
        tabButtons.forEach(button => {
            button.classList.remove('border-b-2', 'border-primary', 'text-primary');
            if (button.textContent.toLowerCase().includes(tabName)) {
                button.classList.add('border-b-2', 'border-primary', 'text-primary');
            }
        });
    }
</script>

<?php include 'footer.php'; ?>