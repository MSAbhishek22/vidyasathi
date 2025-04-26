<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Set page title and include global header
$page_title = 'Edit Profile';
include 'header.php';

// Include database connection
require_once 'config.php';

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $headline = trim($_POST['headline'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');
    $github = trim($_POST['github'] ?? '');
    $discord = trim($_POST['discord'] ?? '');
    $instagram = trim($_POST['instagram'] ?? '');
    $twitter = trim($_POST['twitter'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (empty($name) || empty($email)) {
        $error_message = "Name and email are required fields.";
    } else {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Check if email already exists (for another user)
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $check_stmt->bind_param("si", $email, $user_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $error_message = "Email address is already in use by another account.";
                $check_stmt->close();
                $conn->rollback();
            } else {
                $check_stmt->close();

                // Process profile image upload if provided
                $profile_image = null;
                if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                    $max_size = 5 * 1024 * 1024; // 5MB

                    $file_type = $_FILES['profile_image']['type'];
                    $file_size = $_FILES['profile_image']['size'];

                    if (!in_array($file_type, $allowed_types)) {
                        $error_message = "Only JPG, PNG and GIF images are allowed.";
                        $conn->rollback();
                    } elseif ($file_size > $max_size) {
                        $error_message = "Image size should be less than 5MB.";
                        $conn->rollback();
                    } else {
                        // Create directory if it doesn't exist
                        $upload_dir = 'uploads/profiles/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }

                        $file_name = time() . '_' . $_FILES['profile_image']['name'];
                        $destination = $upload_dir . $file_name;

                        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination)) {
                            $profile_image = $destination;

                            // Delete old profile image if exists
                            $old_image_stmt = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
                            $old_image_stmt->bind_param("i", $user_id);
                            $old_image_stmt->execute();
                            $old_image_result = $old_image_stmt->get_result();
                            $old_image_data = $old_image_result->fetch_assoc();
                            $old_image_stmt->close();

                            if (!empty($old_image_data['profile_image']) && file_exists($old_image_data['profile_image'])) {
                                unlink($old_image_data['profile_image']);
                            }
                        } else {
                            $error_message = "Failed to upload image.";
                            $conn->rollback();
                        }
                    }
                }

                // Update user information
                $update_sql = "UPDATE users SET 
                    name = ?, 
                    email = ?, 
                    headline = ?, 
                    bio = ?, 
                    skills = ?, 
                    linkedin = ?, 
                    github = ?, 
                    discord = ?, 
                    instagram = ?, 
                    twitter = ?";

                // Add profile image to update if uploaded
                $params = [$name, $email, $headline, $bio, $skills, $linkedin, $github, $discord, $instagram, $twitter];
                $types = "ssssssssss";

                if ($profile_image) {
                    $update_sql .= ", profile_image = ?";
                    $params[] = $profile_image;
                    $types .= "s";
                }

                $update_sql .= " WHERE id = ?";
                $params[] = $user_id;
                $types .= "i";

                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param($types, ...$params);
                $update_stmt->execute();
                $update_stmt->close();

                // Change password if requested
                if (!empty($new_password) && !empty($current_password)) {
                    // Verify current password
                    $pwd_stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
                    $pwd_stmt->bind_param("i", $user_id);
                    $pwd_stmt->execute();
                    $pwd_result = $pwd_stmt->get_result();
                    $user_data = $pwd_result->fetch_assoc();
                    $pwd_stmt->close();

                    if (password_verify($current_password, $user_data['password'])) {
                        // Validate new password
                        if (strlen($new_password) < 8) {
                            $error_message = "New password must be at least 8 characters long.";
                            $conn->rollback();
                        } elseif ($new_password !== $confirm_password) {
                            $error_message = "New password and confirmation do not match.";
                            $conn->rollback();
                        } else {
                            // Update password
                            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                            $pwd_update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                            $pwd_update_stmt->bind_param("si", $hashed_password, $user_id);
                            $pwd_update_stmt->execute();
                            $pwd_update_stmt->close();

                            $success_message = "Profile updated successfully with new password.";
                            $conn->commit();
                        }
                    } else {
                        $error_message = "Current password is incorrect.";
                        $conn->rollback();
                    }
                } else {
                    $success_message = "Profile updated successfully.";
                    $conn->commit();
                }

                // Update session variables
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
            }
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "An error occurred: " . $e->getMessage();
        }
    }
}

