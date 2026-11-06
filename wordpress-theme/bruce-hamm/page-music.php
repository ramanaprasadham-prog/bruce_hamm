<?php get_header(); ?>
<main>
  <section class="page-hero">
    <div class="page-hero-inner">
      <p class="eyebrow reveal">Recordings</p>
      <h1 class="page-title reveal">Music</h1>
      <p class="page-subtitle reveal">Listen to performances and recordings of North Indian classical Sarod.</p>
    </div>
  </section>

  <section class="page-content page-content-wide">
    <div class="music-layout">
      <div class="album-column">
        <figure class="album-cover reveal">
          <div class="album-cover-frame">
            <img src="<?php echo esc_url(bruce_hamm_asset('images/morning-ragas-cover.png')); ?>" alt="Morning Ragas — Bruce Hamm, Sarod; Marcus Wise, Tabla">
            <div class="frame-glow"></div>
          </div>
        </figure>
        <ul class="track-list">
          <li class="track-card reveal">
            <div class="track-info">
              <h2 class="track-title">Rag Jogia Kalengra – Tintal</h2>
              <p class="track-meta">Sarod · Recording</p>
            </div>
            <audio controls preload="metadata" src="<?php echo esc_url(bruce_hamm_asset('audio/1-Audio-Track-6.mp3')); ?>">
              Your browser does not support audio playback.
            </audio>
          </li>
          <li class="track-card reveal">
            <div class="track-info">
              <h2 class="track-title">Rag Jogia Kalengra – Jhaptal</h2>
              <p class="track-meta">Sarod · Recording</p>
            </div>
            <audio controls preload="metadata" src="<?php echo esc_url(bruce_hamm_asset('audio/2-Audio-Track-11.mp3')); ?>">
              Your browser does not support audio playback.
            </audio>
          </li>
          <li class="track-card reveal">
            <div class="track-info">
              <h2 class="track-title">Rag Bilaskhani Todi Alap Jor – Slow Tintal</h2>
              <p class="track-meta">Sarod · Recording</p>
            </div>
            <audio controls preload="metadata" src="<?php echo esc_url(bruce_hamm_asset('audio/3-Audio-Track-7.mp3')); ?>">
              Your browser does not support audio playback.
            </audio>
          </li>
        </ul>
      </div>

      <div class="album-column">
        <figure class="album-cover reveal">
          <div class="album-cover-frame">
            <img src="<?php echo esc_url(bruce_hamm_asset('images/evening-ragas-cover.png')); ?>" alt="Evening Ragas — Bruce Hamm, Sarod; Ashis Paul, Tabla">
            <div class="frame-glow"></div>
          </div>
        </figure>
        <div class="coming-soon reveal">
          <p class="coming-soon-label">Coming Soon</p>
          <ul class="tracklist-preview">
            <li>
              <span class="tracklist-name">Rag Hem Bihag – Alap &amp; Jor</span>
              <span class="tracklist-duration">22.25</span>
            </li>
            <li>
              <span class="tracklist-name">Rag Hem Bihag – Gats in Tintal</span>
              <span class="tracklist-duration">18.17</span>
            </li>
            <li>
              <span class="tracklist-name">Rag Bagkauns – Gat in Slow Tintal</span>
              <span class="tracklist-duration">21.29</span>
            </li>
            <li>
              <span class="tracklist-name">Rag Gawati – Gat in Tintal</span>
              <span class="tracklist-duration">10.26</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="other-tracks">
      <h2 class="other-tracks-title reveal">Other Tracks</h2>
      <ul class="tracklist-preview other-tracks-list">
        <li class="other-track-item reveal">
          <span class="tracklist-name">Chandranadan – Alap</span>
          <audio controls preload="metadata" src="<?php echo esc_url(bruce_hamm_asset('audio/Chandranandan-Alap-Mono.mp3')); ?>">
            Your browser does not support audio playback.
          </audio>
        </li>
        <li class="other-track-item reveal">
          <span class="tracklist-name">Chandranandan – Vilambit Gat w/ Marcus Wise, tabla</span>
          <audio controls preload="metadata" src="<?php echo esc_url(bruce_hamm_asset('audio/Chandranandan-Vilambit-Tintal_final_mono.mp3')); ?>">
            Your browser does not support audio playback.
          </audio>
        </li>
        <li class="other-track-item reveal">
          <span class="tracklist-name">Hindol Hem – Vilambit Gat w/ Pandit Swapan Chaudhuri, tabla</span>
          <audio controls preload="metadata" src="<?php echo esc_url(bruce_hamm_asset('audio/Hindol-Hem-Vilambit.mp3')); ?>">
            Your browser does not support audio playback.
          </audio>
        </li>
        <li class="other-track-item reveal">
          <span class="tracklist-name">Bhoop Mand – Chachar w/ Nikhil Pandya, tabla</span>
          <audio controls preload="metadata" src="<?php echo esc_url(bruce_hamm_asset('audio/BhoopMand_web_final.mp3')); ?>">
            Your browser does not support audio playback.
          </audio>
        </li>
        <li class="other-track-item reveal">
          <span class="tracklist-name">Chandranandan – Fast Gat w/ Marcus Wise, tabla</span>
          <audio controls preload="metadata" src="<?php echo esc_url(bruce_hamm_asset('audio/Chandranandan-Fast-Gat.mp3')); ?>">
            Your browser does not support audio playback.
          </audio>
        </li>
      </ul>
    </div>
  </section>
</main>
<?php get_footer(); ?>
