<?php get_header(); ?>
<main>
  <?php bruce_hamm_page_hero(); ?>

  <section class="page-content page-content-wide">
    <?php $slides = bruce_hamm_get('lessons_slides'); ?>
    <?php if ($slides) : ?>
      <div class="lessons-carousel-wrap">
        <?php bruce_hamm_slider($slides, 'Lessons photo gallery'); ?>
      </div>
    <?php endif; ?>

    <div class="content-card reveal">
      <?php echo bruce_hamm_page_body(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered by the_content filters. ?>
      <?php if ('' !== bruce_hamm_get('lessons_button')) : ?>
        <p>
          <a href="<?php echo esc_url(bruce_hamm_page_url('contact')); ?>" class="btn btn-primary"><?php echo esc_html(bruce_hamm_get('lessons_button')); ?></a>
        </p>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
