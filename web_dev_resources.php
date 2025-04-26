<?php
session_start();

// Set page title and include header
$page_title = 'Web Development Resources';
include 'header.php';
?>

<div class="py-6"></div>

<div class="container mx-auto px-4 my-8">
    <div class="max-w-6xl mx-auto">
        <!-- Back to Resources Link -->
        <div class="mb-6 flex justify-between items-center">
            <a href="index.php#resources" class="text-primary hover:underline">
                <i class="fas fa-arrow-left mr-2"></i> Back to Resources
            </a>
            <a href="dashboard.php" class="text-primary hover:underline">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
        </div>

        <!-- Resource Header -->
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 mb-8">
            <div class="p-8">
                <h1 class="text-3xl font-bold mb-4">Web Development Basics</h1>
                <div class="flex items-center mb-4">
                    <span class="text-primary text-xl mr-2">4.7 ★</span>
                    <span class="text-gray-400">• 64 students</span>
                </div>
                <p class="text-gray-300 text-lg mb-6">Learn HTML, CSS, and JavaScript from scratch with hands-on
                    projects.</p>

                <div class="bg-dark-accent p-4 rounded-lg border border-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-primary text-xl mr-3"></i>
                        <p>Choose one of the resource options below to begin your web development journey. We recommend
                            starting with the PDF guides and then exploring the video tutorials for a comprehensive
                            learning experience.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resource Options -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- PDF Resources -->
            <div
                class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 hover:border-primary transition-all">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center mr-4">
                            <i class="fas fa-file-pdf text-2xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">PDF Learning Materials</h2>
                            <p class="text-gray-400">Comprehensive guides and reference materials</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>Step-by-step HTML5, CSS3 & JavaScript tutorials</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>Printable cheat sheets for quick reference</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>Hands-on coding exercises with solutions</p>
                        </div>
                    </div>

                    <a href="resource_viewer.php?file=full-stack.pdf" class="btn btn-primary w-full">
                        <i class="fas fa-book-open mr-2"></i> View PDF Resources
                    </a>
                </div>
            </div>

            <!-- Video Tutorials -->
            <div
                class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 hover:border-primary transition-all">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center mr-4">
                            <i class="fab fa-youtube text-2xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Video Tutorials</h2>
                            <p class="text-gray-400">Watch and code along with experts</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>100+ video tutorials from beginner to advanced</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>Visual demonstrations of core concepts</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>Complete projects with code-along sessions</p>
                        </div>
                    </div>

                    <a href="https://youtube.com/playlist?list=PLu0W_9lII9agq5TrH9XLIKQvv0iaF2X3w&si=r1JpY0R79cfdF6dZ"
                        target="_blank" class="btn btn-primary w-full">
                        <i class="fab fa-youtube mr-2"></i> View YouTube Tutorials
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Resources -->
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 mt-8">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Additional Resources</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="#"
                        class="flex items-center p-4 bg-dark rounded-lg border border-gray-700 hover:border-primary transition-all">
                        <i class="fas fa-laptop-code text-2xl text-primary mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Practice Exercises</h3>
                            <p class="text-sm text-gray-400">Coding challenges to test your skills</p>
                        </div>
                    </a>

                    <a href="#"
                        class="flex items-center p-4 bg-dark rounded-lg border border-gray-700 hover:border-primary transition-all">
                        <i class="fas fa-project-diagram text-2xl text-primary mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Sample Projects</h3>
                            <p class="text-sm text-gray-400">Real-world project ideas with code</p>
                        </div>
                    </a>

                    <a href="#"
                        class="flex items-center p-4 bg-dark rounded-lg border border-gray-700 hover:border-primary transition-all">
                        <i class="fas fa-users text-2xl text-primary mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Community Forum</h3>
                            <p class="text-sm text-gray-400">Discuss topics and get help</p>
                        </div>
                    </a>

                    <a href="#"
                        class="flex items-center p-4 bg-dark rounded-lg border border-gray-700 hover:border-primary transition-all">
                        <i class="fas fa-book text-2xl text-primary mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Resource Library</h3>
                            <p class="text-sm text-gray-400">More books and learning materials</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>