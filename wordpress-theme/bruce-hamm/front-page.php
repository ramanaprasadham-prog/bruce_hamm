<?php get_header(); ?>
<main>
  <section class="hero hero-home">
    <div class="hero-upper">
      <div class="hero-carousel-wrap">
        <div class="hero-slider reveal" data-autoplay="6000">
          <div class="hero-slider-viewport">
            <div class="hero-slider-track">
              <figure class="hero-slide is-active">
                <div class="carousel-slide-frame">
                  <img src="<?php echo esc_url(bruce_hamm_asset('images/homepage-bg/Bruce_2017_GMC.jpg')); ?>" alt="Bruce Hamm with sarod, 2017">
                </div>
              </figure>
              <figure class="hero-slide">
                <div class="carousel-slide-frame">
                  <img src="<?php echo esc_url(bruce_hamm_asset('images/homepage-bg/Bruce_Kolkata_2019.jpg')); ?>" alt="Bruce Hamm performing in Kolkata, 2019">
                </div>
              </figure>
              <figure class="hero-slide">
                <div class="carousel-slide-frame">
                  <img src="<?php echo esc_url(bruce_hamm_asset('images/homepage-bg/Bruce_Baba_1.jpg')); ?>" alt="Bruce Hamm performing sarod with tabla and tanpura">
                </div>
              </figure>
              <figure class="hero-slide">
                <div class="carousel-slide-frame">
                  <img src="<?php echo esc_url(bruce_hamm_asset('images/homepage-bg/Doordharshan_Mix_3.jpg')); ?>" alt="Bruce Hamm performing with ensemble on stage">
                </div>
              </figure>
            </div>
          </div>
          <div class="hero-slider-dots" role="tablist" aria-label="Homepage photo gallery">
            <button type="button" class="hero-slider-dot is-active" aria-label="Show photo 1" aria-selected="true"></button>
            <button type="button" class="hero-slider-dot" aria-label="Show photo 2" aria-selected="false"></button>
            <button type="button" class="hero-slider-dot" aria-label="Show photo 3" aria-selected="false"></button>
            <button type="button" class="hero-slider-dot" aria-label="Show photo 4" aria-selected="false"></button>
          </div>
        </div>
      </div>

      <div class="hero-main reveal">
        <div class="hero-main-row">
          <div class="hero-panel hero-panel-content">
            <div class="hero-content">
              <p class="eyebrow reveal">North Indian Classical Music</p>
              <h1 class="hero-title reveal">
                <span class="title-line">The Art of</span>
                <span class="title-line accent">Sarod and Sitar</span>
              </h1>
              <p class="hero-lead reveal">
                Bruce Hamm is a long-time disciple of Ustad Ali Akbar Khan — an accomplished performer
                and respected teacher in the California Bay Area, carrying forward a legacy of nearly five decades.
              </p>
              <div class="hero-actions reveal">
                <a href="<?php echo esc_url(bruce_hamm_page_url('biography')); ?>" class="btn btn-primary">Explore Biography</a>
                <a href="<?php echo esc_url(bruce_hamm_page_url('lessons')); ?>" class="btn btn-ghost">Book a Lesson</a>
              </div>
            </div>
          </div>

          <div class="hero-panel hero-panel-stats">
            <div class="stat-card reveal">
              <span class="stat-value">1974</span>
              <span class="stat-label">Began Study</span>
            </div>
            <div class="stat-card reveal">
              <span class="stat-value">40+</span>
              <span class="stat-label">Years of Mastery</span>
            </div>
            <div class="stat-card reveal">
              <span class="stat-value">∞</span>
              <span class="stat-label">Legacy Continues</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
