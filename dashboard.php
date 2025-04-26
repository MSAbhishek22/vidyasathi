<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Set page title and include header
$page_title = 'Dashboard - VidyaSathi';
include 'header.php';

// Get user data from session
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

// Include database connection
require_once 'config.php';

// Fetch user stats and recent activities
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Check if posts table exists
$check_table = $conn->query("SHOW TABLES LIKE 'posts'");
$has_posts_table = ($check_table->num_rows > 0);

// Get recent posts if the table exists
$has_recent_posts = false;
$recent_posts = [];

if ($has_posts_table) {
    // Get user's posts
    $posts_query = "SELECT p.*, u.name as author_name 
                   FROM posts p 
                   JOIN users u ON p.user_id = u.id 
                   WHERE p.user_id = ? 
                   ORDER BY p.created_at DESC 
                   LIMIT 5";
    $posts_stmt = $conn->prepare($posts_query);
    $posts_stmt->bind_param("i", $user_id);
    $posts_stmt->execute();
    $posts_result = $posts_stmt->get_result();

    if ($posts_result->num_rows > 0) {
        $has_recent_posts = true;
        while ($post = $posts_result->fetch_assoc()) {
            $recent_posts[] = $post;
        }
    }
    $posts_stmt->close();
}

// Get recent community posts
$community_posts = [];
if ($has_posts_table) {
    $community_query = "SELECT p.*, u.name as author_name, u.profile_image
                       FROM posts p 
                       JOIN users u ON p.user_id = u.id 
                       WHERE p.user_id != ? 
                       ORDER BY p.created_at DESC 
                       LIMIT 3";
    $community_stmt = $conn->prepare($community_query);
    $community_stmt->bind_param("i", $user_id);
    $community_stmt->execute();
    $community_result = $community_stmt->get_result();

    if ($community_result->num_rows > 0) {
        while ($post = $community_result->fetch_assoc()) {
            $community_posts[] = $post;
        }
    }
    $community_stmt->close();
}

// Get user engagement stats
$post_count = 0;
$likes_count = 0;
$comments_count = 0;

if ($has_posts_table) {
    // Count user's posts
    $post_count_query = "SELECT COUNT(*) as count FROM posts WHERE user_id = ?";
    $post_count_stmt = $conn->prepare($post_count_query);
    $post_count_stmt->bind_param("i", $user_id);
    $post_count_stmt->execute();
    $post_count = $post_count_stmt->get_result()->fetch_assoc()['count'];
    $post_count_stmt->close();

    // Check if post_likes table exists
    $check_likes_table = $conn->query("SHOW TABLES LIKE 'post_likes'");
    if ($check_likes_table->num_rows > 0) {
        // Count likes received on user's posts
        $likes_query = "SELECT COUNT(*) as count FROM post_likes 
                        JOIN posts ON post_likes.post_id = posts.id 
                        WHERE posts.user_id = ?";
        $likes_stmt = $conn->prepare($likes_query);
        $likes_stmt->bind_param("i", $user_id);
        $likes_stmt->execute();
        $likes_count = $likes_stmt->get_result()->fetch_assoc()['count'];
        $likes_stmt->close();
    }

    // Check if post_comments table exists
    $check_comments_table = $conn->query("SHOW TABLES LIKE 'post_comments'");
    if ($check_comments_table->num_rows > 0) {
        // Count comments on user's posts
        $comments_query = "SELECT COUNT(*) as count FROM post_comments 
                          JOIN posts ON post_comments.post_id = posts.id 
                          WHERE posts.user_id = ?";
        $comments_stmt = $conn->prepare($comments_query);
        $comments_stmt->bind_param("i", $user_id);
        $comments_stmt->execute();
        $comments_count = $comments_stmt->get_result()->fetch_assoc()['count'];
        $comments_stmt->close();
    }
}

$conn->close();
?>

<div class="py-6"></div>

