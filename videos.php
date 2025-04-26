<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Set page title and include header
$page_title = 'Video Tutorials - VidyaSathi';
include 'header.php';

// Define video playlists with metadata
$video_categories = [
    [
        'title' => 'Programming Fundamentals',
        'description' => 'Master the basics of programming with these comprehensive tutorials',
        'playlists' => [
            [
                'title' => 'Data Structures and Algorithms',
                'channel' => 'Apna College',
                'description' => 'Complete DSA course covering arrays, linked lists, trees, graphs and algorithms with Java implementations',
                'url' => 'https://youtube.com/playlist?list=PLfqMhTWNBTe137I_EPQd34TsgV6IO55pt&si=7gu6_xJ6UThFSyHQ',
                'lessons' => 128,
                'level' => 'Intermediate',
                'language' => 'Java',
                'image' => 'https://img.youtube.com/vi/RBSGKlAvoiM/maxresdefault.jpg'
            ],
            [
                'title' => 'Web Development Course',
                'channel' => 'CodeWithHarry',
                'description' => 'Complete web development bootcamp covering HTML, CSS, JavaScript, and more',
                'url' => 'https://youtube.com/playlist?list=PLu0W_9lII9agq5TrH9XLIKQvv0iaF2X3w&si=mbar47nWiIk10R6l',
                'lessons' => 102,
                'level' => 'Beginner to Advanced',
                'language' => 'HTML/CSS/JavaScript',
                'image' => 'https://img.youtube.com/vi/l1EssrLxt7E/maxresdefault.jpg'
            ]
        ]
    ],
    [
        'title' => 'Frontend Development',
        'description' => 'Learn modern frontend technologies and frameworks',
        'playlists' => [
            [
                'title' => 'JavaScript Algorithms and Data Structures',
                'channel' => 'freeCodeCamp',
                'description' => 'Learn JavaScript data structures and algorithms for technical interviews',
                'url' => 'https://youtube.com/playlist?list=PLWKjhJtqVAbkPz27wpMpBdNbF8wzH_lWh&si=pdvP6HyRe9o9HCJW',
                'lessons' => 26,
                'level' => 'Intermediate',
                'language' => 'JavaScript',
                'image' => 'https://img.youtube.com/vi/t2CEgPsws3U/maxresdefault.jpg'
            ],
            [
                'title' => 'JavaScript Projects Tutorial',
                'channel' => 'freeCodeCamp',
                'description' => 'Build 15 JavaScript projects that you can add to your portfolio',
                'url' => 'https://youtube.com/playlist?list=PLWKjhJtqVAbmMuZ3saqRIBimAKIMYkt0E&si=j3UkgAi3IsGr3iO0',
                'lessons' => 18,
                'level' => 'Intermediate',
                'language' => 'JavaScript',
                'image' => 'https://img.youtube.com/vi/3PHXvlpOkf4/maxresdefault.jpg'
            ]
        ]
    ],
    [
        'title' => 'Backend & Full Stack',
        'description' => 'Dive into server-side programming and database integration',
        'playlists' => [
            [
                'title' => 'Python Django Web Framework',
                'channel' => 'freeCodeCamp',
                'description' => 'Learn the Django web framework for building Python web applications',
                'url' => 'https://youtube.com/playlist?list=PLWKjhJtqVAbkPz27wpMpBdNbF8wzH_lWh&si=pdvP6HyRe9o9HCJW',
                'lessons' => 12,
                'level' => 'Intermediate',
                'language' => 'Python',
                'image' => 'https://img.youtube.com/vi/F5mRW0jo-U4/maxresdefault.jpg'
            ],
            [
                'title' => 'Bootstrap 5 Course',
                'channel' => 'freeCodeCamp',
                'description' => 'Learn to build responsive websites with Bootstrap 5',
                'url' => 'https://youtube.com/playlist?list=PLWKjhJtqVAbm5dir5TLEy2aZQMG7cHEZp&si=m2sV2DAZ7G3iRhyK',
                'lessons' => 38,
                'level' => 'Beginner',
                'language' => 'HTML/CSS',
                'image' => 'https://img.youtube.com/vi/4sosXZsdy-s/maxresdefault.jpg'
            ]
        ]
    ],
    [
        'title' => 'Mobile & Advanced Development',
        'description' => 'Take your skills to the next level with advanced programming topics',
        'playlists' => [
            [
                'title' => 'Data Visualization with D3.js',
                'channel' => 'freeCodeCamp',
                'description' => 'Learn to create interactive data visualizations for the web',
                'url' => 'https://youtube.com/playlist?list=PLWKjhJtqVAblvI1i46ScbKV2jH1gdL7VQ&si=DDOmB0xU_gfHtJQH',
                'lessons' => 16,
                'level' => 'Advanced',
                'language' => 'JavaScript',
                'image' => 'https://img.youtube.com/vi/-qfEOE4vtxE/maxresdefault.jpg'
            ],
            [
                'title' => 'Data Science Full Course',
                'channel' => 'freeCodeCamp',
                'description' => 'Learn Python, pandas, NumPy, Matplotlib, and machine learning',
                'url' => 'https://youtube.com/playlist?list=PLWKjhJtqVAblQe2CCWqV4Zy3LY01Z8aF1&si=mS7iVN9K2DXybIDa',
                'lessons' => 31,
                'level' => 'Intermediate to Advanced',
                'language' => 'Python',
                'image' => 'https://img.youtube.com/vi/ua-CiDNNj30/maxresdefault.jpg'
            ]
        ]
    ]
];
?>

