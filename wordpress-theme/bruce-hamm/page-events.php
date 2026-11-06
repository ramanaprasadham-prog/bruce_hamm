<?php get_header(); ?>
<main>
  <section class="page-hero">
    <div class="page-hero-inner">
      <p class="eyebrow reveal">Live Performances</p>
      <h1 class="page-title reveal">Events</h1>
      <p class="page-subtitle reveal">Concerts, appearances, and performances — upcoming and past.</p>
    </div>
  </section>

  <section class="page-content page-content-wide">
    <div class="events-section reveal">
      <h2 class="events-section-title">Upcoming Events</h2>
      <div id="events-upcoming" aria-live="polite">
        <?php
        $upcoming = bruce_hamm_get_events(true);
        if ($upcoming) :
            echo '<ul class="event-list">';
            foreach ($upcoming as $event) {
                echo bruce_hamm_render_event_card($event);
            }
            echo '</ul>';
        else :
            ?>
          <div class="events-empty">
            <p>Coming soon. New concert dates and appearances will be posted here.</p>
            <p>For booking inquiries, please <a href="<?php echo esc_url(bruce_hamm_page_url('contact')); ?>">get in touch</a>.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="events-section reveal">
      <h2 class="events-section-title">Past Events</h2>
      <div id="events-past" aria-live="polite">
        <?php
        $past = bruce_hamm_get_events(false);
        if ($past) :
            echo '<ul class="event-list">';
            foreach ($past as $event) {
                echo bruce_hamm_render_event_card($event);
            }
            echo '</ul>';
        else :
            ?>
          <div class="events-empty"><p>No past events listed yet.</p></div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
