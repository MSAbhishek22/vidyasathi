<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Set page title and include global header
$page_title = 'Register - VidyaSathi';
include 'header.php';

// Include database connection
require_once 'config.php';

$error_message = '';
$success_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif (!in_array($role, ['student', 'senior'])) {
        $error_message = "Invalid role selected.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email address is already registered. Please use a different email or try logging in.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
            $insert_stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

            if ($insert_stmt->execute()) {
                // Get the new user's ID
                $user_id = $conn->insert_id;

                // Create a session for the new user (automatic login)
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = $role;

                // Redirect to home page
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Registration failed: " . $conn->error;
            }

            $insert_stmt->close();
        }

        $stmt->close();
    }
}

$conn->close();
?>

<div class="py-6"></div>

<!-- Registration Form -->
<section class="py-12">
    <div class="container mx-auto max-w-md px-4">
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 bg-dark border-b border-gray-800">
                <h1 class="text-2xl font-bold text-center">Create an Account</h1>
                <p class="text-gray-400 mt-2 text-center">Join VidyaSathi and start your learning journey</p>
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
                <form method="POST" action="" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name ?? ''); ?>"
                            required
                            class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Email Address</label>
                        <input type="email" name="email" id="email"
                            value="<?php echo htmlspecialchars($email ?? ''); ?>" required
                            class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium mb-2">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters long</p>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium mb-2">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" required
                            class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">I am a:</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label
                                class="relative flex items-center p-3 bg-dark border border-gray-700 rounded-lg cursor-pointer hover:border-primary">
                                <input type="radio" name="role" value="student" checked
                                    class="form-radio h-5 w-5 text-primary focus:ring-primary">
                                <span class="ml-2">Student</span>
                            </label>
                            <label
                                class="relative flex items-center p-3 bg-dark border border-gray-700 rounded-lg cursor-pointer hover:border-primary">
                                <input type="radio" name="role" value="senior"
                                    class="form-radio h-5 w-5 text-primary focus:ring-primary">
                                <span class="ml-2">Senior</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full btn btn-primary py-3">Create Account</button>
                    </div>

                    <div class="text-center text-sm mt-4">
                        <p>Already have an account? <a href="login.php" class="text-primary hover:underline">Log In</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<link rel="stylesheet" href="css/style.css">