<div class="py-6"></div>

<div class="container mx-auto px-4 my-8">
    <div class="max-w-6xl mx-auto">
        <!-- Page Header Banner -->
        <div class="bg-gradient-to-r from-primary to-blue-700 rounded-lg shadow-xl mb-10 overflow-hidden">
            <div class="p-8 md:p-10">
                <h1 class="text-4xl font-bold text-white mb-4">Video Tutorials</h1>
                <p class="text-white text-lg opacity-90 mb-6 max-w-3xl">Enhance your learning with high-quality video
                    courses from top instructors. Our curated collection covers everything from programming basics to
                    advanced topics.</p>

                <div class="flex flex-wrap gap-4">
                    <div class="bg-white bg-opacity-20 rounded-lg px-4 py-3 text-white flex items-center">
                        <i class="fab fa-youtube text-xl mr-3"></i>
                        <div>
                            <div class="text-2xl font-bold">300+</div>
                            <div class="text-sm opacity-90">Video Lessons</div>
                        </div>
                    </div>

                    <div class="bg-white bg-opacity-20 rounded-lg px-4 py-3 text-white flex items-center">
                        <i class="fas fa-graduation-cap text-xl mr-3"></i>
                        <div>
                            <div class="text-2xl font-bold">8</div>
                            <div class="text-sm opacity-90">Curated Playlists</div>
                        </div>
                    </div>

                    <div class="bg-white bg-opacity-20 rounded-lg px-4 py-3 text-white flex items-center">
                        <i class="fas fa-laptop-code text-xl mr-3"></i>
                        <div>
                            <div class="text-2xl font-bold">5+</div>
                            <div class="text-sm opacity-90">Programming Languages</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How To Use Section -->
        <div class="bg-dark-accent rounded-lg p-5 mb-8 border border-gray-700">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-primary text-xl mt-1 mr-4"></i>
                <div>
                    <h3 class="font-bold mb-2">How to Use These Resources</h3>
                    <p class="text-gray-300 mb-2">These video tutorials are organized by topic and skill level. Click on
                        any playlist card to be redirected to the YouTube playlist. We recommend:</p>
                    <ul class="list-disc pl-5 text-sm text-gray-300">
                        <li class="mb-1">Start with beginner-friendly courses if you're new to programming</li>
                        <li class="mb-1">Follow along by coding as you watch the videos</li>
                        <li class="mb-1">Join our community to discuss what you're learning</li>
                        <li>Complement these videos with our PDF resources for a comprehensive learning experience</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Video Categories -->
        <?php foreach ($video_categories as $category): ?>
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($category['title']); ?></h2>
                <p class="text-gray-400 mb-6"><?php echo htmlspecialchars($category['description']); ?></p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($category['playlists'] as $playlist): ?>
                        <a href="<?php echo htmlspecialchars($playlist['url']); ?>" target="_blank"
                            class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 hover:border-primary transition-all">
                            <div class="aspect-w-16 aspect-h-9 w-full">
                                <div class="bg-dark-accent h-48 w-full overflow-hidden relative">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <i class="fab fa-youtube text-5xl text-red-600"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-bold"><?php echo htmlspecialchars($playlist['title']); ?></h3>
                                    <span
                                        class="bg-primary text-white text-xs px-2 py-1 rounded-full"><?php echo htmlspecialchars($playlist['level']); ?></span>
                                </div>

                                <p class="text-sm text-gray-400 mb-3"><?php echo htmlspecialchars($playlist['channel']); ?></p>

                                <p class="text-gray-300 mb-4"><?php echo htmlspecialchars($playlist['description']); ?></p>

                                <div class="flex flex-wrap gap-4 text-sm">
                                    <div class="flex items-center text-gray-400">
                                        <i class="fas fa-play-circle mr-2"></i>
                                        <span><?php echo htmlspecialchars($playlist['lessons']); ?> lessons</span>
                                    </div>

                                    <div class="flex items-center text-gray-400">
                                        <i class="fas fa-code mr-2"></i>
                                        <span><?php echo htmlspecialchars($playlist['language']); ?></span>
                                    </div>
                                </div>

                                <div class="mt-5 pt-5 border-t border-gray-800">
                                    <div class="flex items-center justify-between">
                                        <span class="text-primary">View Playlist</span>
                                        <i class="fas fa-external-link-alt text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Request New Tutorials Section -->
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800 p-6 text-center">
            <h2 class="text-xl font-bold mb-3">Can't Find What You're Looking For?</h2>
            <p class="text-gray-400 mb-5">If you need a tutorial on a specific topic, let us know and we'll try to add
                it to our collection.</p>
            <a href="community.php" class="btn btn-primary">
                <i class="fas fa-comment-alt mr-2"></i> Request in Community
            </a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>