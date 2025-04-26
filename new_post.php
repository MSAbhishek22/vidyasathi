<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Set page title and include header
$page_title = 'Create New Post - VidyaSathi';
include 'header.php';

// Include database connection
require_once 'config.php';

$error_message = '';
$success_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    // Validate inputs
    if (empty($title) || empty($content)) {
        $error_message = "Title and content are required.";
    } else {
        // Initialize variables for file handling
        $has_file = false;
        $file_path = null;
        $file_type = null;
        $file_name = null;

        // Check if file was uploaded
        if (isset($_FILES['post_file']) && $_FILES['post_file']['error'] == 0) {
            $has_file = true;
            $allowed_types = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'text/plain'
            ];

            $file_type = $_FILES['post_file']['type'];
            $file_size = $_FILES['post_file']['size'];
            $file_tmp = $_FILES['post_file']['tmp_name'];
            $file_name = $_FILES['post_file']['name'];

            // Validate file type
            if (!in_array($file_type, $allowed_types)) {
                $error_message = "File type not allowed. Please upload images, PDFs, or office documents.";
            }
            // Validate file size (10MB max)
            else if ($file_size > 10485760) {
                $error_message = "File is too large. Maximum size is 10MB.";
            }
            // Process file upload
            else {
                // Create directory if it doesn't exist
                $upload_dir = "uploads/posts/";
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Generate unique filename
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_filename = uniqid('post_') . '_' . time() . '.' . $file_extension;
                $file_path = $upload_dir . $new_filename;

                // Move uploaded file
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // File uploaded successfully
                } else {
                    $error_message = "Failed to upload file. Please try again.";
                    $has_file = false;
                }
            }
        }

        // If no errors, insert post into database
        if (empty($error_message)) {
            // Check if posts table exists, create if not
            $check_table = $conn->query("SHOW TABLES LIKE 'posts'");
            if ($check_table->num_rows == 0) {
                // Create posts table
                $create_table_sql = "
                CREATE TABLE `posts` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `user_id` int(11) NOT NULL,
                  `title` varchar(255) NOT NULL,
                  `content` text NOT NULL,
                  `has_file` tinyint(1) NOT NULL DEFAULT 0,
                  `file_path` varchar(255) DEFAULT NULL,
                  `file_type` varchar(100) DEFAULT NULL,
                  `file_name` varchar(255) DEFAULT NULL,
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  KEY `user_id` (`user_id`),
                  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

                if ($conn->query($create_table_sql) === FALSE) {
                    $error_message = "Error creating posts table: " . $conn->error;
                }
            }

            if (empty($error_message)) {
                // Insert post into database
                $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, has_file, file_path, file_type, file_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $has_file_int = $has_file ? 1 : 0;
                $stmt->bind_param("issssss", $user_id, $title, $content, $has_file_int, $file_path, $file_type, $file_name);

                if ($stmt->execute()) {
                    $success_message = "Post created successfully!";
                    // Clear form data on successful submission
                    $title = $content = '';
                } else {
                    $error_message = "Failed to create post: " . $stmt->error;
                }

                $stmt->close();
            }
        }
    }
}

$conn->close();
?>

<!-- Page Content -->
<div class="py-6"></div>

<div class="container mx-auto px-4 my-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800">
            <div class="p-6 bg-dark border-b border-gray-800">
                <h1 class="text-2xl font-bold">Create New Post</h1>
                <p class="text-gray-400 mt-2">Share your thoughts, questions, or resources with the community</p>
            </div>

            <?php if ($success_message): ?>
                <div class="bg-green-600 text-white p-4">
                    <?php echo $success_message; ?>
                    <div class="mt-2">
                        <a href="dashboard.php" class="inline-block bg-white text-green-600 px-4 py-2 rounded font-medium">
                            Return to Dashboard
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="bg-red-600 text-white p-4">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!$success_message): ?>
                <div class="p-6">
                    <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium mb-2">Post Title</label>
                            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title ?? ''); ?>"
                                required
                                class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium mb-2">Post Content</label>
                            <textarea name="content" id="content" rows="8" required
                                class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"><?php echo htmlspecialchars($content ?? ''); ?></textarea>
                        </div>

                        <div>
                            <label for="post_file" class="block text-sm font-medium mb-2">Attach File (Optional)</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" name="post_file" id="post_file"
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Supported formats: Images (JPEG, PNG, GIF), Documents (PDF, DOC, DOCX, XLS, XLSX, PPT,
                                PPTX), Text files
                            </p>
                            <p class="text-xs text-gray-500">
                                Maximum file size: 10MB
                            </p>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="btn btn-primary py-3 px-6">
                                <i class="fas fa-paper-plane mr-2"></i> Create Post
                            </button>
                            <a href="dashboard.php" class="btn btn-secondary py-3 px-6 ml-4">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<link rel="stylesheet" href="css/style.css">