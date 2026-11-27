<?php get_header(); ?>
<main>
  <?php bruce_hamm_page_hero(); ?>

  <section class="bio-layout">
    <article class="bio-narrative">
      <?php echo bruce_hamm_page_body(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered by the_content filters. ?>
    </article>

    <aside class="bio-sidebar">
      <?php foreach ((array) bruce_hamm_get('bio_photos') as $photo) : ?>
        <?php if (empty($photo['image'])) { continue; } ?>
        <figure class="bio-image-card<?php echo !empty($photo['wide']) ? ' bio-image-card-wide' : ''; ?> reveal">
          <div class="image-frame">
            <img src="<?php echo esc_url($photo['image']); ?>" alt="<?php echo esc_attr($photo['alt']); ?>">
            <div class="frame-glow"></div>
          </div>
          <?php if ('' !== $photo['caption'] || '' !== $photo['note']) : ?>
            <figcaption>
              <?php echo esc_html($photo['caption']); ?>
              <?php if ('' !== $photo['note']) : ?>
                <?php echo '' !== $photo['caption'] ? '<br>' : ''; ?>
                <em><?php echo esc_html($photo['note']); ?></em>
              <?php endif; ?>
            </figcaption>
          <?php endif; ?>
        </figure>
      <?php endforeach; ?>

      <?php $highlights = (array) bruce_hamm_get('highlights'); ?>
      <?php if ($highlights) : ?>
        <div class="bio-highlights reveal">
          <?php if ('' !== bruce_hamm_get('highlights_title')) : ?>
            <h3><?php echo esc_html(bruce_hamm_get('highlights_title')); ?></h3>
          <?php endif; ?>
          <ul>
            <?php foreach ($highlights as $item) : ?>
              <li>
                <span class="highlight-icon" aria-hidden="true">◈</span>
                <?php echo esc_html($item['text']); ?>
                <?php if ('' !== $item['link_text']) : ?>
                  <?php if ('' !== $item['link_url']) : ?>
                    <a href="<?php echo esc_url($item['link_url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($item['link_text']); ?></a>
                  <?php else : ?>
                    <?php echo esc_html($item['link_text']); ?>
                  <?php endif; ?>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    </aside>
  </section>

  <?php if ('' !== bruce_hamm_get('cta_title')) : ?>
    <section class="cta-band">
      <div class="cta-band-inner reveal">
        <h2><?php echo esc_html(bruce_hamm_get('cta_title')); ?></h2>
        <?php if ('' !== bruce_hamm_get('cta_text')) : ?>
          <p><?php echo esc_html(bruce_hamm_get('cta_text')); ?></p>
        <?php endif; ?>
        <div class="hero-actions">
          <?php if ('' !== bruce_hamm_get('cta_button1')) : ?>
            <a href="<?php echo esc_url(bruce_hamm_page_url('events')); ?>" class="btn btn-primary"><?php echo esc_html(bruce_hamm_get('cta_button1')); ?></a>
          <?php endif; ?>
          <?php if ('' !== bruce_hamm_get('cta_button2')) : ?>
            <a href="<?php echo esc_url(bruce_hamm_page_url('lessons')); ?>" class="btn btn-ghost"><?php echo esc_html(bruce_hamm_get('cta_button2')); ?></a>
          <?php endif; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
