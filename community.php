<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Set page title and include header
$page_title = 'Community - VidyaSathi';
include 'header.php';

// Include database connection
require_once 'config.php';

// Get user info
$user_id = $_SESSION['user_id'];

// Pagination settings
$posts_per_page = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $posts_per_page;

// Filter settings
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Check if posts table exists
$check_table = $conn->query("SHOW TABLES LIKE 'posts'");
$has_posts_table = ($check_table->num_rows > 0);

// Get posts from all users with pagination and filters
$posts = [];
$total_posts = 0;

if ($has_posts_table) {
    // Build query based on filters
    $query_conditions = [];
    $params = [];
    $param_types = "";

    if (!empty($search)) {
        $query_conditions[] = "(p.title LIKE ? OR p.content LIKE ?)";
        $search_param = "%{$search}%";
        $params[] = $search_param;
        $params[] = $search_param;
        $param_types .= "ss";
    }

    // Construct WHERE clause
    $where_clause = !empty($query_conditions) ? "WHERE " . implode(" AND ", $query_conditions) : "";

    // Count total posts for pagination
    $count_query = "SELECT COUNT(*) as total FROM posts p $where_clause";

    if (!empty($params)) {
        $count_stmt = $conn->prepare($count_query);
        $count_stmt->bind_param($param_types, ...$params);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $total_posts = $count_result->fetch_assoc()['total'];
        $count_stmt->close();
    } else {
        $count_result = $conn->query($count_query);
        $total_posts = $count_result->fetch_assoc()['total'];
    }

    // Get total posts count for pagination
    $result = $conn->query("SELECT COUNT(*) AS total FROM posts $where_clause");
    $total_pages = ceil($total_posts / $posts_per_page);

    // Get posts for current page
    $query = "SELECT p.*, u.name as author_name, 
              '' as profile_image, 
              u.role as author_role 
              FROM posts p 
              LEFT JOIN users u ON p.user_id = u.id 
              $where_clause 
              ORDER BY p.created_at DESC 
              LIMIT $offset, $posts_per_page";
    $result = $conn->query($query);
    $posts = $result->fetch_all(MYSQLI_ASSOC);

    // Get likes count for each post
    $likes_count = [];

    // Check if post_likes table exists
    $table_check = $conn->query("SHOW TABLES LIKE 'post_likes'");
    if ($table_check->num_rows > 0) {
        $likes_query = "SELECT post_id, COUNT(*) as count FROM post_likes GROUP BY post_id";
        $likes_result = $conn->query($likes_query);
        if ($likes_result) {
            while ($row = $likes_result->fetch_assoc()) {
                $likes_count[$row['post_id']] = $row['count'];
            }
        }
    }

    // Get comments count for each post
    $comments_count = [];

    // Check if post_comments table exists
    $table_check = $conn->query("SHOW TABLES LIKE 'post_comments'");
    if ($table_check->num_rows > 0) {
        $comments_query = "SELECT post_id, COUNT(*) as count FROM post_comments GROUP BY post_id";
        $comments_result = $conn->query($comments_query);
        if ($comments_result) {
            while ($row = $comments_result->fetch_assoc()) {
                $comments_count[$row['post_id']] = $row['count'];
            }
        }
    }
}

// Calculate pagination variables
$has_previous = $page > 1;
$has_next = $page < $total_pages;

// Don't close the connection here
// $conn->close();
?>

<style>
    .share-knowledge-btn {
        background-color: #FF6B35;
        /* Bright orange color */
        color: white;
        font-size: 1.1rem;
        font-weight: bold;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 14px rgba(255, 107, 53, 0.5);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        border: 2px solid #FF6B35;
    }

    .share-knowledge-btn:hover {
        background-color: #FF8655;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(255, 107, 53, 0.6);
    }

    .share-knowledge-btn:active {
        transform: translateY(0);
    }

    .share-knowledge-btn i {
        font-size: 1.2rem;
        margin-right: 0.75rem;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 107, 53, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(255, 107, 53, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(255, 107, 53, 0);
        }
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }
</style>

<div class="py-6"></div>

