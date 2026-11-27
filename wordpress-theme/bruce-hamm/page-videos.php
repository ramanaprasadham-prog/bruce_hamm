<?php get_header(); ?>
<main>
  <?php bruce_hamm_page_hero(); ?>

  <section class="page-content page-content-wide">
    <?php
    $videos = [];
    foreach ((array) bruce_hamm_get('videos') as $video) {
        $parsed = bruce_hamm_parse_youtube($video['url']);
        if ($parsed) {
            $videos[] = $parsed + ['title' => '' !== $video['title'] ? $video['title'] : 'Bruce Hamm performance video'];
        }
    }
    ?>
    <?php if ($videos) : ?>
      <div class="video-grid">
        <?php foreach ($videos as $video) : ?>
          <?php
          $watch_url = 'https://www.youtube.com/watch?v=' . $video['id'] . ($video['start'] ? '&t=' . $video['start'] . 's' : '');
          ?>
          <article class="video-card reveal">
            <div
              class="video-frame video-lazy"
              data-youtube-id="<?php echo esc_attr($video['id']); ?>"
              <?php echo $video['start'] ? 'data-youtube-start="' . esc_attr($video['start']) . '"' : ''; ?>
              data-youtube-title="<?php echo esc_attr($video['title']); ?>"
            >
              <button type="button" class="video-play-btn" aria-label="<?php echo esc_attr('Play ' . $video['title']); ?>">
                <img
                  src="<?php echo esc_url('https://img.youtube.com/vi/' . $video['id'] . '/hqdefault.jpg'); ?>"
                  alt="<?php echo esc_attr($video['title'] . ' thumbnail'); ?>"
                  loading="lazy"
                >
                <span class="video-play-icon" aria-hidden="true"></span>
              </button>
            </div>
            <p class="video-fallback">
              <a href="<?php echo esc_url($watch_url); ?>" target="_blank" rel="noopener noreferrer">
                Watch on YouTube
              </a>
            </p>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ('' !== bruce_hamm_get('channel_title') || '' !== bruce_hamm_get('channel_url')) : ?>
      <div class="video-channels">
        <div class="content-card reveal video-channel-card">
          <div class="video-channel-row">
            <?php if (bruce_hamm_get('channel_photo')) : ?>
              <figure class="video-channel-photo">
                <div class="image-frame">
                  <img
                    src="<?php echo esc_url(bruce_hamm_get('channel_photo')); ?>"
                    alt="<?php echo esc_attr(bruce_hamm_get('channel_photo_alt')); ?>"
                  >
                  <div class="frame-glow"></div>
                </div>
              </figure>
            <?php endif; ?>
            <div class="video-channel-copy">
              <?php if ('' !== bruce_hamm_get('channel_title')) : ?>
                <h2 class="video-channel-title"><?php echo esc_html(bruce_hamm_get('channel_title')); ?></h2>
              <?php endif; ?>
              <?php if ('' !== bruce_hamm_get('channel_text')) : ?>
                <p><?php echo esc_html(bruce_hamm_get('channel_text')); ?></p>
              <?php endif; ?>
              <?php if ('' !== bruce_hamm_get('channel_url') && '' !== bruce_hamm_get('channel_button')) : ?>
                <p>
                  <a href="<?php echo esc_url(bruce_hamm_get('channel_url')); ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html(bruce_hamm_get('channel_button')); ?>
                  </a>
                </p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
