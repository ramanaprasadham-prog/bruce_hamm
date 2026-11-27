<?php get_header(); ?>
<main>
  <?php bruce_hamm_page_hero(); ?>

  <section class="page-content">
    <div class="content-card reveal">
      <?php $formspree_id = bruce_hamm_formspree_id(); ?>
      <?php if ('' === $formspree_id) : ?>
        <p class="form-status form-status--error" role="alert">
          The contact form is not set up yet.
          <?php if (current_user_can('edit_theme_options')) : ?>
            Add the Formspree form ID under <a href="<?php echo esc_url(admin_url('customize.php?autofocus[section]=bruce_hamm_contact')); ?>">Appearance &rarr; Customize &rarr; Contact Form</a>.
          <?php endif; ?>
        </p>
      <?php else : ?>
        <form class="contact-form" action="<?php echo esc_url('https://formspree.io/f/' . $formspree_id); ?>" method="POST">
          <label class="form-honeypot" aria-hidden="true">
            Do not fill this out
            <input type="text" name="_gotcha" tabindex="-1" autocomplete="off">
          </label>
          <input type="hidden" name="_subject" value="<?php echo esc_attr('New contact form message — ' . wp_parse_url(home_url(), PHP_URL_HOST)); ?>">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required autocomplete="name">
          </div>
          <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required autocomplete="email">
          </div>
          <div class="form-group">
            <label for="message">Your Message</label>
            <textarea id="message" name="message" required></textarea>
          </div>
          <p class="form-status" hidden role="status" aria-live="polite"></p>
          <?php $button = bruce_hamm_get('form_button'); ?>
          <button type="submit" class="btn btn-primary"><?php echo esc_html('' !== $button ? $button : 'Send Message'); ?></button>
        </form>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
