<?php get_header(); ?>
<main>
  <?php bruce_hamm_page_hero(); ?>

  <section class="page-content page-content-wide">
    <div class="gallery-grid">
      <?php
      $shapes = [
          'wide' => ' gallery-item-wide',
          'panoramic' => ' gallery-item-wide gallery-item-panoramic',
          'portrait' => ' gallery-item-portrait',
      ];
      foreach ((array) bruce_hamm_get('gallery_photos') as $photo) :
          if (empty($photo['image'])) {
              continue;
          }
          $shape = isset($shapes[$photo['shape']]) ? $shapes[$photo['shape']] : '';
          ?>
        <figure class="gallery-item<?php echo esc_attr($shape); ?> reveal">
          <div class="image-frame">
            <img src="<?php echo esc_url($photo['image']); ?>" alt="<?php echo esc_attr($photo['alt']); ?>">
            <div class="frame-glow"></div>
          </div>
          <?php if ('' !== $photo['caption']) : ?>
            <figcaption><?php echo esc_html($photo['caption']); ?></figcaption>
          <?php endif; ?>
        </figure>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
