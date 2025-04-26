<?php
require_once 'db.php';

// Set content type to HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Update Comments Table for Nested Comments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        a {
            color: #6b46c1;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Database Update for Nested Comments</h1>
        <?php
        // Check if post_comments table exists
        $check_table = $conn->query("SHOW TABLES LIKE 'post_comments'");

        if ($check_table->num_rows > 0) {
            // Check if parent_id column already exists
            $check_column = $conn->query("SHOW COLUMNS FROM post_comments LIKE 'parent_id'");

            if ($check_column->num_rows == 0) {
                // Add parent_id column for nested comments
                $sql = "ALTER TABLE post_comments ADD parent_id INT(11) NULL DEFAULT NULL AFTER user_id, ADD INDEX parent_id (parent_id)";

                if ($conn->query($sql) === TRUE) {
                    echo '<p class="success">Comments table updated successfully with parent_id support for nested comments</p>';
                    echo '<p>You can now <a href="community.php">go to Community page</a> to see threaded comments.</p>';
                } else {
                    echo '<p class="error">Error updating comments table: ' . $conn->error . '</p>';
                }
            } else {
                echo '<p class="success">The parent_id column already exists in the post_comments table.</p>';
                echo '<p>Nested comments are already supported. <a href="community.php">Go to Community page</a>.</p>';
            }
        } else {
            // Create comments table with parent_id support
            $sql = "CREATE TABLE IF NOT EXISTS `post_comments` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `post_id` int(11) NOT NULL,
                `user_id` int(11) NOT NULL,
                `parent_id` int(11) NULL DEFAULT NULL,
                `comment` text NOT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `post_id` (`post_id`),
                KEY `user_id` (`user_id`),
                KEY `parent_id` (`parent_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

            if ($conn->query($sql) === TRUE) {
                echo '<p class="success">Comments table created successfully with parent_id support for nested comments</p>';
                echo '<p>You can now <a href="community.php">go to Community page</a> to create threaded discussions.</p>';
            } else {
                echo '<p class="error">Error creating comments table: ' . $conn->error . '</p>';
            }
        }
        ?>
    </div>
</body>

</html>