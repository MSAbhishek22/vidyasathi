<?php
// Set page title and include global header
$page_title = 'Home';
include 'header.php';
?>

<!-- Hero Section -->
<section class="hero">
  <h1 data-aos="fade-up">Be the Senior You Always Wanted to Have</h1>
  <p data-aos="fade-up" data-aos-delay="100">Access real-world learning resources, mentorship, and career-boosting
    projects, all in one place.</p>
  <div data-aos="fade-up" data-aos-delay="200" class="flex gap-4">
    <a href="register.php" class="btn btn-primary btn-lg">Join Now</a>
    <a href="#features" class="btn btn-secondary btn-lg">Learn More</a>
  </div>
</section>

<!-- Problem/Solution Section -->
<section class="bg-accent py-24 text-center" data-aos="fade-up">
  <div class="container">
    <h2 class="section-title">Tired of being lost in engineering chaos?</h2>
    <p class="section-subtitle">VidyaSathi is your senior-powered solution — navigate your engineering journey with
      confidence!</p>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="section">
  <div class="container">
    <h2 class="section-title" data-aos="fade-up">Our Core Features</h2>
    <p class="section-subtitle" data-aos="fade-up">Everything you need to excel in your academic journey</p>

    <div class="feature-grid">
      <!-- Feature 1 -->
      <div class="feature-card" data-aos="fade-up" data-aos-delay="0">
        <div class="feature-icon">
          <i class="fas fa-file-alt"></i>
        </div>
        <h3 class="feature-title">Previous Year Questions</h3>
        <p class="feature-text">Get access to carefully curated PYQs to ace your exams with flying colors.</p>
      </div>

      <!-- Feature 2 -->
      <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
        <div class="feature-icon">
          <i class="fas fa-book"></i>
        </div>
        <h3 class="feature-title">Notes from Toppers</h3>
        <p class="feature-text">Learn from the best - get access to comprehensive notes from top-performing seniors.</p>
      </div>

      <!-- Feature 3 -->
      <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
        <div class="feature-icon">
          <i class="fas fa-file-invoice"></i>
        </div>
        <h3 class="feature-title">Resume Tools</h3>
        <p class="feature-text">Build ATS-friendly resumes with expert guidance and industry-specific templates.</p>
      </div>

      <!-- Feature 4 -->
      <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
        <div class="feature-icon">
          <i class="fas fa-users"></i>
        </div>
        <h3 class="feature-title">Mentorship</h3>
        <p class="feature-text">Connect directly with seniors who have walked the path before you.</p>
      </div>

      <!-- Feature 5 -->
      <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
        <div class="feature-icon">
          <i class="fas fa-project-diagram"></i>
        </div>
        <h3 class="feature-title">Real Projects</h3>
        <p class="feature-text">Gain practical experience through industry-relevant projects that boost your portfolio.
        </p>
      </div>

      <!-- Feature 6 -->
      <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
        <div class="feature-icon">
          <i class="fas fa-comments"></i>
        </div>
        <h3 class="feature-title">Q&A Forum</h3>
        <p class="feature-text">Get your doubts cleared by a community of helpful seniors and peers.</p>
      </div>
    </div>
  </div>
</section>

<!-- How It Works Section -->
<section class="section bg-accent floating">
  <div class="container">
    <h2 class="section-title" data-aos="fade-up">How It Works</h2>
    <p class="section-subtitle" data-aos="fade-up">Get started in three simple steps</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
      <!-- Step 1 -->
      <div class="card" data-aos="fade-up" data-aos-delay="0">
        <div class="card-body text-center">
          <div class="text-5xl font-bold text-primary mb-4">01</div>
          <h3 class="card-title">Browse</h3>
          <p class="card-text">Explore our vast library of resources, projects, and mentorship opportunities.</p>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="card" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body text-center">
          <div class="text-5xl font-bold text-primary mb-4">02</div>
          <h3 class="card-title">Connect</h3>
          <p class="card-text">Reach out to seniors for guidance, join study groups, and participate in discussions.</p>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="card" data-aos="fade-up" data-aos-delay="200">
        <div class="card-body text-center">
          <div class="text-5xl font-bold text-primary mb-4">03</div>
          <h3 class="card-title">Grow</h3>
          <p class="card-text">Apply what you've learned, build your portfolio, and become the best version of yourself.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Resources Section -->