<!-- New Dashboard Main Content -->
<div class="container mx-auto px-4 my-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Sidebar - User Profile Card -->
        <div class="lg:col-span-3">
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 sticky top-24">
                <!-- Profile Header -->
                <div class="bg-gradient-to-r from-primary to-blue-700 p-6 text-white">
                    <div class="flex flex-col items-center">
                        <?php if (!empty($user['profile_image']) && file_exists($user['profile_image'])): ?>
                            <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Photo"
                                class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                        <?php else: ?>
                            <div
                                class="w-24 h-24 rounded-full bg-white flex items-center justify-center text-primary text-4xl font-bold border-4 border-white shadow-lg">
                                <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <h1 class="text-xl font-bold mt-4"><?php echo htmlspecialchars($user_name); ?></h1>
                        <?php if (!empty($user['headline'])): ?>
                            <p class="text-gray-100 text-sm mt-1"><?php echo htmlspecialchars($user['headline']); ?></p>
                        <?php else: ?>
                            <p class="text-gray-100 text-sm mt-1 capitalize"><?php echo htmlspecialchars($user_role); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Profile Stats -->
                <div class="p-4 bg-dark-accent border-b border-gray-800">
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="p-2">
                            <div class="text-xl font-bold text-primary"><?php echo $post_count; ?></div>
                            <div class="text-xs text-gray-400">Posts</div>
                        </div>
                        <div class="p-2">
                            <div class="text-xl font-bold text-red-400"><?php echo $likes_count; ?></div>
                            <div class="text-xs text-gray-400">Likes</div>
                        </div>
                        <div class="p-2">
                            <div class="text-xl font-bold text-blue-400"><?php echo $comments_count; ?></div>
                            <div class="text-xs text-gray-400">Comments</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="p-4 border-b border-gray-800">
                    <h3 class="text-sm font-bold uppercase text-gray-500 mb-3">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="profile.php" class="btn btn-sm btn-primary w-full">
                            <i class="fas fa-user mr-1"></i> View Profile
                        </a>
                        <a href="edit_profile.php" class="btn btn-sm btn-secondary w-full">
                            <i class="fas fa-edit mr-1"></i> Edit Profile
                        </a>
                        <a href="new_post.php" class="btn btn-sm btn-success w-full col-span-2">
                            <i class="fas fa-plus mr-1"></i> Create Post
                        </a>
                            </div>
                        </div>

                <!-- Navigation Menu -->
                <div class="p-4">
                    <h3 class="text-sm font-bold uppercase text-gray-500 mb-3">Menu</h3>
                            <nav class="space-y-2">
                        <a href="dashboard.php"
                            class="flex items-center p-2 rounded-lg bg-primary bg-opacity-20 text-primary">
                            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                            Dashboard
                        </a>
                        <a href="community.php" class="flex items-center p-2 rounded-lg hover:bg-dark">
                            <i class="fas fa-users w-5 h-5 mr-3"></i>
                            Community
                        </a>
                        <a href="index.php#resources" class="flex items-center p-2 rounded-lg hover:bg-dark">
                            <i class="fas fa-book w-5 h-5 mr-3"></i>
                                    Learning Resources
                                </a>
                                <a href="videos.php" class="flex items-center p-2 rounded-lg hover:bg-dark">
                            <i class="fas fa-video w-5 h-5 mr-3"></i>
                                    Video Tutorials
                                </a>
                        <a href="my_posts.php" class="flex items-center p-2 rounded-lg hover:bg-dark">
                            <i class="fas fa-file-alt w-5 h-5 mr-3"></i>
                            My Posts
                                </a>
                                <?php if ($user_role === 'admin' || $user_role === 'moderator'): ?>
                                    <a href="admin/index.php"
                                class="flex items-center p-2 rounded-lg hover:bg-dark text-yellow-500">
                                <i class="fas fa-crown w-5 h-5 mr-3"></i>
                                        Admin Panel
                                    </a>
                                <?php endif; ?>
                            </nav>
                        </div>
                    </div>
        </div>

        <!-- Middle Content - Main Dashboard -->
        <div class="lg:col-span-6">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 text-white mb-6">
                <div class="flex items-center">
                    <div class="mr-4 bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-bolt text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h2>
                        <p class="opacity-90">Continue your learning journey today</p>
                    </div>
                </div>
                <div class="mt-4 flex space-x-3">
                    <a href="new_post.php" class="btn bg-white text-indigo-600 hover:bg-gray-100">
                        <i class="fas fa-plus mr-2"></i> Create Post
                    </a>
                    <a href="community.php" class="btn bg-white bg-opacity-20 text-white hover:bg-opacity-30">
                        <i class="fas fa-users mr-2"></i> Browse Community
                    </a>
                </div>
            </div>

            <!-- Activity Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-card-bg rounded-lg shadow-lg p-5 border border-gray-800">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-600 bg-opacity-20 rounded-full p-2 mr-3">
                            <i class="fas fa-file-alt text-blue-400"></i>
                        </div>
                        <h3 class="font-semibold">My Posts</h3>
                            </div>
                    <div class="text-3xl font-bold"><?php echo $post_count; ?></div>
                    <div class="mt-2 text-sm text-gray-400">
                        <a href="my_posts.php" class="inline-flex items-center text-blue-400 hover:underline">
                            View all <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                            </div>
                            </div>

                <div class="bg-card-bg rounded-lg shadow-lg p-5 border border-gray-800">
                    <div class="flex items-center mb-3">
                        <div class="bg-green-600 bg-opacity-20 rounded-full p-2 mr-3">
                            <i class="fas fa-heart text-green-400"></i>
                        </div>
                        <h3 class="font-semibold">Interactions</h3>
                    </div>
                    <div class="text-3xl font-bold"><?php echo ($likes_count + $comments_count); ?></div>
                    <div class="mt-2 text-sm text-gray-400">
                        <span class="text-gray-400"><?php echo $likes_count; ?> likes, <?php echo $comments_count; ?>
                            comments</span>
                    </div>
                </div>

                <div class="bg-card-bg rounded-lg shadow-lg p-5 border border-gray-800">
                    <div class="flex items-center mb-3">
                        <div class="bg-purple-600 bg-opacity-20 rounded-full p-2 mr-3">
                            <i class="fas fa-users text-purple-400"></i>
                        </div>
                        <h3 class="font-semibold">Community</h3>
                    </div>
                    <div class="text-3xl font-bold"><?php echo count($community_posts); ?>+</div>
                    <div class="mt-2 text-sm text-gray-400">
                        <a href="community.php" class="inline-flex items-center text-purple-400 hover:underline">
                            Join discussions <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- My Recent Activity Section -->
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 mb-6">
                <div class="p-4 bg-dark border-b border-gray-800 flex justify-between items-center">
                    <h2 class="text-lg font-bold">My Recent Posts</h2>
                    <a href="my_posts.php" class="text-primary hover:underline text-sm">
                        View all <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="p-4">
                        <?php if ($has_recent_posts): ?>
                            <div class="space-y-4">
                            <?php foreach ($recent_posts as $post): ?>
                                <div class="bg-dark rounded-lg p-4 border border-gray-800 hover:border-primary transition-all">
                                    <div class="flex justify-between">
                                        <h3 class="font-semibold mb-2">
                                            <a href="view_post.php?id=<?php echo $post['id']; ?>" class="hover:text-primary">
                                                <?php echo htmlspecialchars($post['title']); ?>
                                            </a>
                                        </h3>
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-clock"></i>
                                            <?php echo date('M d', strtotime($post['created_at'])); ?>
                                        </span>
                                    </div>

                                    <div class="text-sm text-gray-400 my-2">
                                        <?php
                                        $content_preview = htmlspecialchars(substr($post['content'], 0, 100));
                                        echo $content_preview;
                                        if (strlen($post['content']) > 100)
                                            echo '...';
                                        ?>
                                    </div>

                                    <div class="flex justify-between items-center mt-3">
                                        <div class="flex space-x-3 text-sm text-gray-500">
                                            <span><i class="far fa-heart"></i> 0</span>
                                            <span><i class="far fa-comment"></i> 0</span>
                                        </div>
                                        <a href="view_post.php?id=<?php echo $post['id']; ?>"
                                            class="text-xs text-primary hover:underline">
                                            Read more
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-dark rounded-lg p-4 text-center">
                            <p class="text-gray-400 mb-3">You haven't created any posts yet</p>
                            <a href="new_post.php" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i> Create Your First Post
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Popular Learning Resources -->
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800">
                <div class="p-4 bg-dark border-b border-gray-800">
                    <h2 class="text-lg font-bold">Recommended Learning Resources</h2>
                </div>

                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="index.php#resources"
                            class="bg-dark rounded-lg p-4 border border-gray-800 hover:border-primary transition-all flex items-center">
                            <div class="bg-blue-600 bg-opacity-20 rounded-full p-3 mr-3">
                                <i class="fas fa-book text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="font-medium">Programming Fundamentals</h3>
                                <p class="text-sm text-gray-400">Learn core programming concepts</p>
                            </div>
                        </a>

                        <a href="index.php#resources"
                            class="bg-dark rounded-lg p-4 border border-gray-800 hover:border-primary transition-all flex items-center">
                            <div class="bg-green-600 bg-opacity-20 rounded-full p-3 mr-3">
                                <i class="fas fa-code text-green-400"></i>
                            </div>
                            <div>
                                <h3 class="font-medium">Data Structures</h3>
                                <p class="text-sm text-gray-400">Master essential data structures</p>
                            </div>
                        </a>

                        <a href="videos.php"
                            class="bg-dark rounded-lg p-4 border border-gray-800 hover:border-primary transition-all flex items-center">
                            <div class="bg-red-600 bg-opacity-20 rounded-full p-3 mr-3">
                                <i class="fas fa-video text-red-400"></i>
                            </div>
                            <div>
                                <h3 class="font-medium">Video Tutorials</h3>
                                <p class="text-sm text-gray-400">Visual learning content</p>
                            </div>
                        </a>

                        <a href="index.php#resources"
                            class="bg-dark rounded-lg p-4 border border-gray-800 hover:border-primary transition-all flex items-center">
                            <div class="bg-yellow-600 bg-opacity-20 rounded-full p-3 mr-3">
                                <i class="fas fa-tasks text-yellow-400"></i>
                            </div>
                            <div>
                                <h3 class="font-medium">Practice Problems</h3>
                                <p class="text-sm text-gray-400">Sharpen your skills with exercises</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Community & Updates -->
        <div class="lg:col-span-3">
            <!-- Community Discussions -->
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 mb-6">
                <div class="p-4 bg-dark border-b border-gray-800 flex justify-between items-center">
                    <h2 class="text-lg font-bold">Community Discussions</h2>
                    <a href="community.php" class="text-primary hover:underline text-sm">
                        View all <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="p-4">
                    <?php if (!empty($community_posts)): ?>
                        <div class="space-y-4">
                            <?php foreach ($community_posts as $post): ?>
                                <div class="bg-dark rounded-lg p-4 border border-gray-800 hover:border-primary transition-all">
                                    <div class="flex items-start mb-3">
                                        <div class="mr-3">
                                            <?php if (!empty($post['profile_image']) && file_exists($post['profile_image'])): ?>
                                                <img src="<?php echo htmlspecialchars($post['profile_image']); ?>" alt="Profile"
                                                    class="rounded-full" style="width: 32px; height: 32px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-full bg-primary text-white flex items-center justify-center"
                                                    style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    <?php echo strtoupper(substr($post['author_name'], 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-sm">
                                                <a href="view_post.php?id=<?php echo $post['id']; ?>"
                                                    class="hover:text-primary">
                                                    <?php echo htmlspecialchars($post['title']); ?>
                                                </a>
                                            </h3>
                                            <div class="text-xs text-gray-500">
                                                by <?php echo htmlspecialchars($post['author_name']); ?> •
                                                <?php echo date('M d', strtotime($post['created_at'])); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="view_post.php?id=<?php echo $post['id']; ?>"
                                        class="text-xs text-primary hover:underline">
                                        Join discussion <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="community.php" class="btn btn-primary btn-sm w-full">
                                <i class="fas fa-users mr-2"></i> Browse All Discussions
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="bg-dark rounded-lg p-4 text-center">
                            <p class="text-gray-400 mb-3">No community posts yet</p>
                            <a href="community.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-users mr-2"></i> Explore Community
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- System Updates & Announcements -->
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 mb-6">
                <div class="p-4 bg-dark border-b border-gray-800">
                    <h2 class="text-lg font-bold">Announcements</h2>
                </div>

                <div class="p-4">
                    <div class="bg-dark-accent rounded-lg p-4 border-l-4 border-blue-500">
                        <h3 class="font-semibold mb-1">New Features Available!</h3>
                        <p class="text-sm text-gray-300 mb-2">Enhanced profiles, community discussions, and more!</p>
                        <div class="text-xs text-gray-400">Posted on Apr 20, 2025</div>
                    </div>

                    <div class="bg-dark rounded-lg p-4 mt-3 border-l-4 border-green-500">
                        <h3 class="font-semibold mb-1">Join as a Mentor</h3>
                        <p class="text-sm text-gray-300 mb-2">Share your knowledge and help others learn.</p>
                        <a href="become_mentor.php" class="text-xs text-primary hover:underline">
                            Learn more <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800">
                <div class="p-4 bg-dark border-b border-gray-800">
                    <h2 class="text-lg font-bold">Quick Links</h2>
                </div>

                <div class="p-4">
                    <nav class="space-y-2">
                        <a href="index.php" class="flex items-center p-2 rounded-lg hover:bg-dark">
                            <i class="fas fa-home w-5 h-5 mr-3 text-primary"></i>
                            Home Page
                        </a>
                        <a href="profile.php" class="flex items-center p-2 rounded-lg hover:bg-dark">
                            <i class="fas fa-user w-5 h-5 mr-3 text-blue-400"></i>
                            My Profile
                        </a>
                        <a href="new_post.php" class="flex items-center p-2 rounded-lg hover:bg-dark">
                            <i class="fas fa-edit w-5 h-5 mr-3 text-green-400"></i>
                            Create New Post
                        </a>
                        <a href="become_mentor.php" class="flex items-center p-2 rounded-lg hover:bg-dark">
                            <i class="fas fa-chalkboard-teacher w-5 h-5 mr-3 text-yellow-400"></i>
                            Become a Mentor
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<link rel="stylesheet" href="css/style.css">