<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Set page title and include global header
$page_title = 'Login - VidyaSathi';
include 'header.php';

// Include database connection
require_once 'config.php';

$error_message = '';

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        $error_message = "Please enter both email and password.";
    } else {
        // Check user credentials
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, create session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                header("Location: dashboard.php");
                exit();
            } else {
                $error_message = "Invalid email or password.";
            }
        } else {
            $error_message = "Invalid email or password.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<div class="py-6"></div>

<!-- Login Form -->
<section class="py-12">
    <div class="container mx-auto max-w-md px-4">
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 bg-dark border-b border-gray-800">
                <h1 class="text-2xl font-bold text-center">Welcome Back</h1>
                <p class="text-gray-400 mt-2 text-center">Log in to your VidyaSathi account</p>
            </div>

            <?php if ($error_message): ?>
                <div class="bg-red-600 text-white p-4">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="p-6">
                <form method="POST" action="" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Email Address</label>
                        <input type="email" name="email" id="email"
                            value="<?php echo htmlspecialchars($email ?? ''); ?>" required
                            class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-medium">Password</label>
                            <a href="forgot_password.php" class="text-sm text-primary hover:underline">Forgot
                                Password?</a>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                            class="h-4 w-4 rounded border-gray-700 text-primary focus:ring-primary">
                        <label for="remember" class="ml-2 block text-sm">Remember me</label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full btn btn-primary py-3">Log In</button>
                    </div>

                    <div class="text-center text-sm mt-4">
                        <p>Don't have an account? <a href="register.php" class="text-primary hover:underline">Create
                                Account</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<link rel="stylesheet" href="css/style.css">