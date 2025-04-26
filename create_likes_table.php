<?php
// Database table creation script for post_likes
require_once 'db.php';

// SQL to create post_likes table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS `post_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_post_unique` (`post_id`,`user_id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background-color: #f0f8ff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);'>";
    echo "<h2 style='color: #4CAF50; text-align: center;'>Success!</h2>";
    echo "<p style='font-size: 16px; line-height: 1.5;'>The post_likes table has been created successfully. The like functionality should now work properly.</p>";
    echo "<p style='margin-top: 20px;'><a href='community.php' style='display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Community Page</a></p>";
    echo "</div>";
} else {
    echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background-color: #fff0f0; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);'>";
    echo "<h2 style='color: #f44336; text-align: center;'>Error</h2>";
    echo "<p style='font-size: 16px; line-height: 1.5;'>Could not create the post_likes table: " . $conn->error . "</p>";
    echo "<p style='margin-top: 20px;'><a href='index.php' style='display: inline-block; background-color: #f44336; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Return to Home</a></p>";
    echo "</div>";
}

// Close the connection
$conn->close();
?>