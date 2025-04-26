<?php
session_start();
$page_title = 'Cookie Policy - VidyaSathi';
include 'header.php';
?>

<div class="py-8"></div>

<!-- Cookie Policy Header -->
<section class="bg-gradient-to-r from-primary to-purple-700 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Cookie Policy</h1>
            <p class="text-xl text-white opacity-90">Last Updated: <?php echo date('F d, Y'); ?></p>
        </div>
    </div>
</section>

<!-- Cookie Policy Content -->
<section class="py-16 bg-dark">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-card-bg rounded-lg p-8 shadow-lg border border-gray-800">

            <!-- Table of Contents -->
            <div class="mb-10 p-5 bg-gray-900 rounded-lg border border-gray-700">
                <h3 class="text-lg font-bold mb-4 text-primary">Table of Contents</h3>
                <ol class="list-decimal list-inside space-y-2 text-gray-300">
                    <li><a href="#what-are-cookies" class="hover:text-primary transition-colors">What Are Cookies</a>
                    </li>
                    <li><a href="#how-we-use" class="hover:text-primary transition-colors">How We Use Cookies</a></li>
                    <li><a href="#types-of-cookies" class="hover:text-primary transition-colors">Types of Cookies We
                            Use</a></li>
                    <li><a href="#cookie-management" class="hover:text-primary transition-colors">Cookie Management</a>
                    </li>
                    <li><a href="#manage-browsers" class="hover:text-primary transition-colors">How to Manage Cookies in
                            Different Browsers</a></li>
                    <li><a href="#policy-changes" class="hover:text-primary transition-colors">Changes to Our Cookie
                            Policy</a></li>
                    <li><a href="#contact" class="hover:text-primary transition-colors">Contact Us</a></li>
                </ol>
            </div>

            <div class="prose prose-invert max-w-none">
                <!-- What Are Cookies Section -->
                <div id="what-are-cookies" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-cookie-bite mr-3"></i>What Are Cookies
                    </h2>
                    <div class="bg-gray-800 p-5 rounded-lg flex items-start">
                        <div class="flex-shrink-0 mr-4 mt-1">
                            <i class="fas fa-info-circle text-3xl text-blue-400"></i>
                        </div>
                        <p class="text-gray-300 leading-relaxed">
                            Cookies are small text files that are placed on your device when you visit a website. They
                            are widely
                            used to make websites work more efficiently and provide information to the website owners.
                        </p>
                    </div>
                </div>

                <!-- How We Use Cookies Section -->
                <div id="how-we-use" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-cog mr-3"></i>How We Use Cookies
                    </h2>
                    <p class="text-gray-300 leading-relaxed mb-5">
                        VidyaSathi uses cookies for several purposes, including:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-800 p-5 rounded-lg border-l-4 border-green-500">
                            <h4 class="font-bold text-white flex items-center mb-3">
                                <i class="fas fa-shield-alt text-green-500 mr-2"></i>Essential Cookies
                            </h4>
                            <p class="text-gray-300">These cookies are necessary for the website to function properly.
                                They enable core functionality such as security, network management, and account access.
                            </p>
                        </div>
                        <div class="bg-gray-800 p-5 rounded-lg border-l-4 border-blue-500">
                            <h4 class="font-bold text-white flex items-center mb-3">
                                <i class="fas fa-sliders-h text-blue-500 mr-2"></i>Preference Cookies
                            </h4>
                            <p class="text-gray-300">These cookies enable us to remember information that changes the
                                way the website behaves or looks, like your preferred language or the region you are in.
                            </p>
                        </div>
                        <div class="bg-gray-800 p-5 rounded-lg border-l-4 border-purple-500">
                            <h4 class="font-bold text-white flex items-center mb-3">
                                <i class="fas fa-chart-line text-purple-500 mr-2"></i>Analytics Cookies
                            </h4>
                            <p class="text-gray-300">These cookies help us understand how visitors interact with our
                                website by collecting and reporting information anonymously.</p>
                        </div>
                        <div class="bg-gray-800 p-5 rounded-lg border-l-4 border-yellow-500">
                            <h4 class="font-bold text-white flex items-center mb-3">
                                <i class="fas fa-user-clock text-yellow-500 mr-2"></i>Session Cookies
                            </h4>
                            <p class="text-gray-300">These cookies maintain your session while you're using the
                                VidyaSathi platform, allowing you to remain logged in as you navigate between pages.</p>
                        </div>
                    </div>
                </div>

                <!-- Types of Cookies Section -->
                <div id="types-of-cookies" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-cookie mr-3"></i>Types of Cookies We Use
                    </h2>

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <div class="h-6 w-6 rounded-full bg-primary flex items-center justify-center mr-2">
                                <span class="text-white text-xs">1</span>
                            </div>
                            First-Party Cookies
                        </h3>
                        <div class="bg-gray-800 p-5 rounded-lg">
                            <p class="text-gray-300 leading-relaxed mb-4">
                                These are cookies that are set by VidyaSathi directly. They are used to:
                            </p>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 text-primary mr-3 mt-1">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <span class="text-gray-300">Remember your login status</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 text-primary mr-3 mt-1">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <span class="text-gray-300">Remember your preferences</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 text-primary mr-3 mt-1">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <span class="text-gray-300">Ensure the platform functions correctly</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <div class="h-6 w-6 rounded-full bg-primary flex items-center justify-center mr-2">
                                <span class="text-white text-xs">2</span>
                            </div>
                            Third-Party Cookies
                        </h3>
                        <div class="bg-gray-800 p-5 rounded-lg">
                            <p class="text-gray-300 leading-relaxed mb-4">
                                These are cookies set by our partners and service providers. They help us:
                            </p>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 text-primary mr-3 mt-1">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <span class="text-gray-300">Analyze how our platform is used</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 text-primary mr-3 mt-1">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <span class="text-gray-300">Improve our services</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 text-primary mr-3 mt-1">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <span class="text-gray-300">Enhance user experience</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Cookie Management Section -->
                <div id="cookie-management" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-sliders-h mr-3"></i>Cookie Management
                    </h2>
                    <p class="text-gray-300 leading-relaxed mb-5">
                        Most web browsers allow you to manage your cookie preferences. You can:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-800 p-4 rounded-lg text-center">
                            <div class="mb-3">
                                <i class="fas fa-trash-alt text-3xl text-red-500"></i>
                            </div>
                            <h4 class="font-bold text-white mb-2">Delete Cookies</h4>
                            <p class="text-sm text-gray-300">Remove cookies from your device</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-lg text-center">
                            <div class="mb-3">
                                <i class="fas fa-ban text-3xl text-yellow-500"></i>
                            </div>
                            <h4 class="font-bold text-white mb-2">Block Cookies</h4>
                            <p class="text-sm text-gray-300">Refuse all or some cookies via browser settings</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-lg text-center">
                            <div class="mb-3">
                                <i class="fas fa-bell text-3xl text-blue-500"></i>
                            </div>
                            <h4 class="font-bold text-white mb-2">Notifications</h4>
                            <p class="text-sm text-gray-300">Get browser alerts when cookies are received</p>
                        </div>
                    </div>

                    <div class="bg-gray-900 p-5 rounded-lg border-l-4 border-yellow-500">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                <i class="fas fa-exclamation-triangle text-2xl text-yellow-500"></i>
                            </div>
                            <p class="text-gray-300">
                                Please note that if you choose to block or delete cookies, you may not be able to access
                                certain areas or features of our platform, and some services may not function properly.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Browser Management Section -->
                <div id="manage-browsers" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-globe-americas mr-3"></i>How to Manage Cookies in Different Browsers
                    </h2>
                    <p class="text-gray-300 leading-relaxed mb-5">
                        You can manage cookie settings in the following browsers:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="https://support.google.com/chrome/answer/95647" target="_blank"
                            class="block bg-gray-800 p-5 rounded-lg hover:bg-gray-700 transition-colors group">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 mr-4">
                                    <i class="fab fa-chrome text-3xl text-red-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Google Chrome</h4>
                                    <p class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">Learn
                                        how to manage cookies in Chrome</p>
                                </div>
                                <div class="ml-auto">
                                    <i class="fas fa-external-link-alt text-primary"></i>
                                </div>
                            </div>
                        </a>
                        <a href="https://support.mozilla.org/en-US/kb/enhanced-tracking-protection-firefox-desktop"
                            target="_blank"
                            class="block bg-gray-800 p-5 rounded-lg hover:bg-gray-700 transition-colors group">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 mr-4">
                                    <i class="fab fa-firefox text-3xl text-orange-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Mozilla Firefox</h4>
                                    <p class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">Learn
                                        how to manage cookies in Firefox</p>
                                </div>
                                <div class="ml-auto">
                                    <i class="fas fa-external-link-alt text-primary"></i>
                                </div>
                            </div>
                        </a>
                        <a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471"
                            target="_blank"
                            class="block bg-gray-800 p-5 rounded-lg hover:bg-gray-700 transition-colors group">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 mr-4">
                                    <i class="fab fa-safari text-3xl text-blue-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Safari</h4>
                                    <p class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">Learn
                                        how to manage cookies in Safari</p>
                                </div>
                                <div class="ml-auto">
                                    <i class="fas fa-external-link-alt text-primary"></i>
                                </div>
                            </div>
                        </a>
                        <a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09"
                            target="_blank"
                            class="block bg-gray-800 p-5 rounded-lg hover:bg-gray-700 transition-colors group">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 mr-4">
                                    <i class="fab fa-edge text-3xl text-blue-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Microsoft Edge</h4>
                                    <p class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">Learn
                                        how to manage cookies in Edge</p>
                                </div>
                                <div class="ml-auto">
                                    <i class="fas fa-external-link-alt text-primary"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Changes to Policy Section -->
                <div id="policy-changes" class="mb-10 pb-8 border-b border-gray-700">
                    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center">
                        <i class="fas fa-sync-alt mr-3"></i>Changes to Our Cookie Policy
                    </h2>
                    <div class="bg-gray-800 p-5 rounded-lg flex items-center">
                        <i class="fas fa-clipboard-list text-primary text-xl mr-4"></i>
                        <p class="text-gray-300 leading-relaxed">
                            We may update our Cookie Policy from time to time. Any changes will be posted on this page
                            with an updated revision date.
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
                            If you have any questions about our Cookie Policy, please contact us at:
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