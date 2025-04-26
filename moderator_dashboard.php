<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'moderator') {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'config.php';

// Set page title and include global header
$page_title = 'Moderator Dashboard';
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
                    <p class="text-gray-400 mt-2">Keep the community clean and helpful</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <span class="bg-primary text-dark text-sm font-semibold py-1 px-3 rounded-full">Moderator
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
                        <h3 class="text-xl font-semibold">Moderator Tools</h3>
                    </div>
                    <div class="p-4">
                        <ul class="space-y-2">
                            <li>
                                <a href="review_uploads.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-check-circle text-primary mr-3"></i>
                                    <span>Review Student Uploads</span>
                                </a>
                            </li>
                            <li>
                                <a href="manage_discussions.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-comments text-primary mr-3"></i>
                                    <span>Manage Discussions</span>
                                </a>
                            </li>
                            <li>
                                <a href="handle_reports.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-flag text-primary mr-3"></i>
                                    <span>Handle Reports</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Activity Summary -->
                <div class="bg-card-bg rounded-lg overflow-hidden shadow-lg mt-8">
                    <div class="p-4 bg-dark border-b border-gray-800">
                        <h3 class="text-xl font-semibold">Activity Summary</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span>Uploads Reviewed</span>
                                <span class="text-primary font-semibold">28</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Posts Moderated</span>
                                <span class="text-primary font-semibold">16</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Reports Resolved</span>
                                <span class="text-primary font-semibold">9</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-2">
                <!-- Pending Reviews -->
                <div class="bg-card-bg rounded-lg overflow-hidden shadow-lg mb-8">
                    <div class="p-4 bg-dark border-b border-gray-800 flex justify-between items-center">
                        <h3 class="text-xl font-semibold">Pending Reviews</h3>
                        <span class="bg-red-500 text-white text-xs py-1 px-2 rounded">3 new</span>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            <div class="bg-dark p-4 rounded-lg">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-primary text-2xl mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold">Computer Networks Notes</h4>
                                            <p class="text-sm text-gray-400">Uploaded by Rahul S. • 1 day ago</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="#" class="btn btn-primary btn-sm">Review</a>
                                    <a href="#" class="btn btn-secondary btn-sm">Details</a>
                                </div>
                            </div>
                            <div class="bg-dark p-4 rounded-lg">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-alt text-primary text-2xl mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold">Database Management PYQs</h4>
                                            <p class="text-sm text-gray-400">Uploaded by Priya K. • 2 days ago</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="#" class="btn btn-primary btn-sm">Review</a>
                                    <a href="#" class="btn btn-secondary btn-sm">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-card-bg rounded-lg overflow-hidden shadow-lg">
                    <div class="p-4 bg-dark border-b border-gray-800">
                        <h3 class="text-xl font-semibold">Recent Activity</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            <div class="bg-dark p-4 rounded-lg flex items-center">
                                <div class="w-2 h-2 rounded-full bg-green-500 mr-3"></div>
                                <span>You approved <strong>Operating Systems Notes</strong> • 1 hour ago</span>
                            </div>
                            <div class="bg-dark p-4 rounded-lg flex items-center">
                                <div class="w-2 h-2 rounded-full bg-red-500 mr-3"></div>
                                <span>You rejected <strong>Incomplete Data Structures Notes</strong> • 3 hours
                                    ago</span>
                            </div>
                            <div class="bg-dark p-4 rounded-lg flex items-center">
                                <div class="w-2 h-2 rounded-full bg-yellow-500 mr-3"></div>
                                <span>You flagged a discussion for <strong>inappropriate content</strong> • 1 day
                                    ago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>