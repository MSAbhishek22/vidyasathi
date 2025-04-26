<?php
session_start();
$page_title = 'Terms of Service - VidyaSathi';
include 'header.php';
?>

<div class="py-8"></div>

<!-- Terms of Service Header -->
<section class="bg-gradient-to-r from-primary to-purple-700 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Terms of Service</h1>
            <p class="text-xl text-white opacity-90">Last Updated: <?php echo date('F d, Y'); ?></p>
        </div>
    </div>
</section>

<!-- Terms of Service Content -->
<section class="py-16 bg-dark">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-card-bg rounded-lg p-8 shadow-lg border border-gray-800">

            <!-- Table of Contents -->
            <div class="mb-10 p-5 bg-gray-900 rounded-lg border border-gray-700">
                <h3 class="text-lg font-bold mb-4 text-primary">Table of Contents</h3>
                <ol class="list-decimal list-inside space-y-2 text-gray-300">
                    <li><a href="#agreement" class="hover:text-primary transition-colors">Agreement to Terms</a></li>
                    <li><a href="#license" class="hover:text-primary transition-colors">Use License</a></li>
                    <li><a href="#accounts" class="hover:text-primary transition-colors">User Accounts</a></li>
                    <li><a href="#content" class="hover:text-primary transition-colors">User Content</a></li>
                    <li><a href="#prohibited" class="hover:text-primary transition-colors">Prohibited Activities</a>
                    </li>
                    <li><a href="#termination" class="hover:text-primary transition-colors">Termination</a></li>
                    <li><a href="#liability" class="hover:text-primary transition-colors">Limitation of Liability</a>
                    </li>
                    <li><a href="#changes" class="hover:text-primary transition-colors">Changes to Terms</a></li>
                    <li><a href="#law" class="hover:text-primary transition-colors">Governing Law</a></li>
                    <li><a href="#contact" class="hover:text-primary transition-colors">Contact Us</a></li>
                </ol>
            </div>

            <div class="prose prose-invert max-w-none">
                <!-- Agreement to Terms Section -->
                <div id="agreement" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-handshake mr-3"></i>Agreement to Terms
                    </h2>
                    <div class="bg-gray-800 p-5 rounded-lg">
                        <p class="text-gray-300 leading-relaxed">
                            By accessing or using VidyaSathi, you agree to be bound by these Terms of Service and all
                            applicable
                            laws and regulations. If you do not agree with any of these terms, you are prohibited from
                            using or
                            accessing this site.
                        </p>
                    </div>
                </div>

                <!-- Use License Section -->
                <div id="license" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-file-contract mr-3"></i>Use License
                    </h2>
                    <p class="text-gray-300 leading-relaxed mb-4">
                        Permission is granted to temporarily access the materials on VidyaSathi for personal,
                        non-commercial
                        use. This is the grant of a license, not a transfer of title, and under this license you may
                        not:
                    </p>
                    <div class="bg-gray-800 p-5 rounded-lg">
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 text-red-500 mr-3">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <span class="text-gray-300">Modify or copy the materials</span>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 text-red-500 mr-3">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <span class="text-gray-300">Use the materials for any commercial purpose</span>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 text-red-500 mr-3">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <span class="text-gray-300">Attempt to decompile or reverse engineer any software
                                    contained on VidyaSathi</span>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 text-red-500 mr-3">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <span class="text-gray-300">Remove any copyright or other proprietary notations from the
                                    materials</span>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 text-red-500 mr-3">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <span class="text-gray-300">Transfer the materials to another person or "mirror" the
                                    materials on any other server</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- User Accounts Section -->
                <div id="accounts" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-user-circle mr-3"></i>User Accounts
                    </h2>
                    <p class="text-gray-300 leading-relaxed mb-4">
                        To access certain features of the platform, you will need to create an account. You are
                        responsible
                        for maintaining the confidentiality of your account information and for all activities that
                        occur
                        under your account. You agree to:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-user-check text-green-500 mr-2"></i>
                                <h4 class="font-bold">Accurate Information</h4>
                            </div>
                            <p class="text-sm text-gray-300">Provide accurate and complete information when creating
                                your account</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-sync-alt text-blue-500 mr-2"></i>
                                <h4 class="font-bold">Keep Updated</h4>
                            </div>
                            <p class="text-sm text-gray-300">Update your information to keep it accurate and current</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-key text-yellow-500 mr-2"></i>
                                <h4 class="font-bold">Password Security</h4>
                            </div>
                            <p class="text-sm text-gray-300">Ensure your password remains confidential</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                <h4 class="font-bold">Security Alerts</h4>
                            </div>
                            <p class="text-sm text-gray-300">Notify us immediately of any unauthorized use of your
                                account</p>
                        </div>
                    </div>
                </div>

                <!-- User Content Section -->
                <div id="content" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-file-upload mr-3"></i>User Content
                    </h2>
                    <div class="mb-5 bg-gray-800 p-5 rounded-lg">
                        <p class="text-gray-300 leading-relaxed">
                            When you upload, post, or share content on VidyaSathi, you grant us a non-exclusive,
                            worldwide,
                            royalty-free license to use, copy, modify, and display that content in connection with the
                            services
                            we provide.
                        </p>
                    </div>
                    <p class="text-gray-300 leading-relaxed mb-4">
                        You are solely responsible for the content you post on the platform and must ensure that:
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <div
                                class="flex-shrink-0 h-6 w-6 rounded-full bg-primary flex items-center justify-center mt-1 mr-3">
                                <i class="fas fa-check text-xs text-white"></i>
                            </div>
                            <span class="text-gray-300">You own or have the necessary rights to the content you
                                post</span>
                        </li>
                        <li class="flex items-start">
                            <div
                                class="flex-shrink-0 h-6 w-6 rounded-full bg-primary flex items-center justify-center mt-1 mr-3">
                                <i class="fas fa-check text-xs text-white"></i>
                            </div>
                            <span class="text-gray-300">Your content does not violate the privacy, copyright, or other
                                rights of any person</span>
                        </li>
                        <li class="flex items-start">
                            <div
                                class="flex-shrink-0 h-6 w-6 rounded-full bg-primary flex items-center justify-center mt-1 mr-3">
                                <i class="fas fa-check text-xs text-white"></i>
                            </div>
                            <span class="text-gray-300">Your content does not contain material that is unlawful,
                                defamatory, harassing, or otherwise objectionable</span>
                        </li>
                    </ul>
                </div>

                <!-- Prohibited Activities Section -->
                <div id="prohibited" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-ban mr-3"></i>Prohibited Activities
                    </h2>
                    <p class="text-gray-300 leading-relaxed mb-4">
                        You agree not to engage in any of the following activities:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-red-500">
                            <h4 class="font-bold text-white mb-2">Illegal Use</h4>
                            <p class="text-sm text-gray-300">Using the platform for any illegal purpose</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-red-500">
                            <h4 class="font-bold text-white mb-2">Harassment</h4>
                            <p class="text-sm text-gray-300">Harassing, intimidating, or bullying other users</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-red-500">
                            <h4 class="font-bold text-white mb-2">Misinformation</h4>
                            <p class="text-sm text-gray-300">Posting spam, fake content, or misleading information</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-red-500">
                            <h4 class="font-bold text-white mb-2">Impersonation</h4>
                            <p class="text-sm text-gray-300">Impersonating another person or entity</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-red-500">
                            <h4 class="font-bold text-white mb-2">Unauthorized Access</h4>
                            <p class="text-sm text-gray-300">Attempting to gain unauthorized access to other user
                                accounts or VidyaSathi systems</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-red-500">
                            <h4 class="font-bold text-white mb-2">Disruption</h4>
                            <p class="text-sm text-gray-300">Interfering with or disrupting the platform's functionality
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Termination Section -->
                <div id="termination" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-power-off mr-3"></i>Termination
                    </h2>
                    <div class="flex p-5 bg-gray-800 rounded-lg">
                        <div class="flex-shrink-0 mr-4">
                            <i class="fas fa-exclamation-circle text-4xl text-yellow-500"></i>
                        </div>
                        <p class="text-gray-300 leading-relaxed">
                            We may terminate or suspend your account and access to VidyaSathi immediately, without prior
                            notice,
                            if you breach these Terms of Service. Upon termination, your right to use the platform will
                            cease
                            immediately.
                        </p>
                    </div>
                </div>

                <!-- Limitation of Liability Section -->
                <div id="liability" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-shield-alt mr-3"></i>Limitation of Liability
                    </h2>
                    <div class="bg-gray-800 p-5 rounded-lg">
                        <p class="text-gray-300 leading-relaxed">
                            In no event shall VidyaSathi, its officers, directors, employees, or agents be liable for
                            any
                            indirect, incidental, special, consequential, or punitive damages arising out of or relating
                            to your
                            use of the platform.
                        </p>
                    </div>
                </div>

                <!-- Changes to Terms Section -->
                <div id="changes" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-sync-alt mr-3"></i>Changes to Terms
                    </h2>
                    <div class="bg-gray-800 p-5 rounded-lg flex items-center">
                        <i class="fas fa-bell text-yellow-500 text-xl mr-4"></i>
                        <p class="text-gray-300 leading-relaxed">
                            We reserve the right to modify these terms at any time. We will notify users of any
                            significant
                            changes by posting an announcement on our platform. Your continued use of VidyaSathi after
                            changes
                            are made constitutes your acceptance of the updated terms.
                        </p>
                    </div>
                </div>

                <!-- Governing Law Section -->
                <div id="law" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-gavel mr-3"></i>Governing Law
                    </h2>
                    <div class="bg-gray-800 p-5 rounded-lg">
                        <p class="text-gray-300 leading-relaxed">
                            These terms shall be governed by and construed in accordance with the laws of India, without
                            regard
                            to its conflict of law provisions.
                        </p>
                    </div>
                </div>

                <!-- Contact Us Section -->
                <div id="contact" class="mb-4">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-envelope mr-3"></i>Contact Us
                    </h2>
                    <div class="bg-gray-800 p-6 rounded-lg text-center">
                        <p class="text-gray-300 leading-relaxed mb-4">
                            If you have any questions about these Terms of Service, please contact us at:
                        </p>
                        <a href="mailto:info@vidyasathi.com"
                            class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-envelope mr-2"></i>info@vidyasathi.com
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'footer.php'; ?>