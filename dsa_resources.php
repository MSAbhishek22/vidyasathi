<?php
session_start();

// Set page title and include header
$page_title = 'Data Structures & Algorithms Resources';
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
                <h1 class="text-3xl font-bold mb-4">Data Structures & Algorithms</h1>
                <div class="flex items-center mb-4">
                    <span class="text-primary text-xl mr-2">4.9 ★</span>
                    <span class="text-gray-400">• 120 students</span>
                </div>
                <p class="text-gray-300 text-lg mb-6">Master the fundamentals of DSA with comprehensive notes and
                    practice problems.</p>

                <div class="bg-dark-accent p-4 rounded-lg border border-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-primary text-xl mr-3"></i>
                        <p>Choose one of the resource options below to strengthen your DSA knowledge. We recommend
                            starting with the PDF guides for theory and then exploring the video tutorials for
                            implementation details.</p>
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
                            <p class="text-gray-400">Comprehensive guides and practice problems</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>Complete theory on arrays, linked lists, trees, graphs & more</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>Big O notation and algorithm complexity analysis</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>100+ practice problems with detailed solutions</p>
                        </div>
                    </div>

                    <a href="resource_viewer.php?file=DSA-Decoded.pdf" class="btn btn-primary w-full">
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
                            <p class="text-gray-400">Visual explanations and coding examples</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>Visual explanations of complex data structures</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>Step-by-step algorithm implementations</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <p>Competitive programming techniques and tips</p>
                        </div>
                    </div>

                    <a href="https://youtube.com/playlist?list=PLfqMhTWNBTe137I_EPQd34TsgV6IO55pt&si=G2itsR9LK0IK4nF9"
                        target="_blank" class="btn btn-primary w-full">
                        <i class="fab fa-youtube mr-2"></i> View YouTube Tutorials
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Resources -->
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 mt-8">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Additional DSA Resources</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="#"
                        class="flex items-center p-4 bg-dark rounded-lg border border-gray-700 hover:border-primary transition-all">
                        <i class="fas fa-laptop-code text-2xl text-primary mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Coding Challenges</h3>
                            <p class="text-sm text-gray-400">Practice problems to test your skills</p>
                        </div>
                    </a>

                    <a href="#"
                        class="flex items-center p-4 bg-dark rounded-lg border border-gray-700 hover:border-primary transition-all">
                        <i class="fas fa-project-diagram text-2xl text-primary mr-4"></i>
                        <div>
                            <h3 class="font-semibold">DSA Projects</h3>
                            <p class="text-sm text-gray-400">Real-world applications of algorithms</p>
                        </div>
                    </a>

                    <a href="#"
                        class="flex items-center p-4 bg-dark rounded-lg border border-gray-700 hover:border-primary transition-all">
                        <i class="fas fa-users text-2xl text-primary mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Study Groups</h3>
                            <p class="text-sm text-gray-400">Join peers to solve problems together</p>
                        </div>
                    </a>

                    <a href="#"
                        class="flex items-center p-4 bg-dark rounded-lg border border-gray-700 hover:border-primary transition-all">
                        <i class="fas fa-book text-2xl text-primary mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Interview Prep</h3>
                            <p class="text-sm text-gray-400">Prepare for technical interviews</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>