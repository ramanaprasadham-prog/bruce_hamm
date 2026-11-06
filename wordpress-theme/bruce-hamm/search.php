<?php get_header(); ?>
<main>
  <section class="page-hero">
    <div class="page-hero-inner">
      <p class="eyebrow reveal">Search</p>
      <h1 class="page-title reveal">Results</h1>
      <p class="page-subtitle reveal">
        <?php
        printf(
            /* translators: %s: search query */
            esc_html__('Showing matches for “%s”.', 'bruce-hamm'),
            esc_html(get_search_query())
        );
        ?>
      </p>
    </div>
  </section>
  <section class="page-content">
    <?php if (have_posts()) : ?>
      <div class="content-card reveal">
        <ul class="footer-links">
          <?php while (have_posts()) : the_post(); ?>
            <li>
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              <p><?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt() ?: get_the_content()), 24)); ?></p>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    <?php else : ?>
      <div class="events-empty reveal">
        <p>No matching pages.</p>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
