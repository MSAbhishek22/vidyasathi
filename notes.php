<?php
session_start();

// Set page title and include header
$page_title = 'Study Notes - VidyaSathi';
include 'header.php';

// Directory to scan for notes
$notesDir = 'uploads';
$notes = [];

// Get all files from the directory
if (is_dir($notesDir)) {
    $files = scandir($notesDir);

    foreach ($files as $file) {
        // Skip directories and hidden files
        if ($file == '.' || $file == '..' || is_dir($notesDir . '/' . $file)) {
            continue;
        }

        // Get file extension
        $fileInfo = pathinfo($file);
        $extension = strtolower($fileInfo['extension'] ?? '');

        // Create file info
        $fileSize = filesize($notesDir . '/' . $file);
        $fileDate = filemtime($notesDir . '/' . $file);

        // Get icon based on file type
        $icon = 'fa-file';
        $color = 'text-gray-400';

        if ($extension == 'pdf') {
            $icon = 'fa-file-pdf';
            $color = 'text-red-500';
        } elseif (in_array($extension, ['doc', 'docx'])) {
            $icon = 'fa-file-word';
            $color = 'text-blue-500';
        } elseif (in_array($extension, ['xls', 'xlsx'])) {
            $icon = 'fa-file-excel';
            $color = 'text-green-500';
        } elseif (in_array($extension, ['ppt', 'pptx'])) {
            $icon = 'fa-file-powerpoint';
            $color = 'text-orange-500';
        } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $icon = 'fa-file-image';
            $color = 'text-purple-500';
        } elseif (in_array($extension, ['zip', 'rar', '7z'])) {
            $icon = 'fa-file-archive';
            $color = 'text-yellow-500';
        } elseif ($extension == 'txt') {
            $icon = 'fa-file-alt';
            $color = 'text-blue-400';
        }

        $notes[] = [
            'name' => $file,
            'path' => $notesDir . '/' . $file,
            'size' => $fileSize,
            'date' => $fileDate,
            'extension' => $extension,
            'icon' => $icon,
            'color' => $color
        ];
    }
}
?>

<div class="py-8"></div>

<!-- Notes Header Section -->
<section class="bg-gradient-to-r from-primary to-blue-700 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Study Notes</h1>
            <p class="text-xl text-white opacity-90">Access comprehensive notes to enhance your learning experience</p>
        </div>
    </div>
</section>

<!-- Notes Content -->
<section class="py-16 bg-dark">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Search and Filters -->
            <div class="bg-card-bg rounded-lg p-6 mb-8 shadow-lg border border-gray-800">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-grow">
                        <input type="text" id="search-notes" placeholder="Search notes by filename..."
                            class="w-full p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <select id="filter-type"
                            class="p-3 bg-dark border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="all">All Types</option>
                            <option value="pdf">PDF</option>
                            <option value="doc">Word</option>
                            <option value="ppt">PowerPoint</option>
                            <option value="xls">Excel</option>
                            <option value="txt">Text</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Notes List -->
            <div class="bg-card-bg rounded-lg overflow-hidden shadow-lg border border-gray-800">
                <div class="p-6 bg-dark border-b border-gray-800">
                    <h2 class="text-xl font-bold">Available Notes</h2>
                </div>

                <?php if (empty($notes)): ?>
                    <div class="p-12 text-center">
                        <i class="fas fa-file-alt text-gray-600 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">No Notes Available</h3>
                        <p class="text-gray-400">Check back later for study materials.</p>
                    </div>
                <?php else: ?>
                    <div id="notes-container" class="divide-y divide-gray-800">
                        <?php foreach ($notes as $note): ?>
                            <div class="note-item p-4 hover:bg-dark transition-colors"
                                data-extension="<?php echo $note['extension']; ?>">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <i class="fas <?php echo $note['icon']; ?> text-4xl <?php echo $note['color']; ?>"></i>
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($note['name']); ?></h3>
                                        <div class="flex flex-wrap items-center text-xs text-gray-400 mt-1">
                                            <span class="mr-3"><i class="fas fa-calendar mr-1"></i>
                                                <?php echo date('M d, Y', $note['date']); ?></span>
                                            <span><i class="fas fa-weight-hanging mr-1"></i>
                                                <?php echo formatFileSize($note['size']); ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="<?php echo htmlspecialchars($note['path']); ?>" target="_blank"
                                            class="inline-flex items-center justify-center p-2 bg-primary text-white rounded-full hover:bg-primary-dark transition-colors"
                                            title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo htmlspecialchars($note['path']); ?>" download
                                            class="inline-flex items-center justify-center p-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors ml-2"
                                            title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Resources Info -->
            <div class="bg-dark-accent rounded-lg p-4 mt-8 border border-gray-700">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-primary text-xl mt-1 mr-3"></i>
                    <div>
                        <h3 class="font-bold mb-1">About These Resources</h3>
                        <p class="text-sm text-gray-300">These notes are provided to help you in your studies. You can
                            view them online or download for offline use. If you need additional resources or have any
                            specific requests, please contact us.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Search and filter functionality
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-notes');
        const filterType = document.getElementById('filter-type');
        const notesContainer = document.getElementById('notes-container');
        const noteItems = document.querySelectorAll('.note-item');

        function filterNotes() {
            const searchTerm = searchInput.value.toLowerCase();
            const fileType = filterType.value;

            noteItems.forEach(item => {
                const title = item.querySelector('h3').textContent.toLowerCase();
                const extension = item.dataset.extension;

                const matchesSearch = title.includes(searchTerm);
                const matchesType = fileType === 'all' || extension.includes(fileType);

                if (matchesSearch && matchesType) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterNotes);
        filterType.addEventListener('change', filterNotes);
    });
</script>

<?php
// Helper function to format file size
function formatFileSize($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));

    return round($bytes, 2) . ' ' . $units[$pow];
}

include 'footer.php';
?>