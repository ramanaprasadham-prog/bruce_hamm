<?php get_header(); ?>
<main>
  <section class="page-hero">
    <div class="page-hero-inner">
      <p class="eyebrow reveal">Get in Touch</p>
      <h1 class="page-title reveal">Contact</h1>
      <p class="page-subtitle reveal">Reach out for performances, lessons, or general inquiries.</p>
    </div>
  </section>

  <section class="page-content">
    <div class="content-card reveal">
      <form class="contact-form" action="https://formspree.io/f/maqgqnee" method="POST">
        <label class="form-honeypot" aria-hidden="true">
          Do not fill this out
          <input type="text" name="_gotcha" tabindex="-1" autocomplete="off">
        </label>
        <input type="hidden" name="_subject" value="New contact form message — brucehamm.com">
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
        <button type="submit" class="btn btn-primary">Send Message</button>
      </form>
    </div>
  </section>
</main>
<?php get_footer(); ?>
