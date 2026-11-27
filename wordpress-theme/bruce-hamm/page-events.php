<?php get_header(); ?>
<main>
  <?php bruce_hamm_page_hero(); ?>

  <section class="page-content page-content-wide">
    <div class="events-section reveal">
      <h2 class="events-section-title"><?php echo esc_html(bruce_hamm_get('upcoming_title')); ?></h2>
      <div id="events-upcoming" aria-live="polite">
        <?php
        $upcoming = bruce_hamm_get_events(true);
        if ($upcoming) :
            echo '<ul class="event-list">';
            foreach ($upcoming as $event) {
                echo bruce_hamm_render_event_card($event); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the renderer.
            }
            echo '</ul>';
        else :
            $booking = bruce_hamm_get('upcoming_booking');
            ?>
          <div class="events-empty">
            <?php if ('' !== bruce_hamm_get('upcoming_empty')) : ?>
              <p><?php echo esc_html(bruce_hamm_get('upcoming_empty')); ?></p>
            <?php endif; ?>
            <?php if ('' !== $booking) : ?>
              <p><?php echo bruce_hamm_link_phrase($booking, 'get in touch', bruce_hamm_page_url('contact')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper. ?></p>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="events-section reveal">
      <h2 class="events-section-title"><?php echo esc_html(bruce_hamm_get('past_title')); ?></h2>
      <div id="events-past" aria-live="polite">
        <?php
        $past = bruce_hamm_get_events(false);
        if ($past) :
            echo '<ul class="event-list">';
            foreach ($past as $event) {
                echo bruce_hamm_render_event_card($event); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the renderer.
            }
            echo '</ul>';
        else :
            ?>
          <div class="events-empty"><p><?php echo esc_html(bruce_hamm_get('past_empty')); ?></p></div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
