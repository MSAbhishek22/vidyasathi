<?php
require_once 'db.php';

// Set content type to HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Update Comments Table Structure</title>
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
        <h1>Update Comments Table Structure</h1>
        <?php
        // Check if post_comments table exists
        $check_table = $conn->query("SHOW TABLES LIKE 'post_comments'");

        if ($check_table->num_rows > 0) {
            // Check if parent_comment_id column already exists
            $check_column = $conn->query("SHOW COLUMNS FROM post_comments LIKE 'parent_comment_id'");

            if ($check_column->num_rows == 0) {
                // Add parent_comment_id column for nested comments
                $sql = "ALTER TABLE post_comments ADD parent_comment_id INT(11) NULL DEFAULT NULL AFTER user_id, ADD INDEX parent_comment_id (parent_comment_id)";

                if ($conn->query($sql) === TRUE) {
                    echo '<p class="success">Comments table updated successfully with parent_comment_id support for nested comments</p>';
                    echo '<p>You can now <a href="community.php">go to Community page</a> to use nested comments.</p>';
                } else {
                    echo '<p class="error">Error updating comments table: ' . $conn->error . '</p>';
                }
            } else {
                echo '<p class="success">The parent_comment_id column already exists in the post_comments table.</p>';
                echo '<p>Nested comments are already supported. <a href="community.php">Go to Community page</a>.</p>';
            }
        } else {
            echo '<p class="error">The post_comments table does not exist.</p>';
            echo '<p>Please run the <a href="create_comments_table.php">create_comments_table.php</a> script first.</p>';
        }
        ?>
    </div>
</body>

</html>