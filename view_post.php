<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if post ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = intval($_GET['id']);
$current_user_id = $_SESSION['user_id'];
$is_admin = ($_SESSION['user_role'] === 'admin');
$is_moderator = ($_SESSION['user_role'] === 'moderator');

// Get post data
$post_query = "SELECT p.*, u.name as author_name, u.profile_image, u.role as author_role
               FROM posts p
               JOIN users u ON p.user_id = u.id
               WHERE p.id = ?";
$stmt = $conn->prepare($post_query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if post exists
if ($result->num_rows === 0) {
    header("Location: dashboard.php");
    exit();
}

$post = $result->fetch_assoc();
$stmt->close();

// Check if current user can view this post
$is_post_owner = ($post['user_id'] == $current_user_id);
$can_edit = $is_post_owner || $is_admin || $is_moderator;

// Check if user has liked the post
$user_liked = false;
$like_count = 0;

// Check if likes table exists
$check_likes_table = $conn->query("SHOW TABLES LIKE 'post_likes'");
if ($check_likes_table->num_rows > 0) {
    // Check if user has liked this post
    $like_check_query = "SELECT id FROM post_likes WHERE post_id = ? AND user_id = ?";
    $like_check_stmt = $conn->prepare($like_check_query);
    $like_check_stmt->bind_param("ii", $post_id, $current_user_id);
    $like_check_stmt->execute();
    $like_check_result = $like_check_stmt->get_result();
    $user_liked = ($like_check_result->num_rows > 0);
    $like_check_stmt->close();

    // Get total likes count
    $like_count_query = "SELECT COUNT(*) as count FROM post_likes WHERE post_id = ?";
    $like_count_stmt = $conn->prepare($like_count_query);
    $like_count_stmt->bind_param("i", $post_id);
    $like_count_stmt->execute();
    $like_count_result = $like_count_stmt->get_result();
    $like_count = $like_count_result->fetch_assoc()['count'];
    $like_count_stmt->close();
}

// Get comments for this post
$comments = [];
$comment_tree = [];
$check_comments_table = $conn->query("SHOW TABLES LIKE 'post_comments'");
if ($check_comments_table->num_rows > 0) {
    $comments_query = "SELECT c.*, u.name as user_name, u.profile_image, u.role as user_role
                      FROM post_comments c
                      JOIN users u ON c.user_id = u.id
                      WHERE c.post_id = ?
                      ORDER BY c.created_at ASC";
    $comments_stmt = $conn->prepare($comments_query);
    $comments_stmt->bind_param("i", $post_id);
    $comments_stmt->execute();
    $comments_result = $comments_stmt->get_result();

    while ($comment = $comments_result->fetch_assoc()) {
        $comments[] = $comment;

        // Add to comment tree structure
        // Check for both parent_comment_id and parent_id for backward compatibility
        $parent_id = null;
        if (isset($comment['parent_comment_id'])) {
            $parent_id = $comment['parent_comment_id'];
        } elseif (isset($comment['parent_id'])) {
            $parent_id = $comment['parent_id'];
        }

        if ($parent_id === null) {
            // This is a top-level comment
            if (!isset($comment_tree[$comment['id']])) {
                $comment_tree[$comment['id']] = [];
            }
            $comment['replies'] = &$comment_tree[$comment['id']];
            $comment_tree['root'][] = $comment;
        } else {
            // This is a reply
            if (!isset($comment_tree[$parent_id])) {
                $comment_tree[$parent_id] = [];
            }
            $comment['replies'] = [];
            $comment_tree[$parent_id][] = $comment;
        }
    }

    $comments_stmt->close();
}

// Set page title and include header
$page_title = htmlspecialchars($post['title']) . ' - VidyaSathi';
include 'header.php';
?>

<div class="py-6"></div>

<div class="container mx-auto px-4 my-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back to Dashboard Link -->
        <div class="mb-6 flex justify-between items-center">
            <a href="community.php" class="text-primary hover:underline">
                <i class="fas fa-arrow-left mr-2"></i> Back to Community
            </a>
            <a href="dashboard.php" class="text-primary hover:underline">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
        </div>

        <!-- Post Display -->
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800">
            <!-- Post Header -->
            <div class="p-6 border-b border-gray-800 bg-dark">
                <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($post['title']); ?></h1>

                <div class="flex items-center mt-4">
                    <!-- Author Avatar -->
                    <div class="mr-4">
                        <?php if (!empty($post['profile_image']) && file_exists($post['profile_image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['profile_image']); ?>" alt="Profile"
                                class="rounded-full" style="width: 40px; height: 40px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-full bg-primary text-white flex items-center justify-center"
                                style="width: 40px; height: 40px; font-size: 1.2rem;">
                                <?php echo strtoupper(substr($post['author_name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Post Metadata -->
                    <div>
                        <div class="flex items-center">
                            <a href="user_profile.php?id=<?php echo $post['user_id']; ?>"
                                class="font-medium hover:text-primary">
                                <?php echo htmlspecialchars($post['author_name']); ?>
                            </a>
                            <span class="ml-2 px-2 py-1 bg-primary text-white text-xs rounded-full">
                                <?php echo ucfirst(htmlspecialchars($post['author_role'])); ?>
                            </span>
                        </div>
                        <div class="text-sm text-gray-400">
                            <span><i class="fas fa-clock mr-1"></i>
                                <?php echo date('M d, Y \a\t h:i A', strtotime($post['created_at'])); ?></span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-3">
                    <?php if ($can_edit): ?>
                        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="text-primary hover:underline">
                            <i class="fas fa-edit mr-1"></i> Edit Post
                        </a>
                        <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="text-red-500 hover:underline"
                            onclick="return confirm('Are you sure you want to delete this post?');">
                            <i class="fas fa-trash-alt mr-1"></i> Delete
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Post Content -->
            <div class="p-6">
                <!-- Post Text Content -->
                <div class="mb-6 text-gray-300 whitespace-pre-line">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>

                <!-- Attached File (if any) -->
                <?php if ($post['has_file']): ?>
                    <div class="mt-8 pt-6 border-t border-gray-800">
                        <h3 class="text-lg font-semibold mb-4">Attached File</h3>

                        <div class="bg-dark p-4 rounded-lg border border-gray-700">
                            <?php
                            // Determine file icon based on type
                            $file_icon = 'fa-file';
                            $icon_color = 'text-gray-400';

                            if (strpos($post['file_type'], 'image/') === 0) {
                                $file_icon = 'fa-file-image';
                                $icon_color = 'text-blue-400';
                            } elseif ($post['file_type'] === 'application/pdf') {
                                $file_icon = 'fa-file-pdf';
                                $icon_color = 'text-red-400';
                            } elseif (
                                strpos($post['file_type'], 'application/vnd.ms-excel') === 0 ||
                                strpos($post['file_type'], 'application/vnd.openxmlformats-officedocument.spreadsheetml') === 0
                            ) {
                                $file_icon = 'fa-file-excel';
                                $icon_color = 'text-green-400';
                            } elseif (
                                strpos($post['file_type'], 'application/msword') === 0 ||
                                strpos($post['file_type'], 'application/vnd.openxmlformats-officedocument.wordprocessingml') === 0
                            ) {
                                $file_icon = 'fa-file-word';
                                $icon_color = 'text-blue-400';
                            }

                            // Show file preview for images
                            if (strpos($post['file_type'], 'image/') === 0):
                                ?>
                                <div class="mb-4">
                                    <img src="<?php echo htmlspecialchars($post['file_path']); ?>" alt="Attached Image"
                                        class="max-w-full rounded-lg max-h-96 mx-auto">
                                </div>
                            <?php endif; ?>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas <?php echo $file_icon; ?> <?php echo $icon_color; ?> text-2xl mr-3"></i>
                                    <div>
                                        <div class="font-medium"><?php echo htmlspecialchars($post['file_name']); ?></div>
                                        <div class="text-xs text-gray-500">
                                            <?php
                                            // Convert file type to readable format
                                            $file_type_readable = $post['file_type'];
                                            if ($file_type_readable === 'application/pdf') {
                                                $file_type_readable = 'PDF Document';
                                            } elseif (strpos($file_type_readable, 'image/') === 0) {
                                                $file_type_readable = str_replace('image/', '', $file_type_readable) . ' Image';
                                                $file_type_readable = strtoupper($file_type_readable);
                                            } elseif (
                                                $file_type_readable === 'application/msword' ||
                                                $file_type_readable === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                                            ) {
                                                $file_type_readable = 'Word Document';
                                            } elseif (
                                                $file_type_readable === 'application/vnd.ms-excel' ||
                                                $file_type_readable === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                            ) {
                                                $file_type_readable = 'Excel Spreadsheet';
                                            }

                                            echo $file_type_readable;
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <a href="<?php echo htmlspecialchars($post['file_path']); ?>" class="btn btn-primary"
                                    download="<?php echo htmlspecialchars($post['file_name']); ?>">
                                    <i class="fas fa-download mr-2"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Like and Comment Section -->
                <div class="mt-8 pt-6 border-t border-gray-800">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center">
                            <button id="likeButton"
                                class="flex items-center <?php echo $user_liked ? 'text-red-500' : 'text-gray-400'; ?> hover:text-red-500 transition-colors"
                                onclick="likePost(<?php echo $post_id; ?>)">
                                <i class="<?php echo $user_liked ? 'fas' : 'far'; ?> fa-heart text-2xl mr-2"></i>
                                <span id="likeCount"><?php echo $like_count; ?></span>
                            </button>
                            <button class="flex items-center text-gray-400 hover:text-primary transition-colors ml-4"
                                onclick="focusCommentBox()">
                                <i class="far fa-comment text-2xl mr-2"></i>
                                <span id="commentCount"><?php echo count($comments); ?></span>
                            </button>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div id="commentsSection">
                        <h3 class="text-xl font-semibold mb-4">Comments
                            <?php if (count($comments) > 0): ?>(<?php echo count($comments); ?>)<?php endif; ?>
                        </h3>

                        <!-- New Comment Form -->
                        <div class="bg-dark p-4 rounded-lg mb-6">
                            <form action="post_comment.php" method="POST" class="space-y-4">
                                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                <input type="hidden" name="redirect_url"
                                    value="<?php echo "view_post.php?id=$post_id"; ?>">
                                <div>
                                    <textarea name="comment"
                                        class="w-full p-3 bg-card-bg border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        placeholder="Write a comment..." rows="3" required></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane mr-2"></i> Post Comment
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Comments List -->
                        <div id="commentsList" class="space-y-4">
                            <?php if (count($comments) === 0): ?>
                                <div id="noComments" class="text-center py-4 text-gray-500">
                                    <p>No comments yet. Be the first to comment!</p>
                                </div>
                            <?php else: ?>
                                <?php
                                // Display comments recursively
                                function displayComments($comments, $level = 0, $post_id)
                                {
                                    foreach ($comments as $comment):
                                        ?>
                                        <div class="comment bg-dark p-4 rounded-lg mb-3 <?php echo $level > 0 ? 'ml-' . ($level * 5) : ''; ?>"
                                            style="<?php echo $level > 0 ? 'margin-left: ' . ($level * 20) . 'px;' : ''; ?> border-left: <?php echo $level > 0 ? '2px solid #6b46c1' : 'none'; ?>">
                                            <div class="flex items-start">
                                                <div class="mr-3 mt-1">
                                                    <?php if (!empty($comment['profile_image']) && file_exists($comment['profile_image'])): ?>
                                                        <img src="<?php echo htmlspecialchars($comment['profile_image']); ?>"
                                                            alt="Profile" class="rounded-full"
                                                            style="width: 32px; height: 32px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="rounded-full bg-primary text-white flex items-center justify-center"
                                                            style="width: 32px; height: 32px; font-size: 0.875rem;">
                                                            <?php echo strtoupper(substr($comment['user_name'], 0, 1)); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-1">
                                                        <a href="user_profile.php?id=<?php echo $comment['user_id']; ?>"
                                                            class="font-medium hover:text-primary">
                                                            <?php echo htmlspecialchars($comment['user_name']); ?>
                                                        </a>
                                                        <span
                                                            class="ml-2 px-2 py-0.5 bg-dark-accent text-xs rounded-full text-gray-400">
                                                            <?php echo ucfirst(htmlspecialchars($comment['user_role'])); ?>
                                                        </span>
                                                        <?php if (
                                                            (isset($comment['parent_comment_id']) && $comment['parent_comment_id']) ||
                                                            (isset($comment['parent_id']) && $comment['parent_id'])
                                                        ): ?>
                                                            <span class="ml-2 text-gray-500 text-xs">
                                                                <i class="fas fa-reply mr-1"></i> Reply
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-gray-300 mb-1">
                                                        <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                                                    </div>
                                                    <div class="flex items-center justify-between">
                                                        <div class="text-xs text-gray-500">
                                                            <?php echo date('M d, Y \a\t h:i A', strtotime($comment['created_at'])); ?>
                                                        </div>
                                                        <button class="text-xs text-primary hover:underline reply-button"
                                                            onclick="showReplyForm(<?php echo $comment['id']; ?>)">
                                                            <i class="fas fa-reply mr-1"></i> Reply
                                                        </button>
                                                    </div>

                                                    <!-- Reply form (hidden by default) -->
                                                    <div id="replyForm-<?php echo $comment['id']; ?>" class="mt-3 hidden">
                                                        <form action="post_comment.php" method="POST" class="space-y-3">
                                                            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                                            <input type="hidden" name="parent_comment_id"
                                                                value="<?php echo $comment['id']; ?>">
                                                            <input type="hidden" name="redirect_url"
                                                                value="<?php echo "view_post.php?id=" . $post_id; ?>">
                                                            <textarea name="comment"
                                                                class="w-full p-2 bg-card-bg border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                                                placeholder="Write a reply..." rows="2" required></textarea>
                                                            <div class="flex justify-end space-x-2">
                                                                <button type="button" class="btn btn-secondary text-xs py-1 px-3"
                                                                    onclick="hideReplyForm(<?php echo $comment['id']; ?>)">
                                                                    Cancel
                                                                </button>
                                                                <button type="submit" class="btn btn-primary text-xs py-1 px-3">
                                                                    <i class="fas fa-paper-plane mr-1"></i> Reply
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Display replies if any -->
                                            <?php if (isset($comment['replies']) && !empty($comment['replies'])): ?>
                                                <div class="mt-3">
                                                    <?php displayComments($comment['replies'], $level + 1, $post_id); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php
                                    endforeach;
                                }

                                // Start displaying from root comments
                                if (isset($comment_tree['root']) && !empty($comment_tree['root'])) {
                                    displayComments($comment_tree['root'], 0, $post_id);
                                }
                                ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for likes and comments -->
<script>
    // Like post function
    function likePost(postId) {
        fetch('like_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `post_id=${postId}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const likeButton = document.getElementById('likeButton');
                    const likeCount = document.getElementById('likeCount');
                    const heartIcon = likeButton.querySelector('i');

                    // Update like count
                    likeCount.textContent = data.like_count;

                    // Toggle heart icon
                    if (data.action === 'liked') {
                        heartIcon.classList.remove('far');
                        heartIcon.classList.add('fas');
                        likeButton.classList.add('text-red-500');
                        likeButton.classList.remove('text-gray-400');
                    } else {
                        heartIcon.classList.remove('fas');
                        heartIcon.classList.add('far');
                        likeButton.classList.remove('text-red-500');
                        likeButton.classList.add('text-gray-400');
                    }
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Focus on comment box
    function focusCommentBox() {
        const commentTextArea = document.querySelector('textarea[name="comment"]');
        if (commentTextArea) {
            commentTextArea.focus();
        }
    }

    // Show reply form
    function showReplyForm(commentId) {
        const replyForm = document.getElementById(`replyForm-${commentId}`);
        if (replyForm) {
            replyForm.classList.remove('hidden');
            const textarea = replyForm.querySelector('textarea');
            if (textarea) {
                textarea.focus();
            }
        }
    }

    // Hide reply form
    function hideReplyForm(commentId) {
        const replyForm = document.getElementById(`replyForm-${commentId}`);
        if (replyForm) {
            replyForm.classList.add('hidden');
        }
    }
</script>

<?php include 'footer.php'; ?>

<link rel="stylesheet" href="css/style.css">