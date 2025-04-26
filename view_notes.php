<?php
require 'db.php';
$sql = "SELECT * FROM uploads WHERE approved = 1 ORDER BY upload_date DESC";
$result = $conn->query($sql);

echo "<h2>Approved Notes & Resources</h2><ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>
            <strong>{$row['title']}</strong> - {$row['subject']} ({$row['category']}) 
            <a href='{$row['file_path']}' target='_blank'>Download</a>
          </li>";
}
echo "</ul>";
?>
