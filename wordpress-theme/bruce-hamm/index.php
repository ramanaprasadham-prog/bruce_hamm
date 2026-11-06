<?php get_header(); ?>
<main>
  <section class="page-hero">
    <div class="page-hero-inner">
      <h1 class="page-title reveal"><?php echo esc_html(get_the_title() ?: get_bloginfo('name')); ?></h1>
    </div>
  </section>
  <section class="page-content">
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <div class="content-card reveal">
          <?php the_content(); ?>
        </div>
      <?php endwhile; ?>
    <?php else : ?>
      <div class="content-card reveal">
        <p>Nothing found.</p>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
