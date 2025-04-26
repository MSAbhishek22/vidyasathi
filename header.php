<?php
// Only start session if one isn't already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check for logout message
$logout_message = null;
if (isset($_SESSION['logout_message'])) {
    $logout_message = $_SESSION['logout_message'];
    unset($_SESSION['logout_message']); // Clear the message so it doesn't show again
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'VidyaSathi') ?></title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AOS CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Main stylesheet -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav>
        <a href="index.php" class="nav-logo">Vidya<span>Sathi</span></a>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#features">Features</a></li>
            <li><a href="index.php#resources">Resources</a></li>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php" class="btn btn-primary">Get Started</a></li>
            <?php else: ?>
                <li><a href="community.php">Community</a></li>
                <li><a href="chatbot.php"><i class="fas fa-robot mr-1"></i>AI Assistant</a></li>
                <li>
                    <?php
                    // Determine dashboard link based on user role
                    $dashboard_link = "dashboard.php"; // Default dashboard
                    if (isset($_SESSION['user_role'])) {
                        if ($_SESSION['user_role'] === 'admin') {
                            $dashboard_link = "admin_dashboard.php";
                        } elseif ($_SESSION['user_role'] === 'moderator') {
                            $dashboard_link = "moderator_dashboard.php";
                        } elseif ($_SESSION['user_role'] === 'student') {
                            $dashboard_link = "student_dashboard.php";
                        } elseif ($_SESSION['user_role'] === 'senior') {
                            $dashboard_link = "senior_dashboard.php";
                        }
                    }
                    ?>
                    <a href="<?= $dashboard_link ?>">Dashboard</a>
                </li>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <?php if ($logout_message): ?>
        <div class="bg-green-500 text-white p-4 text-center">
            <?= htmlspecialchars($logout_message) ?>
        </div>
    <?php endif; ?>
</body>

</html>