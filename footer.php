<footer class="bg-black text-light py-16">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div>
        <h3 class="text-xl font-bold mb-4">VidyaSathi</h3>
        <p class="mb-4 text-gray-400">Be the senior you always wanted to have. Navigate your educational journey with
          confidence.</p>
        <div class="flex space-x-4">
          <a href="https://x.com/MSAbhishek022" target="_blank" class="text-gray-400 hover:text-primary"><i
              class="fab fa-twitter"></i></a>
          <a href="#" class="text-gray-400 hover:text-primary"><i class="fab fa-instagram"></i></a>
          <a href="https://www.linkedin.com/in/m-s-abhishek22/" target="_blank"
            class="text-gray-400 hover:text-primary"><i class="fab fa-linkedin"></i></a>
        </div>
      </div>

      <div>
        <h3 class="text-xl font-bold mb-4">Resources</h3>
        <ul class="space-y-2">
          <li><a href="pyqs.php" class="text-gray-400 hover:text-primary">PYQs</a></li>
          <li><a href="notes.php" class="text-gray-400 hover:text-primary">Notes</a></li>
          <li><a href="#" class="text-gray-400 hover:text-primary">Resume Builder</a></li>
          <li><a href="#" class="text-gray-400 hover:text-primary">Q&A Forum</a></li>
        </ul>
      </div>

      <div>
        <h3 class="text-xl font-bold mb-4">Community</h3>
        <ul class="space-y-2">
          <li><a href="community.php" class="text-gray-400 hover:text-primary">Connect with Seniors</a></li>
          <li><a href="community.php" class="text-gray-400 hover:text-primary">Study Groups</a></li>
          <li><a href="community.php" class="text-gray-400 hover:text-primary">Mentorship</a></li>
          <li><a href="community.php" class="text-gray-400 hover:text-primary">Events</a></li>
        </ul>
      </div>

      <div>
        <h3 class="text-xl font-bold mb-4">Legal</h3>
        <ul class="space-y-2">
          <li><a href="privacy-policy.php" class="text-gray-400 hover:text-primary">Privacy Policy</a></li>
          <li><a href="terms-of-service.php" class="text-gray-400 hover:text-primary">Terms of Service</a></li>
          <li><a href="cookie-policy.php" class="text-gray-400 hover:text-primary">Cookie Policy</a></li>
        </ul>
      </div>
    </div>

    <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm text-gray-500">
      <p>&copy; <?= date('Y') ?> VidyaSathi. All rights reserved.</p>
    </div>
  </div>
</footer>

<script>
  // Initialize AOS (Animations)
  AOS.init({
    duration: 1000,
    easing: 'ease-in-out',
    once: true,
  });
</script>
</body>

</html>