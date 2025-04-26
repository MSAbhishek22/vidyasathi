<?php
session_start();
$page_title = "Profile System Guide - VidyaSathi";
include 'header.php';
?>

<div class="py-6"></div>

<div class="container mx-auto px-4 my-8 max-w-4xl">
    <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800">
        <div class="p-6 bg-gradient-to-r from-primary to-secondary">
            <h1 class="text-3xl font-bold text-white">Profile System Guide</h1>
            <p class="text-white text-lg opacity-90 mt-2">Get the most out of your VidyaSathi profile</p>
        </div>

        <div class="p-6">
            <div class="prose prose-invert max-w-none">
                <p class="lead">
                    The VidyaSathi profile system has been enhanced to help you showcase your skills,
                    interests, and build meaningful connections with other students. This guide will
                    walk you through all the new features and how to use them.
                </p>

                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4 border-b border-gray-800 pb-2">Getting Started</h2>
                    <p>
                        To access your profile, click on your name in the top-right corner of any page and select
                        "My Profile" from the dropdown menu. To edit your profile, click the "Edit Profile" button
                        on your profile page.
                    </p>
                    <div class="bg-dark-accent p-4 rounded-lg border border-gray-700 my-4">
                        <div class="flex items-start">
                            <i class="fas fa-lightbulb text-yellow-400 mt-1 mr-3 text-xl"></i>
                            <div>
                                <h4 class="font-bold">Pro Tip</h4>
                                <p class="text-sm">A complete profile increases your visibility in the community and
                                    helps others connect with you based on shared interests and skills.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4 border-b border-gray-800 pb-2">Profile Photo</h2>
                    <p>
                        Add a profile photo to make your profile more personal and recognizable. You can upload JPEG,
                        PNG, or GIF images (max 5MB). Your photo will be shown on your profile, in community
                        discussions,
                        and anywhere you contribute across VidyaSathi.
                    </p>
                    <p class="text-sm text-gray-400 mt-2">
                        If you don't upload a photo, your profile will display your first initial in a colored circle.
                    </p>
                </div>

                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4 border-b border-gray-800 pb-2">Headline</h2>
                    <p>
                        Your headline is a brief description that appears right below your name. It's like your
                        professional
                        tagline that tells others about your primary focus, interests, or achievements.
                    </p>
                    <div class="bg-dark p-4 rounded-lg border border-gray-700 my-4">
                        <p class="font-bold mb-2">Examples:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Computer Science Student | Python Developer</li>
                            <li>3rd Year Electronics Engineering Student | Robotics Enthusiast</li>
                            <li>Mechanical Engineering Student | CAD Design Specialist</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4 border-b border-gray-800 pb-2">About Me</h2>
                    <p>
                        The "About Me" section allows you to write a more detailed introduction about yourself,
                        your interests, and goals. This helps others understand who you are and what you're
                        passionate about.
                    </p>
                    <p class="text-sm text-gray-400 mt-2">
                        Keep it concise but informative. Include your academic interests, projects you're working on,
                        or what you hope to achieve.
                    </p>
                </div>

                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4 border-b border-gray-800 pb-2">Skills</h2>
                    <p>
                        List your skills to showcase your expertise and help others find you when they need help
                        in those areas. Simply enter your skills separated by commas.
                    </p>
                    <div class="bg-dark p-4 rounded-lg border border-gray-700 my-4">
                        <p class="font-bold mb-2">Example:</p>
                        <p>Python, Java, Data Structures, Machine Learning, Web Development, Arduino</p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="bg-dark-accent text-primary px-3 py-1 rounded-full text-sm">Python</span>
                            <span class="bg-dark-accent text-primary px-3 py-1 rounded-full text-sm">Java</span>
                            <span class="bg-dark-accent text-primary px-3 py-1 rounded-full text-sm">Data
                                Structures</span>
                            <span class="bg-dark-accent text-primary px-3 py-1 rounded-full text-sm">Machine
                                Learning</span>
                            <span class="bg-dark-accent text-primary px-3 py-1 rounded-full text-sm">Web
                                Development</span>
                            <span class="bg-dark-accent text-primary px-3 py-1 rounded-full text-sm">Arduino</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4 border-b border-gray-800 pb-2">Social Media Links</h2>
                    <p>
                        Connect your social profiles to make networking easier. You can add links to your
                        LinkedIn, GitHub, Discord, Instagram, and Twitter/X accounts.
                    </p>
                    <div class="bg-dark-accent p-4 rounded-lg border border-gray-700 my-4">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-400 mt-1 mr-3 text-xl"></i>
                            <div>
                                <h4 class="font-bold">Privacy Note</h4>
                                <p class="text-sm">Adding social media links is optional. Only add profiles that you're
                                    comfortable sharing with the VidyaSathi community.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4 border-b border-gray-800 pb-2">Profile Privacy</h2>
                    <p>
                        Your profile is visible to all registered users of VidyaSathi. However, contact information
                        like your email is only visible to administrators. Social media links are only visible if
                        you choose to add them.
                    </p>
                </div>

                <div class="mt-10 p-6 bg-dark-accent rounded-lg border border-gray-700">
                    <h3 class="text-xl font-bold mb-4">Need Help?</h3>
                    <p>
                        If you have any questions about the profile system or encounter any issues,
                        feel free to contact the VidyaSathi team.
                    </p>
                    <a href="contact.php" class="btn btn-primary mt-4">
                        <i class="fas fa-envelope mr-2"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>