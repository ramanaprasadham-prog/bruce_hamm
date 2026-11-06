<?php get_header(); ?>
<main>
  <section class="page-hero">
    <div class="page-hero-inner">
      <p class="eyebrow reveal">Not found</p>
      <h1 class="page-title reveal">Page missing</h1>
      <p class="page-subtitle reveal">That address does not match a page on this site.</p>
    </div>
  </section>
  <section class="page-content">
    <div class="events-empty reveal">
      <p><a href="<?php echo esc_url(home_url('/')); ?>">Return home</a></p>
    </div>
  </section>
</main>
<?php get_footer(); ?>
