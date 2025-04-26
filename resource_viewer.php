<?php
session_start();

// Basic security check - user must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if file parameter is provided
if (!isset($_GET['file']) || empty($_GET['file'])) {
    header("Location: index.php");
    exit();
}

// Sanitize file name to prevent directory traversal attacks
$filename = basename($_GET['file']);
$filepath = "uploads/" . $filename;

// Check if file exists
if (!file_exists($filepath)) {
    header("Location: index.php");
    exit();
}

// Get file information
$fileInfo = pathinfo($filepath);
$extension = strtolower($fileInfo['extension']);

// Set page title and include header
$page_title = 'Viewing Resource: ' . htmlspecialchars($fileInfo['filename']);
include 'header.php';

// Get file size
$filesize = filesize($filepath);
$filesize_formatted = $filesize < 1048576
    ? round($filesize / 1024, 2) . ' KB'
    : round($filesize / 1048576, 2) . ' MB';
?>

<div class="py-6"></div>

<div class="container mx-auto px-4 my-8">
    <div class="max-w-5xl mx-auto">
        <!-- Back to Resources Link -->
        <div class="mb-6 flex justify-between items-center">
            <a href="index.php#resources" class="text-primary hover:underline">
                <i class="fas fa-arrow-left mr-2"></i> Back to Resources
            </a>
            <a href="dashboard.php" class="text-primary hover:underline">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
        </div>

        <!-- Resource Information -->
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 mb-6">
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($fileInfo['filename']); ?></h1>
                <div class="flex flex-wrap gap-3 text-sm text-gray-400 mb-4">
                    <span><i class="fas fa-file-pdf mr-1"></i> PDF Document</span>
                    <span><i class="fas fa-weight mr-1"></i> <?php echo $filesize_formatted; ?></span>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="<?php echo $filepath; ?>" download class="btn btn-secondary">
                        <i class="fas fa-download mr-2"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- PDF Viewer -->
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800">
            <?php if ($extension === 'pdf'): ?>
                <div class="relative" style="padding-top: 100vh;">
                    <iframe src="<?php echo $filepath; ?>" class="absolute top-0 left-0 w-full h-full"
                        style="min-height: 800px;" frameborder="0">
                    </iframe>
                </div>
            <?php else: ?>
                <div class="p-6 text-center">
                    <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                    <h2 class="text-xl font-bold mb-2">Unsupported File Type</h2>
                    <p class="mb-4">This file type cannot be previewed in the browser.</p>
                    <a href="<?php echo $filepath; ?>" download class="btn btn-primary">
                        <i class="fas fa-download mr-2"></i> Download File Instead
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>