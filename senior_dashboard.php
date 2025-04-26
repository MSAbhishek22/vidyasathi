<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'senior') {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'config.php';

// Set page title and include global header
$page_title = 'Senior Dashboard';
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
                    <p class="text-gray-400 mt-2">Share your experience. Be the senior you wanted!</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <span class="bg-primary text-dark text-sm font-semibold py-1 px-3 rounded-full">Senior
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
                        <h3 class="text-xl font-semibold">Senior Tools</h3>
                    </div>
                    <div class="p-4">
                        <ul class="space-y-2">
                            <li>
                                <a href="upload_note.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-upload text-primary mr-3"></i>
                                    <span>Upload Notes</span>
                                </a>
                            </li>
                            <li>
                                <a href="upload_pyqs.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-file-upload text-primary mr-3"></i>
                                    <span>Upload PYQs</span>
                                </a>
                            </li>
                            <li>
                                <a href="create_polls.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-poll text-primary mr-3"></i>
                                    <span>Create Polls</span>
                                </a>
                            </li>
                            <li>
                                <a href="mentor_requests.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-user-graduate text-primary mr-3"></i>
                                    <span>Handle Mentorship Requests</span>
                                </a>
                            </li>
                            <li>
                                <a href="my_uploads.php"
                                    class="flex items-center p-3 rounded-lg hover:bg-dark transition-colors">
                                    <i class="fas fa-folder-open text-primary mr-3"></i>
                                    <span>My Uploads</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-2">
                <!-- Upload Stats -->
                <div class="bg-card-bg rounded-lg overflow-hidden shadow-lg mb-8">
                    <div class="p-4 bg-dark border-b border-gray-800">
                        <h3 class="text-xl font-semibold">Your Contribution Stats</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-dark p-4 rounded-lg text-center">
                                <div class="text-3xl font-bold text-primary mb-1">8</div>
                                <div class="text-sm text-gray-400">Notes Uploaded</div>
                            </div>
                            <div class="bg-dark p-4 rounded-lg text-center">
                                <div class="text-3xl font-bold text-primary mb-1">15</div>
                                <div class="text-sm text-gray-400">PYQs Shared</div>
                            </div>
                            <div class="bg-dark p-4 rounded-lg text-center">
                                <div class="text-3xl font-bold text-primary mb-1">23</div>
                                <div class="text-sm text-gray-400">Students Helped</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Uploads -->
                <div class="bg-card-bg rounded-lg overflow-hidden shadow-lg">
                    <div class="p-4 bg-dark border-b border-gray-800">
                        <h3 class="text-xl font-semibold">Recent Uploads</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            <div class="bg-dark p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-primary text-2xl mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold">Database Management Notes</h4>
                                            <p class="text-sm text-gray-400">Uploaded 3 days ago</p>
                                        </div>
                                    </div>
                                    <span class="bg-green-600 text-white text-xs py-1 px-2 rounded">Approved</span>
                                </div>
                            </div>
                            <div class="bg-dark p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-alt text-primary text-2xl mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold">Operating Systems PYQs</h4>
                                            <p class="text-sm text-gray-400">Uploaded 1 week ago</p>
                                        </div>
                                    </div>
                                    <span class="bg-green-600 text-white text-xs py-1 px-2 rounded">Approved</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>