<?php get_header(); ?>
<main>
  <section class="hero hero-home">
    <div class="hero-upper">
      <div class="hero-carousel-wrap">
        <?php bruce_hamm_slider(bruce_hamm_get('hero_slides'), 'Homepage photo gallery'); ?>
      </div>

      <div class="hero-main reveal">
        <div class="hero-main-row">
          <div class="hero-panel hero-panel-content">
            <div class="hero-content">
              <?php $eyebrow = bruce_hamm_get('hero_eyebrow'); ?>
              <?php if ('' !== $eyebrow) : ?>
                <p class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></p>
              <?php endif; ?>
              <h1 class="hero-title reveal">
                <?php if ('' !== bruce_hamm_get('hero_line1')) : ?>
                  <span class="title-line"><?php echo esc_html(bruce_hamm_get('hero_line1')); ?></span>
                <?php endif; ?>
                <?php if ('' !== bruce_hamm_get('hero_line2')) : ?>
                  <span class="title-line accent"><?php echo esc_html(bruce_hamm_get('hero_line2')); ?></span>
                <?php endif; ?>
              </h1>
              <?php $lead = bruce_hamm_page_body_plain(); ?>
              <?php if ('' !== $lead) : ?>
                <p class="hero-lead reveal"><?php echo esc_html($lead); ?></p>
              <?php endif; ?>
              <div class="hero-actions reveal">
                <?php if ('' !== bruce_hamm_get('hero_button1')) : ?>
                  <a href="<?php echo esc_url(bruce_hamm_page_url('biography')); ?>" class="btn btn-primary"><?php echo esc_html(bruce_hamm_get('hero_button1')); ?></a>
                <?php endif; ?>
                <?php if ('' !== bruce_hamm_get('hero_button2')) : ?>
                  <a href="<?php echo esc_url(bruce_hamm_page_url('lessons')); ?>" class="btn btn-ghost"><?php echo esc_html(bruce_hamm_get('hero_button2')); ?></a>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="hero-panel hero-panel-stats">
            <?php foreach ((array) bruce_hamm_get('hero_stats') as $stat) : ?>
              <div class="stat-card reveal">
                <span class="stat-value"><?php echo esc_html($stat['value']); ?></span>
                <span class="stat-label"><?php echo esc_html($stat['label']); ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
