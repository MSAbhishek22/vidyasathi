<?php
require_once 'db.php';

// Set content type to HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Create Comments Table</title>
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
        <h1>Database Setup</h1>
        <?php
        // Create comments table
        $sql = "CREATE TABLE IF NOT EXISTS `post_comments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `post_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `parent_comment_id` int(11) DEFAULT NULL,
            `comment` text NOT NULL,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `post_id` (`post_id`),
            KEY `user_id` (`user_id`),
            KEY `parent_comment_id` (`parent_comment_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        if ($conn->query($sql) === TRUE) {
            echo '<p class="success">Post comments table created successfully</p>';
            echo '<p>You can now <a href="community.php">go to Community page</a> or <a href="index.php">return to Home</a>.</p>';
        } else {
            echo '<p class="error">Error creating post comments table: ' . $conn->error . '</p>';
        }
        ?>
    </div>
</body>

</html>