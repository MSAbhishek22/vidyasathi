<?php
require_once 'db.php';

// Set content type to HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Fix Comment Columns</title>
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
        <h1>Fix Comment Columns</h1>
        <?php
        // Check if post_comments table exists
        $check_table = $conn->query("SHOW TABLES LIKE 'post_comments'");

        if ($check_table->num_rows > 0) {
            // Check if both parent_comment_id and parent_id columns exist
            $check_parent_comment_id = $conn->query("SHOW COLUMNS FROM post_comments LIKE 'parent_comment_id'");
            $check_parent_id = $conn->query("SHOW COLUMNS FROM post_comments LIKE 'parent_id'");

            if ($check_parent_comment_id->num_rows > 0 && $check_parent_id->num_rows > 0) {
                // Sync NULL values in parent_comment_id from parent_id
                $sql1 = "UPDATE post_comments 
                         SET parent_comment_id = parent_id 
                         WHERE parent_comment_id IS NULL AND parent_id IS NOT NULL";

                // Sync NULL values in parent_id from parent_comment_id
                $sql2 = "UPDATE post_comments 
                         SET parent_id = parent_comment_id 
                         WHERE parent_id IS NULL AND parent_comment_id IS NOT NULL";

                $success = true;

                // Execute first query
                if ($conn->query($sql1) === FALSE) {
                    echo '<p class="error">Error syncing parent_comment_id: ' . $conn->error . '</p>';
                    $success = false;
                }

                // Execute second query
                if ($conn->query($sql2) === FALSE) {
                    echo '<p class="error">Error syncing parent_id: ' . $conn->error . '</p>';
                    $success = false;
                }

                if ($success) {
                    echo '<p class="success">Comment parent columns synchronized successfully!</p>';
                    echo '<p>You can now <a href="community.php">go to Community page</a> to use nested comments.</p>';
                }
            } else {
                if ($check_parent_comment_id->num_rows == 0) {
                    echo '<p class="error">The parent_comment_id column does not exist.</p>';
                    echo '<p>Please run the <a href="update_comments_table.php">update_comments_table.php</a> script first.</p>';
                }

                if ($check_parent_id->num_rows == 0) {
                    echo '<p class="error">The parent_id column does not exist.</p>';
                    echo '<p>Please run the <a href="create_nested_comments_table.php">create_nested_comments_table.php</a> script first.</p>';
                }
            }
        } else {
            echo '<p class="error">The post_comments table does not exist.</p>';
            echo '<p>Please run the <a href="create_comments_table.php">create_comments_table.php</a> script first.</p>';
        }
        ?>
    </div>
</body>

</html>