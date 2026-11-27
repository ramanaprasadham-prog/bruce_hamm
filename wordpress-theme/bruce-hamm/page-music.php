<?php
get_header();

function bruce_hamm_render_album_column($n)
{
    $cover = bruce_hamm_get('album' . $n . '_cover');
    $tracks = (array) bruce_hamm_get('album' . $n . '_tracks');
    $playable = array_filter($tracks, function ($track) {
        return !empty($track['audio']);
    });
    $upcoming = array_filter($tracks, function ($track) {
        return empty($track['audio']) && '' !== trim($track['title']);
    });

    if (!$cover && !$playable && !$upcoming) {
        return;
    }
    ?>
      <div class="album-column">
        <?php if ($cover) : ?>
          <figure class="album-cover reveal">
            <div class="album-cover-frame">
              <img src="<?php echo esc_url($cover); ?>" alt="<?php echo esc_attr(bruce_hamm_get('album' . $n . '_cover_alt')); ?>">
              <div class="frame-glow"></div>
            </div>
          </figure>
        <?php endif; ?>

        <?php if ($playable) : ?>
          <ul class="track-list">
            <?php foreach ($playable as $track) : ?>
              <li class="track-card reveal">
                <div class="track-info">
                  <h2 class="track-title"><?php echo esc_html($track['title']); ?></h2>
                  <?php if ('' !== $track['meta']) : ?>
                    <p class="track-meta"><?php echo esc_html($track['meta']); ?></p>
                  <?php endif; ?>
                </div>
                <audio controls preload="metadata" src="<?php echo esc_url($track['audio']); ?>">
                  Your browser does not support audio playback.
                </audio>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <?php if ($upcoming) : ?>
          <div class="coming-soon reveal">
            <?php if ('' !== bruce_hamm_get('coming_soon_label')) : ?>
              <p class="coming-soon-label"><?php echo esc_html(bruce_hamm_get('coming_soon_label')); ?></p>
            <?php endif; ?>
            <ul class="tracklist-preview">
              <?php foreach ($upcoming as $track) : ?>
                <li>
                  <span class="tracklist-name"><?php echo esc_html($track['title']); ?></span>
                  <?php if ('' !== $track['meta']) : ?>
                    <span class="tracklist-duration"><?php echo esc_html($track['meta']); ?></span>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    <?php
}
?>
<main>
  <?php bruce_hamm_page_hero(); ?>

  <section class="page-content page-content-wide">
    <div class="music-layout">
      <?php
      bruce_hamm_render_album_column(1);
      bruce_hamm_render_album_column(2);
      ?>
    </div>

    <?php $others = array_filter((array) bruce_hamm_get('other_tracks'), function ($track) {
        return !empty($track['audio']);
    }); ?>
    <?php if ($others) : ?>
      <div class="other-tracks">
        <?php if ('' !== bruce_hamm_get('other_tracks_title')) : ?>
          <h2 class="other-tracks-title reveal"><?php echo esc_html(bruce_hamm_get('other_tracks_title')); ?></h2>
        <?php endif; ?>
        <ul class="tracklist-preview other-tracks-list">
          <?php foreach ($others as $track) : ?>
            <li class="other-track-item reveal">
              <span class="tracklist-name"><?php echo esc_html($track['title']); ?></span>
              <audio controls preload="metadata" src="<?php echo esc_url($track['audio']); ?>">
                Your browser does not support audio playback.
              </audio>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
