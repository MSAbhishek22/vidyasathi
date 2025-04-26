<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle mark as read for all notifications
if (isset($_GET['mark_all_read']) && $_GET['mark_all_read'] == 1) {
    $mark_all_query = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $mark_all_query->bind_param("i", $user_id);
    $mark_all_query->execute();
    header("Location: notifications.php");
    exit();
}

// Handle mark as read for single notification
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $notification_id = intval($_GET['mark_read']);
    $mark_read_query = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
    $mark_read_query->bind_param("ii", $notification_id, $user_id);
    $mark_read_query->execute();

    // If redirect parameter is set, redirect to that URL
    if (isset($_GET['redirect'])) {
        header("Location: " . $_GET['redirect']);
        exit();
    } else {
        header("Location: notifications.php");
        exit();
    }
}

// Get notifications for this user
$query = "SELECT n.id, n.message, n.related_to, n.related_id, n.is_read, n.created_at 
          FROM notifications n 
          WHERE n.user_id = ? 
          ORDER BY n.created_at DESC 
          LIMIT 50";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Count unread notifications
$unread_query = $conn->prepare("SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = ? AND is_read = 0");
$unread_query->bind_param("i", $user_id);
$unread_query->execute();
$unread_result = $unread_query->get_result();
$unread_data = $unread_result->fetch_assoc();
$unread_count = $unread_data['unread_count'];

// Set page title and include header
$page_title = "Notifications";
include 'header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Notifications</h1>
                <?php if ($unread_count > 0): ?>
                    <a href="notifications.php?mark_all_read=1" class="btn btn-outline-primary">
                        <i class="fas fa-check-double me-1"></i> Mark All as Read
                    </a>
                <?php endif; ?>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Notifications</h5>
                    <?php if ($unread_count > 0): ?>
                        <span class="badge bg-primary"><?php echo $unread_count; ?> unread</span>
                    <?php endif; ?>
                </div>

                <?php if ($result->num_rows > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php while ($notification = $result->fetch_assoc()): ?>
                            <?php
                            // Determine the link for each notification type
                            $link = '#';
                            if ($notification['related_to'] === 'upload' && $notification['related_id']) {
                                $link = "view_file.php?id=" . $notification['related_id'];
                            } elseif ($notification['related_to'] === 'mentorship' && $notification['related_id']) {
                                $link = "mentorship_details.php?id=" . $notification['related_id'];
                            } elseif ($notification['related_to'] === 'message' && $notification['related_id']) {
                                $link = "messages.php?conversation=" . $notification['related_id'];
                            }

                            // Determine the icon for each notification type
                            $icon = 'fas fa-bell';
                            if ($notification['related_to'] === 'upload') {
                                $icon = 'fas fa-file-upload';
                            } elseif ($notification['related_to'] === 'download') {
                                $icon = 'fas fa-download';
                            } elseif ($notification['related_to'] === 'mentorship') {
                                $icon = 'fas fa-user-graduate';
                            } elseif ($notification['related_to'] === 'message') {
                                $icon = 'fas fa-envelope';
                            } elseif ($notification['related_to'] === 'system') {
                                $icon = 'fas fa-cog';
                            }
                            ?>

                            <a href="<?php echo $link; ?><?php echo ($notification['is_read'] == 0) ? '&mark_read=' . $notification['id'] : ''; ?>"
                                class="list-group-item list-group-item-action <?php echo ($notification['is_read'] == 0) ? 'list-group-item-light' : ''; ?>">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="rounded-circle <?php echo ($notification['is_read'] == 0) ? 'bg-primary' : 'bg-secondary'; ?> text-white d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="<?php echo $icon; ?>"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <?php echo $notification['message']; ?>
                                                <?php if ($notification['is_read'] == 0): ?>
                                                    <span class="badge bg-primary rounded-pill ms-2">New</span>
                                                <?php endif; ?>
                                            </div>
                                            <small
                                                class="text-muted"><?php echo timeAgo($notification['created_at']); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="card-body text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <h5>No notifications yet</h5>
                        <p class="text-muted">You don't have any notifications at the moment</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Notification Settings</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="uploadNotifications" checked>
                        <label class="form-check-label" for="uploadNotifications">Upload approvals and
                            rejections</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="mentorshipNotifications" checked>
                        <label class="form-check-label" for="mentorshipNotifications">Mentorship requests and
                            updates</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="messageNotifications" checked>
                        <label class="form-check-label" for="messageNotifications">Message notifications</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="systemNotifications" checked>
                        <label class="form-check-label" for="systemNotifications">System notifications</label>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="button" class="btn btn-primary" disabled>Save Settings</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Function to format date as time ago
function timeAgo($datetime)
{
    $timestamp = strtotime($datetime);
    $now = time();
    $diff = $now - $timestamp;

    if ($diff < 60) {
        return "Just now";
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . " day" . ($days > 1 ? "s" : "") . " ago";
    } elseif ($diff < 2592000) {
        $weeks = floor($diff / 604800);
        return $weeks . " week" . ($weeks > 1 ? "s" : "") . " ago";
    } else {
        return date("M j, Y", $timestamp);
    }
}
?>

<?php include 'footer.php'; ?>