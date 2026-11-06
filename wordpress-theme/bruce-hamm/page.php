<?php get_header(); ?>
<main>
  <section class="page-hero">
    <div class="page-hero-inner">
      <h1 class="page-title reveal"><?php the_title(); ?></h1>
    </div>
  </section>
  <section class="page-content">
    <div class="content-card reveal">
      <?php
      while (have_posts()) :
          the_post();
          the_content();
      endwhile;
      ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