<div class="container mx-auto px-4 my-8">
    <div class="max-w-6xl mx-auto">
        <!-- Community Banner Section -->
        <div class="bg-gradient-to-r from-primary to-blue-700 rounded-lg shadow-xl mb-10 overflow-hidden">
            <div class="p-8 md:p-10">
                <h1 class="text-4xl font-bold text-white mb-4">VidyaSathi Community</h1>
                <p class="text-white text-lg opacity-90 mb-6 max-w-3xl">Connect with fellow students, share knowledge,
                    ask questions, and build your network. Our community is a space for collaboration and growth.</p>

                <div class="flex flex-wrap gap-4 mb-6">
                    <div class="bg-white bg-opacity-20 rounded-lg px-4 py-3 text-white flex items-center">
                        <i class="fas fa-users text-xl mr-3"></i>
                        <div>
                            <div class="text-2xl font-bold"><?php echo $total_posts; ?></div>
                            <div class="text-sm opacity-90">Total Posts</div>
                        </div>
                    </div>

                    <div class="bg-white bg-opacity-20 rounded-lg px-4 py-3 text-white flex items-center">
                        <i class="fas fa-comments text-xl mr-3"></i>
                        <div>
                            <div class="text-2xl font-bold">
                                <?php
                                $total_comments = 0;
                                foreach ($comments_count as $count) {
                                    $total_comments += $count;
                                }
                                echo $total_comments;
                                ?>
                            </div>
                            <div class="text-sm opacity-90">Discussions</div>
                        </div>
                    </div>

                    <div class="bg-white bg-opacity-20 rounded-lg px-4 py-3 text-white flex items-center">
                        <i class="fas fa-heart text-xl mr-3"></i>
                        <div>
                            <div class="text-2xl font-bold">
                                <?php
                                $total_likes = 0;
                                foreach ($likes_count as $count) {
                                    $total_likes += $count;
                                }
                                echo $total_likes;
                                ?>
                            </div>
                            <div class="text-sm opacity-90">Appreciations</div>
                        </div>
                    </div>
                </div>

                <a href="new_post.php" class="share-knowledge-btn pulse-animation">
                    <i class="fas fa-plus"></i> Share Your Knowledge
                </a>
            </div>
        </div>

        <!-- Search and Filters Section -->
        <div class="bg-card-bg rounded-lg p-6 mb-8 shadow-lg border border-gray-800">
            <h2 class="text-xl font-bold mb-4">Find Posts</h2>
            <form action="" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                            placeholder="Search by keyword, topic, or content..."
                            class="w-full p-3 pl-10 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-500"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary w-full md:w-auto">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                </div>
                <?php if (!empty($search)): ?>
                    <div>
                        <a href="community.php" class="btn btn-secondary w-full md:w-auto">
                            <i class="fas fa-times mr-2"></i> Clear
                        </a>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Community Guidelines -->
        <div class="bg-dark-accent rounded-lg p-4 mb-8 border border-gray-700">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-primary text-xl mt-1 mr-3"></i>
                <div>
                    <h3 class="font-bold mb-1">Community Guidelines</h3>
                    <p class="text-sm text-gray-300">Be respectful, share valuable content, provide constructive
                        feedback, and help each other grow. Together we build a supportive learning environment.</p>
                </div>
            </div>
        </div>

        <!-- Posts List -->
        <?php if (count($posts) > 0): ?>
            <h2 class="text-2xl font-bold mb-6">Latest Discussions</h2>
            <div class="grid grid-cols-1 gap-6 mb-8">
                <?php foreach ($posts as $post): ?>
                    <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden mb-6">
                        <!-- Post header with author info -->
                        <div class="p-4 flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <?php if (!empty($post['profile_image'])): ?>
                                    <img src="uploads/profiles/<?php echo htmlspecialchars($post['profile_image']); ?>"
                                        alt="Profile" class="w-12 h-12 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center">
                                        <span
                                            class="text-xl font-bold text-white"><?php echo substr($post['author_name'], 0, 1); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow">
                                <h3 class="font-semibold"><?php echo htmlspecialchars($post['author_name']); ?></h3>
                                <p class="text-gray-400 text-sm">
                                    <?php echo date('M d, Y \a\t h:i A', strtotime($post['created_at'])); ?>
                                </p>
                                <p class="text-gray-400 text-xs"><?php echo ucfirst(htmlspecialchars($post['author_role'])); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Post content -->
                        <div class="px-6 py-4">
                            <div class="prose prose-invert max-w-none mb-4">
                                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                            </div>

                            <?php if ($post['has_file']): ?>
                                <div class="mt-4">
                                    <div class="flex items-center">
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
                                        ?> mr-2 text-lg"></i>
                                        <span><?php echo htmlspecialchars($post['file_name']); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Post actions (like, comment) -->
                        <div class="px-6 py-3 border-t border-gray-800 flex space-x-4">
                            <?php
                            // Check if user has liked this post
                            $liked = false;
                            if (isset($_SESSION['user_id'])) {
                                $like_check_stmt = $conn->prepare("SELECT id FROM post_likes WHERE post_id = ? AND user_id = ?");
                                $like_check_stmt->bind_param("ii", $post['id'], $_SESSION['user_id']);
                                $like_check_stmt->execute();
                                $like_result = $like_check_stmt->get_result();
                                $liked = $like_result->num_rows > 0;
                            }

                            // Get total likes for this post
                            $like_count_stmt = $conn->prepare("SELECT COUNT(*) as like_count FROM post_likes WHERE post_id = ?");
                            $like_count_stmt->bind_param("i", $post['id']);
                            $like_count_stmt->execute();
                            $like_count_result = $like_count_stmt->get_result();
                            $like_count = $like_count_result->fetch_assoc()['like_count'] ?? 0;
                            ?>

                            <a href="<?php echo isset($_SESSION['user_id']) ? "like_post.php?post_id={$post['id']}&redirect_url=community.php" : "login.php"; ?>"
                                class="flex items-center space-x-1 group">
                                <span
                                    class="<?php echo $liked ? 'text-red-500' : 'text-gray-400 group-hover:text-red-500'; ?> transition-colors">
                                    <i class="fas fa-heart"></i>
                                </span>
                                <span
                                    class="text-sm <?php echo $liked ? 'text-red-500' : 'text-gray-400 group-hover:text-red-500'; ?> transition-colors">
                                    <?php echo $like_count; ?>         <?php echo $like_count == 1 ? 'Like' : 'Likes'; ?>
                                </span>
                            </a>

                            <a href="view_post.php?id=<?php echo $post['id']; ?>"
                                class="flex items-center space-x-1 text-gray-400 hover:text-blue-500 transition-colors">
                                <i class="fas fa-comment"></i>
                                <?php
                                // Get comments count for this post
                                $comment_count = 0;
                                $comment_count_stmt = $conn->prepare("SELECT COUNT(*) as comment_count FROM post_comments WHERE post_id = ?");
                                $comment_count_stmt->bind_param("i", $post['id']);
                                $comment_count_stmt->execute();
                                $comment_result = $comment_count_stmt->get_result();
                                if ($comment_result) {
                                    $comment_count = $comment_result->fetch_assoc()['comment_count'] ?? 0;
                                }
                                ?>
                                <span class="text-sm"><?php echo $comment_count; ?>
                                    <?php echo $comment_count == 1 ? 'Comment' : 'Comments'; ?></span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="flex justify-center mt-8">
                    <div class="flex space-x-1">
                        <?php if ($has_previous): ?>
                            <a href="?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"
                                class="px-4 py-2 bg-dark border border-gray-700 rounded-lg hover:bg-primary hover:text-white transition-all">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php else: ?>
                            <span class="px-4 py-2 bg-dark border border-gray-700 rounded-lg opacity-50 cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        <?php endif; ?>

                        <?php
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $start_page + 4);

                        if ($end_page - $start_page < 4) {
                            $start_page = max(1, $end_page - 4);
                        }

                        for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                            <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"
                                class="px-4 py-2 border <?php echo $i === $page ? 'bg-primary text-white border-primary' : 'bg-dark border-gray-700 hover:bg-primary hover:text-white'; ?> rounded-lg transition-all">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($has_next): ?>
                            <a href="?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"
                                class="px-4 py-2 bg-dark border border-gray-700 rounded-lg hover:bg-primary hover:text-white transition-all">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php else: ?>
                            <span class="px-4 py-2 bg-dark border border-gray-700 rounded-lg opacity-50 cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 p-8 text-center">
                <i class="fas fa-comments text-5xl text-gray-600 mb-4"></i>
                <?php if (!empty($search)): ?>
                    <h2 class="text-2xl font-bold mb-2">No Matching Posts</h2>
                    <p class="text-gray-400 mb-6">We couldn't find any posts matching your search criteria.</p>
                    <a href="community.php" class="btn btn-primary">
                        <i class="fas fa-times mr-2"></i> Clear Search
                    </a>
                <?php else: ?>
                    <h2 class="text-2xl font-bold mb-2">Start the First Discussion</h2>
                    <p class="text-gray-400 mb-6">Be the first to share your thoughts with the community!</p>
                    <a href="new_post.php" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i> Create First Post
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Close the connection at the end of the file, after all database operations
include 'footer.php';
$conn->close();
?>

<link rel="stylesheet" href="css/style.css">