<section id="resources" class="section">
  <div class="container">
    <h2 class="section-title" data-aos="fade-up">Popular Resources</h2>
    <p class="section-subtitle" data-aos="fade-up">Dive into our most sought-after learning materials</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Resource 1 -->
      <div class="card" data-aos="fade-up">
        <div class="card-header bg-dark">
          <div class="flex justify-between items-center">
            <span class="text-xs text-primary font-semibold uppercase tracking-wider">TRENDING</span>
            <span class="bg-primary text-sm text-dark py-1 px-2 rounded-full">FREE</span>
          </div>
        </div>
        <div class="card-body">
          <h3 class="card-title">Data Structures & Algorithms</h3>
          <div class="flex items-center mb-3">
            <span class="text-primary mr-1">4.9 ★</span>
            <span class="text-sm text-gray-400">• 120 students</span>
          </div>
          <p class="card-text">Master the fundamentals of DSA with comprehensive notes and practice problems.</p>
          <a href="dsa_resources.php" class="btn btn-primary w-full">View Resource</a>
        </div>
      </div>

      <!-- Resource 2 -->
      <div class="card" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header bg-dark">
          <div class="flex justify-between items-center">
            <span class="text-xs text-primary font-semibold uppercase tracking-wider">POPULAR</span>
            <span class="bg-primary text-sm text-dark py-1 px-2 rounded-full">FREE</span>
          </div>
        </div>
        <div class="card-body">
          <h3 class="card-title">Interview Preparation</h3>
          <div class="flex items-center mb-3">
            <span class="text-primary mr-1">4.8 ★</span>
            <span class="text-sm text-gray-400">• 85 students</span>
          </div>
          <p class="card-text">Comprehensive guide to technical and HR interviews with real questions.</p>
          <a href="https://youtu.be/1qw5ITr3k9E?si=d24Dg4LRvNo9kRT9" target="_blank" class="btn btn-primary w-full">View
            Resource</a>
        </div>
      </div>

      <!-- Resource 3 -->
      <div class="card" data-aos="fade-up" data-aos-delay="200">
        <div class="card-header bg-dark">
          <div class="flex justify-between items-center">
            <span class="text-xs text-primary font-semibold uppercase tracking-wider">NEW</span>
            <span class="bg-primary text-sm text-dark py-1 px-2 rounded-full">FREE</span>
          </div>
        </div>
        <div class="card-body">
          <h3 class="card-title">Web Development Basics</h3>
          <div class="flex items-center mb-3">
            <span class="text-primary mr-1">4.7 ★</span>
            <span class="text-sm text-gray-400">• 64 students</span>
          </div>
          <p class="card-text">Learn HTML, CSS, and JavaScript from scratch with hands-on projects.</p>
          <a href="web_dev_resources.php" class="btn btn-primary w-full">View Resource</a>
        </div>
      </div>
    </div>

    <div class="text-center mt-12" data-aos="fade-up">
      <a href="#" class="btn btn-secondary">View All Resources</a>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="section bg-accent">
  <div class="container">
    <h2 class="section-title" data-aos="fade-up">What Students Say</h2>
    <p class="section-subtitle" data-aos="fade-up">Hear from those who've transformed their academic journey</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
      <!-- Testimonial 1 -->
      <div class="card" data-aos="fade-up">
        <div class="card-body">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 rounded-full bg-primary mr-4"></div>
            <div>
              <h4 class="font-semibold">Rahul Sharma</h4>
              <p class="text-sm text-gray-400">Computer Science, 3rd Year</p>
            </div>
          </div>
          <p class="card-text">"The PYQs and notes from seniors helped me top my exams. The mentorship I received was
            invaluable for my internship hunt."</p>
          <div class="text-primary mt-4">★★★★★</div>
        </div>
      </div>

      <!-- Testimonial 2 -->
      <div class="card" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 rounded-full bg-primary mr-4"></div>
            <div>
              <h4 class="font-semibold">Priya Patel</h4>
              <p class="text-sm text-gray-400">Electronics, Final Year</p>
            </div>
          </div>
          <p class="card-text">"The resume tools helped me craft a perfect CV that landed me interviews at my dream
            companies. Forever grateful to VidyaSathi!"</p>
          <div class="text-primary mt-4">★★★★★</div>
        </div>
      </div>

      <!-- Testimonial 3 -->
      <div class="card" data-aos="fade-up" data-aos-delay="200">
        <div class="card-body">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 rounded-full bg-primary mr-4"></div>
            <div>
              <h4 class="font-semibold">Amit Verma</h4>
              <p class="text-sm text-gray-400">Mechanical, 2nd Year</p>
            </div>
          </div>
          <p class="card-text">"The community here is amazing! I found study partners, mentors, and friends who helped
            me navigate through tough academic times."</p>
          <div class="text-primary mt-4">★★★★☆</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Join Now CTA -->
<section class="section" data-aos="fade-up">
  <div class="container text-center">
    <h2 class="section-title">Ready to Get Started?</h2>
    <p class="section-subtitle">Join VidyaSathi today and become a part of a growing community of learners and mentors.
    </p>
    <a href="register.php" class="btn btn-primary btn-lg mt-8">Join Now</a>
  </div>
</section>

<?php
// Include global footer
include 'footer.php';
?>