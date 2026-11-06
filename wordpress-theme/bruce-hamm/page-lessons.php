<?php get_header(); ?>
<main>
  <section class="page-hero">
    <div class="page-hero-inner">
      <p class="eyebrow reveal">Study Sarod</p>
      <h1 class="page-title reveal">Lessons</h1>
      <p class="page-subtitle reveal">
        Bruce teaches anyone willing to dedicate themselves to the art of North Indian classical music.
      </p>
    </div>
  </section>

  <section class="page-content page-content-wide">
    <div class="lessons-carousel-wrap">
      <div class="hero-slider reveal" data-autoplay="6000">
        <div class="hero-slider-viewport">
          <div class="hero-slider-track">
            <figure class="hero-slide is-active">
              <div class="carousel-slide-frame">
                <img src="<?php echo esc_url(bruce_hamm_asset('images/lessons/student-collage-2026.jpg')); ?>" alt="Collage of Bruce Hamm's students performing and studying sarod, sitar, and tabla">
              </div>
            </figure>
            <figure class="hero-slide">
              <div class="carousel-slide-frame">
                <img src="<?php echo esc_url(bruce_hamm_asset('images/lessons/class-boys-2019.jpg')); ?>" alt="Group music lesson with students playing sitar and tabla alongside Bruce Hamm and Joanna Mack">
              </div>
            </figure>
            <figure class="hero-slide">
              <div class="carousel-slide-frame">
                <img src="<?php echo esc_url(bruce_hamm_asset('images/lessons/group-lesson.jpg')); ?>" alt="Bruce Hamm with a young student beside a portrait of Ustad Ali Akbar Khan">
              </div>
            </figure>
            <figure class="hero-slide">
              <div class="carousel-slide-frame">
                <img src="<?php echo esc_url(bruce_hamm_asset('images/lessons/teaching-vedika.jpg')); ?>" alt="Bruce Hamm teaching a student sarod one on one">
              </div>
            </figure>
          </div>
        </div>
        <div class="hero-slider-dots" role="tablist" aria-label="Lessons photo gallery">
          <button type="button" class="hero-slider-dot is-active" aria-label="Show photo 1" aria-selected="true"></button>
          <button type="button" class="hero-slider-dot" aria-label="Show photo 2" aria-selected="false"></button>
          <button type="button" class="hero-slider-dot" aria-label="Show photo 3" aria-selected="false"></button>
          <button type="button" class="hero-slider-dot" aria-label="Show photo 4" aria-selected="false"></button>
        </div>
      </div>
    </div>

    <div class="content-card reveal">
      <p>Bruce is an experienced teacher and enjoys sharing his knowledge with students of all ages and levels.</p>
      <p>
        He began teaching at the Ali Akbar Khan College in 1982, assisting beginning students with their
        instruments and helping them understand the compositions. He has taught both vocal and instrumental
        classes ever since.
      </p>
      <p>
        He began his studies on Sitar and then switched to Sarode during his time with Khansahib. He is able
        to teach Classical Hindustani music for Sitar, Sarode, or any other Indian or Western instrument.
        Khansahib always said you have to sing before you can play, so at least half of Bruce&rsquo;s education
        was an immersion in the vocal tradition — The old dhrupads and khyals collected by Baba Allauddin
        Khan, and the many beautiful compositions of Khansahib.
      </p>
      <p>Please inquire for availability of private instrumental lessons and vocal lessons.</p>
      <p>
        <a href="<?php echo esc_url(bruce_hamm_page_url('contact')); ?>" class="btn btn-primary">Inquire About Lessons</a>
      </p>
    </div>
  </section>
</main>
<?php get_footer(); ?>
