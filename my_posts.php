<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Set page title and include header
$page_title = 'My Posts - VidyaSathi';
include 'header.php';

// Include database connection
require_once 'config.php';

// Get user ID
$user_id = $_SESSION['user_id'];

// Check if posts table exists
$check_table = $conn->query("SHOW TABLES LIKE 'posts'");
$has_posts_table = ($check_table->num_rows > 0);

// Get all user's posts
$posts = [];
if ($has_posts_table) {
    $query = "SELECT p.*, u.name as author_name 
              FROM posts p 
              JOIN users u ON p.user_id = u.id 
              WHERE p.user_id = ? 
              ORDER BY p.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<div class="py-6"></div>

<div class="container mx-auto px-4 my-8">
    <div class="max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">My Posts</h1>
                <p class="text-gray-400 mt-1">View and manage all your posts</p>
            </div>
            <div class="flex space-x-4">
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                </a>
                <a href="new_post.php" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Create New Post
                </a>
            </div>
        </div>

        <!-- Posts List -->
        <?php if (count($posts) > 0): ?>
            <div class="grid grid-cols-1 gap-6">
                <?php foreach ($posts as $post): ?>
                    <div
                        class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 hover:border-primary transition-all">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h2 class="text-2xl font-bold mb-2">
                                    <a href="view_post.php?id=<?php echo $post['id']; ?>"
                                        class="hover:text-primary transition-all">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h2>
                                <div class="text-sm text-gray-400">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                                </div>
                            </div>

                            <div class="text-gray-300 my-4">
                                <?php
                                // Show content preview (first 200 characters)
                                $content_preview = htmlspecialchars(substr($post['content'], 0, 200));
                                echo $content_preview;
                                if (strlen($post['content']) > 200)
                                    echo '...';
                                ?>
                            </div>

                            <?php if ($post['has_file']): ?>
                                <div class="bg-dark rounded p-2 inline-block mb-3">
                                    <i class="fas <?php
                                    // Choose icon based on file type
                                    if (strpos($post['file_type'], 'image/') === 0) {
                                        echo 'fa-image text-blue-400';
                                    } elseif ($post['file_type'] === 'application/pdf') {
                                        echo 'fa-file-pdf text-red-400';
                                    } elseif (
                                        strpos($post['file_type'], 'application/vnd.ms-excel') === 0 ||
                                        strpos($post['file_type'], 'application/vnd.openxmlformats-officedocument.spreadsheetml') === 0
                                    ) {
                                        echo 'fa-file-excel text-green-400';
                                    } elseif (
                                        strpos($post['file_type'], 'application/msword') === 0 ||
                                        strpos($post['file_type'], 'application/vnd.openxmlformats-officedocument.wordprocessingml') === 0
                                    ) {
                                        echo 'fa-file-word text-blue-400';
                                    } else {
                                        echo 'fa-file text-gray-400';
                                    }
                                    ?> mr-1"></i>
                                    <?php echo htmlspecialchars($post['file_name']); ?>
                                </div>
                            <?php endif; ?>

                            <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-800">
                                <a href="view_post.php?id=<?php echo $post['id']; ?>" class="text-primary hover:underline">
                                    Read More <i class="fas fa-arrow-right ml-1"></i>
                                </a>

                                <div class="flex space-x-3">
                                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="text-primary hover:underline">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="text-red-500 hover:underline"
                                        onclick="return confirm('Are you sure you want to delete this post?');">
                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 p-8 text-center">
                <i class="fas fa-file-alt text-5xl text-gray-600 mb-4"></i>
                <h2 class="text-2xl font-bold mb-2">No Posts Yet</h2>
                <p class="text-gray-400 mb-6">You haven't created any posts yet. Share your thoughts with the community!</p>
                <a href="new_post.php" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Create Your First Post
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<link rel="stylesheet" href="css/style.css">