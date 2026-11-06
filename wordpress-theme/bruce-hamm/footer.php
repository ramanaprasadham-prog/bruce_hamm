  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-brand-link">
          <span class="footer-brand-text">Bruce Hamm</span>
        </a>
        <p class="footer-tagline">
          North Indian classical music with Bruce Hamm — sarod, sitar, and vocal study in the California Bay Area.
        </p>
      </div>
      <nav class="footer-nav" aria-label="Footer navigation">
        <h2 class="footer-heading">Explore</h2>
        <ul class="footer-links">
          <li><a href="<?php echo esc_url(bruce_hamm_page_url('biography')); ?>">Biography</a></li>
          <li><a href="<?php echo esc_url(bruce_hamm_page_url('gallery')); ?>">Gallery</a></li>
          <li><a href="<?php echo esc_url(bruce_hamm_page_url('music')); ?>">Music</a></li>
          <li><a href="<?php echo esc_url(bruce_hamm_page_url('videos')); ?>">Videos</a></li>
          <li><a href="<?php echo esc_url(bruce_hamm_page_url('events')); ?>">Events</a></li>
        </ul>
      </nav>
      <div class="footer-connect">
        <h2 class="footer-heading">Connect</h2>
        <ul class="footer-links">
          <li><a href="<?php echo esc_url(bruce_hamm_page_url('lessons')); ?>">Lessons</a></li>
          <li><a href="<?php echo esc_url(bruce_hamm_page_url('contact')); ?>">Contact</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?php echo esc_html(gmdate('Y')); ?> Bruce Hamm. All rights reserved.</p>
    </div>
  </footer>
<?php wp_footer(); ?>
</body>
</html>
