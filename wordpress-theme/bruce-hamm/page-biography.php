<?php get_header(); ?>
<main>
  <section class="page-hero">
    <div class="page-hero-inner">
      <p class="eyebrow reveal">Disciple · Performer · Teacher</p>
      <h1 class="page-title reveal">Biography</h1>
      <p class="page-subtitle reveal">
        A lifelong journey through North Indian classical music under the guidance of a legendary maestro.
      </p>
    </div>
  </section>

  <section class="bio-layout">
    <article class="bio-narrative">
      <div class="bio-block reveal">
        <span class="bio-year">1974</span>
        <h2>Ali Akbar College</h2>
        <p>
          Bruce Hamm is a disciple of the revered maestro Ustad Ali Akbar Khan. He began studying
          the sitar and vocal music with Khansahib at Ali Akbar College of Music in 1974. After
          several years of studying the sitar, Bruce decided to switch his main instrument to the sarod.
          He pursued a serious and continuous study of classical instrumental and vocal music with
          his guru over the span of nearly four decades.
        </p>
      </div>

      <div class="bio-block reveal">
        <span class="bio-year">1981</span>
        <h2>Formal Discipleship</h2>
        <p>
          In 1981 he had the honor of tying the thread (Gandha Bandhan) with Khansahib to become a
          formal disciple. At that time he became an instructor at the Ali Akbar College of Music.
          He continued as an assistant teacher at the AACM until his guru's death in 2009. Bruce was
          one of a handful of disciples chosen by Khansahib to teach at the AACM after his passing.
        </p>
      </div>

      <div class="bio-block reveal">
        <span class="bio-year">Stage</span>
        <h2>Performances &amp; Collaborations</h2>
        <p>
          Bruce had the opportunity on multiple occasions to accompany his Guru Ali Akbar Khan
          onstage. He regularly performs solo concerts throughout the US, Europe and India. He has also
          had the privilege of performing with many of India's leading artists, including
          Swapan Chaudhuri, Zakir Hussain and Anindo Chatterjee.
        </p>
      </div>

      <div class="bio-block reveal">
        <span class="bio-year">Today</span>
        <h2>Continuing the Legacy</h2>
        <p>
          Bruce continues his mentor's legacy today by teaching anyone who is willing to dedicate
          themselves to this music. He is co-founder, along with Joanna Mack, of the non-profit
          organization Path of Raga, which is committed to the preservation of the music of the
          Maihar Gharana.
        </p>
      </div>

      <div class="bio-block reveal">
        <span class="bio-year">Roots</span>
        <h2>Roots</h2>
        <p>
          Bruce was born into a musical family and immersed in music from early childhood. His father,
          Charles Hamm, was a composer and later a famous musicologist. His mother, Helen Hamm, was an
          opera singer. His younger brother Stu Hamm is a noted bassist. As a child Bruce played piano,
          trumpet, French horn and guitar. As a teenager he found his true love, Indian Classical Music.
        </p>
      </div>
    </article>

    <aside class="bio-sidebar">
      <figure class="bio-image-card reveal">
        <div class="image-frame">
          <img
            src="<?php echo esc_url(bruce_hamm_asset('images/gallery/alladuin-fes-aak.jpg')); ?>"
            alt="George Ruckert, Peter Van Gelder, Ali Akbar Khan, James Pomerantz, Swapan Chaudhuri, Terence Pease, and Bruce Hamm at the 1st Annual Allaudin Khan Festival, 1991"
          >
          <div class="frame-glow"></div>
        </div>
        <figcaption>
          Left to Right: George Ruckert, Peter Van Gelder, Ali Akbar Khan, James Pomerantz,
          Swapan Chaudhuri, Terence Pease, Bruce Hamm.<br>
          <em>1st Annual Allaudin Khan Festival, 1991</em>
        </figcaption>
      </figure>

      <figure class="bio-image-card bio-image-card-wide reveal">
        <div class="image-frame">
          <img
            src="<?php echo esc_url(bruce_hamm_asset('images/gallery/doordharshan-ensemble.jpg')); ?>"
            alt="Ensemble performance on Doordarshan with sarod, tanpura, and tabla"
          >
          <div class="frame-glow"></div>
        </div>
        <figcaption>
          Left to Right: Ali Akbar Khan, Buddhdev Dasgupta, Dhyanesh Khan, Supriyo Banerjee, Bruce Hamm.
        </figcaption>
      </figure>

      <div class="bio-highlights reveal">
        <h3>Highlights</h3>
        <ul>
          <li>
            <span class="highlight-icon" aria-hidden="true">◈</span>
            Disciple of Ustad Ali Akbar Khan
          </li>
          <li>
            <span class="highlight-icon" aria-hidden="true">◈</span>
            Instructor, Ali Akbar College of Music
          </li>
          <li>
            <span class="highlight-icon" aria-hidden="true">◈</span>
            Solo concerts across US &amp; India
          </li>
          <li>
            <span class="highlight-icon" aria-hidden="true">◈</span>
            Founder of <a href="https://pathofraga.org" target="_blank" rel="noopener noreferrer">Path of Raga Foundation</a>
          </li>
        </ul>
      </div>
    </aside>
  </section>

  <section class="cta-band">
    <div class="cta-band-inner reveal">
      <h2>Experience the music live</h2>
      <p>Explore upcoming events or inquire about private lessons.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url(bruce_hamm_page_url('events')); ?>" class="btn btn-primary">View Events</a>
        <a href="<?php echo esc_url(bruce_hamm_page_url('lessons')); ?>" class="btn btn-ghost">Learn Sarod and Sitar</a>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