// Get current user data
$stmt = $conn->prepare("SELECT name, email, bio, profile_image, headline, skills, linkedin, github, discord, instagram, twitter FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<div class="py-6"></div>

<!-- Edit Profile Form -->
<section class="py-12">
    <div class="container mx-auto max-w-4xl px-4">
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 bg-dark border-b border-gray-800">
                <h1 class="text-2xl font-bold">Edit Profile</h1>
                <p class="text-gray-400 mt-2">Update your personal information</p>
            </div>

            <?php if ($success_message): ?>
                <div class="bg-green-600 text-white p-4">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="bg-red-600 text-white p-4">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="p-6">
                <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                    <!-- Profile Photo -->
                    <div class="space-y-4 border-b border-gray-800 pb-6">
                        <h2 class="text-xl font-semibold mb-4">Profile Photo</h2>

                        <div class="flex items-center space-x-6">
                            <div class="w-32 h-32 overflow-hidden rounded-full bg-dark">
                                <?php if (!empty($user['profile_image']) && file_exists($user['profile_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Photo"
                                        class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-primary text-white text-4xl">
                                        <?php echo strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Change Profile Photo</label>
                                <div class="flex flex-col space-y-2">
                                    <label for="profile_image"
                                        class="flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 transition-colors duration-200 rounded-lg cursor-pointer border-2 border-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-white font-medium">Choose Photo</span>
                                    </label>
                                    <input id="profile_image" type="file" name="profile_image"
                                        accept="image/jpeg, image/png, image/gif" class="hidden"
                                        onchange="updateFileName(this)">
                                    <div id="file-selected" class="text-sm text-blue-400 mt-1"></div>
                                    <p class="text-xs text-gray-300 mt-1">Max size: 5MB. Allowed formats: JPG, PNG, GIF
                                    </p>
                                </div>
                                <script>
                                    function updateFileName(input) {
                                        const fileSelected = document.getElementById('file-selected');
                                        if (input.files && input.files[0]) {
                                            fileSelected.textContent = 'Selected: ' + input.files[0].name;
                                        } else {
                                            fileSelected.textContent = '';
                                        }
                                    }
                                </script>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="space-y-4 border-b border-gray-800 pb-6">
                        <h2 class="text-xl font-semibold mb-4">Basic Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium mb-2">Full Name</label>
                                <input type="text" name="name" id="name"
                                    value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium mb-2">Email Address</label>
                                <input type="email" name="email" id="email"
                                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div class="md:col-span-2">
                                <label for="headline" class="block text-sm font-medium mb-2">Professional
                                    Headline</label>
                                <input type="text" name="headline" id="headline"
                                    value="<?php echo htmlspecialchars($user['headline'] ?? ''); ?>"
                                    placeholder="e.g., Computer Science Student | Web Developer"
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <p class="text-xs text-gray-500 mt-1">A brief title that describes who you are</p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="bio" class="block text-sm font-medium mb-2">About Me</label>
                                <textarea name="bio" id="bio" rows="4"
                                    placeholder="Write a short introduction about yourself"
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label for="skills" class="block text-sm font-medium mb-2">Skills</label>
                                <textarea name="skills" id="skills" rows="2"
                                    placeholder="e.g., Python, C, Web Development, Java, Data Structures"
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"><?php echo htmlspecialchars($user['skills'] ?? ''); ?></textarea>
                                <p class="text-xs text-gray-500 mt-1">List your skills separated by commas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="space-y-4 border-b border-gray-800 pb-6">
                        <h2 class="text-xl font-semibold mb-4">Social Media Links</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="linkedin" class="block text-sm font-medium mb-2">
                                    <i class="fab fa-linkedin text-blue-500 mr-1"></i> LinkedIn
                                </label>
                                <input type="url" name="linkedin" id="linkedin"
                                    value="<?php echo htmlspecialchars($user['linkedin'] ?? ''); ?>"
                                    placeholder="https://linkedin.com/in/yourusername"
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div>
                                <label for="github" class="block text-sm font-medium mb-2">
                                    <i class="fab fa-github text-gray-400 mr-1"></i> GitHub
                                </label>
                                <input type="url" name="github" id="github"
                                    value="<?php echo htmlspecialchars($user['github'] ?? ''); ?>"
                                    placeholder="https://github.com/yourusername"
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div>
                                <label for="discord" class="block text-sm font-medium mb-2">
                                    <i class="fab fa-discord text-indigo-400 mr-1"></i> Discord
                                </label>
                                <input type="text" name="discord" id="discord"
                                    value="<?php echo htmlspecialchars($user['discord'] ?? ''); ?>"
                                    placeholder="username#0000"
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div>
                                <label for="instagram" class="block text-sm font-medium mb-2">
                                    <i class="fab fa-instagram text-pink-500 mr-1"></i> Instagram
                                </label>
                                <input type="text" name="instagram" id="instagram"
                                    value="<?php echo htmlspecialchars($user['instagram'] ?? ''); ?>"
                                    placeholder="yourusername"
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div>
                                <label for="twitter" class="block text-sm font-medium mb-2">
                                    <i class="fab fa-twitter text-blue-400 mr-1"></i> X / Twitter
                                </label>
                                <input type="text" name="twitter" id="twitter"
                                    value="<?php echo htmlspecialchars($user['twitter'] ?? ''); ?>"
                                    placeholder="yourusername"
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                    </div>

                    <!-- Change Password Section -->
                    <div class="space-y-4 border-b border-gray-800 pb-6">
                        <h2 class="text-xl font-semibold mb-4">Change Password</h2>
                        <p class="text-sm text-gray-400 mb-4">Leave blank if you don't want to change your password</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium mb-2">Current
                                    Password</label>
                                <input type="password" name="current_password" id="current_password"
                                    class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="new_password" class="block text-sm font-medium mb-2">New
                                        Password</label>
                                    <input type="password" name="new_password" id="new_password"
                                        class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters long</p>
                                </div>

                                <div>
                                    <label for="confirm_password" class="block text-sm font-medium mb-2">Confirm New
                                        Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password"
                                        class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4">
                        <a href="profile.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
$conn->close();
include 'footer.php';
?>