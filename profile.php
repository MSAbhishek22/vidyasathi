<?php
session_start();
require_once 'config.php';

// Set page title and include header
$page_title = 'Profile - VidyaSathi';
include 'header.php'; // Fix inclusion path

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user information
$user_sql = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    // User not found
    header("Location: logout.php");
    exit();
}

$user = $user_result->fetch_assoc();

// Remove courses-related queries since the tables don't exist
$total_enrolled = 0;
$total_completed = 0;
$completion_rate = 0;
$total_study_hours = 0;
$streak_days = 0;

// Process profile update if form submitted
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);

    // Validate inputs
    if (empty($name) || empty($email)) {
        $error_message = "Name and email are required fields.";
    } else {
        // Check if email already exists for another user
        $check_email_sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $check_email_stmt = $conn->prepare($check_email_sql);
        $check_email_stmt->bind_param("si", $email, $user_id);
        $check_email_stmt->execute();
        $check_email_result = $check_email_stmt->get_result();

        if ($check_email_result->num_rows > 0) {
            $error_message = "Email is already in use by another account.";
        } else {
            // Update user information
            $update_sql = "UPDATE users SET name = ?, email = ?, bio = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssi", $name, $email, $bio, $user_id);

            if ($update_stmt->execute()) {
                $success_message = "Profile updated successfully!";
                // Update session variables
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;

                // Refresh user data
                $user_stmt->execute();
                $user_result = $user_stmt->get_result();
                $user = $user_result->fetch_assoc();
            } else {
                $error_message = "Error updating profile: " . $conn->error;
            }
        }
    }
}

// Check for password change messages in session
if (isset($_SESSION['password_success'])) {
    $success_message = $_SESSION['password_success'];
    unset($_SESSION['password_success']);
}

if (isset($_SESSION['password_error'])) {
    $error_message = $_SESSION['password_error'];
    unset($_SESSION['password_error']);
}
?>

<div class="container mx-auto px-4 my-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left column: User profile -->
        <div class="lg:col-span-1">
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800">
                <div class="p-6 bg-primary text-white border-b border-gray-800">
                    <h5 class="text-xl font-bold">User Profile</h5>
                </div>
                <div class="p-6 text-center">
                    <div class="mb-6">
                        <?php if (!empty($user['profile_image']) && file_exists($user['profile_image'])): ?>
                            <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile"
                                class="rounded-full mx-auto shadow-lg border-4 border-primary"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-full bg-primary text-white d-flex align-items-center justify-content-center mx-auto shadow-lg"
                                style="width: 150px; height: 150px; font-size: 3.5rem; line-height: 150px;">
                                <?php echo strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h5 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($user['name']); ?></h5>
                    <p class="text-gray-400 mb-1"><?php echo htmlspecialchars($user['email']); ?></p>

                    <?php if (!empty($user['headline'])): ?>
                        <p class="text-gray-300 mb-2"><?php echo htmlspecialchars($user['headline']); ?></p>
                    <?php endif; ?>

                    <p class="text-gray-400 mb-4">
                        <?php echo !empty($user['bio']) ? nl2br(htmlspecialchars($user['bio'])) : 'No bio added yet'; ?>
                    </p>

                    <!-- Social Media Links -->
                    <?php if (
                        !empty($user['linkedin']) || !empty($user['github']) || !empty($user['discord']) ||
                        !empty($user['instagram']) || !empty($user['twitter'])
                    ): ?>
                        <div class="flex justify-center space-x-3 mb-6">
                            <?php if (!empty($user['linkedin'])): ?>
                                <a href="<?php echo htmlspecialchars($user['linkedin']); ?>" target="_blank"
                                    class="text-blue-500 hover:text-blue-400 transition-colors" title="LinkedIn">
                                    <i class="fab fa-linkedin fa-lg"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($user['github'])): ?>
                                <a href="<?php echo htmlspecialchars($user['github']); ?>" target="_blank"
                                    class="text-gray-400 hover:text-white transition-colors" title="GitHub">
                                    <i class="fab fa-github fa-lg"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($user['discord'])): ?>
                                <a href="#" onclick="alert('Discord: <?php echo htmlspecialchars($user['discord']); ?>')"
                                    class="text-indigo-400 hover:text-indigo-300 transition-colors"
                                    title="Discord: <?php echo htmlspecialchars($user['discord']); ?>">
                                    <i class="fab fa-discord fa-lg"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($user['instagram'])): ?>
                                <a href="https://instagram.com/<?php echo htmlspecialchars($user['instagram']); ?>"
                                    target="_blank" class="text-pink-500 hover:text-pink-400 transition-colors"
                                    title="Instagram">
                                    <i class="fab fa-instagram fa-lg"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($user['twitter'])): ?>
                                <a href="https://twitter.com/<?php echo htmlspecialchars($user['twitter']); ?>" target="_blank"
                                    class="text-blue-400 hover:text-blue-300 transition-colors" title="Twitter/X">
                                    <i class="fab fa-twitter fa-lg"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-center space-x-2">
                        <a href="edit_profile.php" class="btn btn-primary">
                            <i class="fas fa-edit mr-2"></i> Edit Profile
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#changePasswordModal">
                            <i class="fas fa-key mr-2"></i> Change Password
                        </button>
                    </div>
                </div>
            </div>

            <!-- Skills Section (if available) -->
            <?php if (!empty($user['skills'])): ?>
                <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 mt-6">
                    <div class="p-4 bg-dark border-b border-gray-800">
                        <h3 class="text-xl font-bold">Skills</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-wrap gap-2">
                            <?php
                            $skills = explode(',', $user['skills']);
                            foreach ($skills as $skill):
                                $skill = trim($skill);
                                if (!empty($skill)):
                                    ?>
                                    <span class="bg-dark-accent text-primary px-3 py-1 rounded-full text-sm">
                                        <?php echo htmlspecialchars($skill); ?>
                                    </span>
                                <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right column: User Activity -->
        <div class="lg:col-span-2">
            <!-- Alert messages -->
            <?php if (!empty($success_message)): ?>
                <div class="bg-green-600 text-white p-4 rounded-lg mb-4" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="bg-red-600 text-white p-4 rounded-lg mb-4" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- User Activity -->
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800">
                <div class="p-6 bg-primary text-white border-b border-gray-800">
                    <h5 class="text-xl font-bold">Recent Activity</h5>
                </div>
                <div class="p-6">
                    <p class="text-gray-400">Your recent activities will appear here.</p>
                    <a href="community.php" class="btn btn-primary mt-4">
                        <i class="fas fa-users mr-2"></i> Visit Community
                    </a>
                </div>
            </div>

            <!-- User Uploads -->
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 mt-6">
                <div class="p-6 bg-primary text-white border-b border-gray-800">
                    <h5 class="text-xl font-bold">Your Uploads</h5>
                </div>
                <div class="p-6">
                    <p class="text-gray-400">Your uploaded resources will appear here.</p>
                    <a href="upload.php" class="btn btn-primary mt-4">
                        <i class="fas fa-upload mr-2"></i> Upload Resource
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-card-bg border border-gray-800">
            <div class="modal-header bg-dark border-b border-gray-800">
                <h5 class="modal-title text-white" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="change_password.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control bg-dark border border-gray-700 text-white"
                            id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control bg-dark border border-gray-700 text-white"
                            id="new_password" name="new_password" required>
                        <div class="form-text text-gray-400">Password must be at least 8 characters long</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control bg-dark border border-gray-700 text-white"
                            id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer bg-dark border-t border-gray-800">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>