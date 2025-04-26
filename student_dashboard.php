<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'config.php';

// Set page title and include global header
$page_title = 'Student Dashboard';
include 'header.php';
?>

<div class="py-6"></div>

<!-- Dashboard Header -->
<section class="bg-accent py-12 px-4">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-card-bg rounded-lg p-8 shadow-lg">
            <div class="flex items-center justify-between flex-wrap">
                <div>
                    <h1 class="text-3xl font-bold text-primary">Welcome, <?php echo $_SESSION['user_name']; ?></h1>
                    <p class="text-gray-400 mt-2">Your personalized academic hub</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <span class="bg-primary text-dark text-sm font-semibold py-1 px-3 rounded-full">Student
                        Account</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dashboard Content -->
<section class="py-12">
    <div class="container mx-auto max-w-6xl px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-card-bg rounded-lg overflow-hidden shadow-lg">
                    <div class="p-4 bg-dark border-b border-gray-800">
                        <h3 class="text-xl font-semibold">Student Tools</h3>
                    </div>
                    <div class="p-4">
                        <ul class="space-y-2">
                            <li>
                                <a href="view_notes.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-book-open text-primary mr-3"></i>
                                    <span>View Notes</span>
                                </a>
                            </li>
                            <li>
                                <a href="view_pyqs.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-file-alt text-primary mr-3"></i>
                                    <span>View PYQs</span>
                                </a>
                            </li>
                            <li>
                                <a href="view_assignments.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-clipboard-list text-primary mr-3"></i>
                                    <span>View Assignments</span>
                                </a>
                            </li>
                            <li>
                                <a href="recommended_youtube.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fab fa-youtube text-primary mr-3"></i>
                                    <span>Recommended Videos</span>
                                </a>
                            </li>
                            <li>
                                <a href="certifications.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-certificate text-primary mr-3"></i>
                                    <span>Free Certifications</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-2">
                <!-- Recent Resources -->
                <div class="bg-card-bg rounded-lg overflow-hidden shadow-lg mb-8">
                    <div class="p-4 bg-dark border-b border-gray-800">
                        <h3 class="text-xl font-semibold">Recent Resources</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-dark p-4 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-file-pdf text-primary text-2xl mr-3"></i>
                                    <div>
                                        <h4 class="font-semibold">Data Structures Notes</h4>
                                        <p class="text-sm text-gray-400">Added 2 days ago</p>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-primary btn-sm w-full">View</a>
                            </div>
                            <div class="bg-dark p-4 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-file-alt text-primary text-2xl mr-3"></i>
                                    <div>
                                        <h4 class="font-semibold">Algorithms PYQs</h4>
                                        <p class="text-sm text-gray-400">Added 3 days ago</p>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-primary btn-sm w-full">View</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-card-bg rounded-lg overflow-hidden shadow-lg">
                    <div class="p-4 bg-dark border-b border-gray-800">
                        <h3 class="text-xl font-semibold">Your Stats</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-dark p-4 rounded-lg text-center">
                                <div class="text-3xl font-bold text-primary mb-1">12</div>
                                <div class="text-sm text-gray-400">Resources Viewed</div>
                            </div>
                            <div class="bg-dark p-4 rounded-lg text-center">
                                <div class="text-3xl font-bold text-primary mb-1">5</div>
                                <div class="text-sm text-gray-400">Questions Asked</div>
                            </div>
                            <div class="bg-dark p-4 rounded-lg text-center">
                                <div class="text-3xl font-bold text-primary mb-1">3</div>
                                <div class="text-sm text-gray-400">Certifications</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>