<?php get_header(); ?>
<main>
  <section class="page-hero">
    <div class="page-hero-inner">
      <p class="eyebrow reveal">On Stage</p>
      <h1 class="page-title reveal">Videos</h1>
      <p class="page-subtitle reveal">Watch breathtaking performances and concert footage directly from their channels.</p>
    </div>
  </section>

  <section class="page-content page-content-wide">
    <div class="video-grid">
      <article class="video-card reveal">
        <div
          class="video-frame video-lazy"
          data-youtube-id="_OTR5QUcVR8"
          data-youtube-start="111"
          data-youtube-title="Bruce Hamm performance video"
        >
          <button type="button" class="video-play-btn" aria-label="Play Bruce Hamm performance video">
            <img
              src="https://img.youtube.com/vi/_OTR5QUcVR8/hqdefault.jpg"
              alt="Bruce Hamm performance video thumbnail"
              loading="lazy"
            >
            <span class="video-play-icon" aria-hidden="true"></span>
          </button>
        </div>
        <p class="video-fallback">
          <a href="https://www.youtube.com/watch?v=_OTR5QUcVR8&amp;t=111s" target="_blank" rel="noopener noreferrer">
            Watch on YouTube
          </a>
        </p>
      </article>
    </div>

    <div class="video-channels">
      <div class="content-card reveal video-channel-card">
        <div class="video-channel-row">
          <figure class="video-channel-photo">
            <div class="image-frame">
              <img
                src="<?php echo esc_url(bruce_hamm_asset('images/gallery/bruce-portrait.png')); ?>"
                alt="Bruce Hamm playing sarod in studio portrait"
              >
              <div class="frame-glow"></div>
            </div>
          </figure>
          <div class="video-channel-copy">
            <h2 class="video-channel-title">YouTube Channel</h2>
            <p>Watch sarod performances and music from Bruce Hamm on his YouTube channel.</p>
            <p>
              <a href="https://www.youtube.com/@oobssarode" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
                Bruce Hamm on YouTube
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
