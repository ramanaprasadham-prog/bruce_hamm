<?php
if (!defined('ABSPATH')) {
    exit;
}

function bruce_hamm_register_event_cpt()
{
    register_post_type('bruce_event', [
        'labels' => [
            'name' => 'Events',
            'singular_name' => 'Event',
            'add_new_item' => 'Add Event',
            'edit_item' => 'Edit Event',
        ],
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'event'],
    ]);
}

add_action('init', 'bruce_hamm_register_event_cpt');

function bruce_hamm_event_meta_boxes()
{
    add_meta_box(
        'bruce_hamm_event_details',
        'Event details',
        'bruce_hamm_render_event_meta_box',
        'bruce_event',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'bruce_hamm_event_meta_boxes');

function bruce_hamm_render_event_meta_box($post)
{
    wp_nonce_field('bruce_hamm_event_meta', 'bruce_hamm_event_nonce');
    $fields = [
        'event_date' => ['Date (YYYY-MM-DD)', get_post_meta($post->ID, 'event_date', true)],
        'event_date_display' => ['Date display', get_post_meta($post->ID, 'event_date_display', true)],
        'event_venue' => ['Venue', get_post_meta($post->ID, 'event_venue', true)],
        'event_poster' => ['Poster image URL', get_post_meta($post->ID, 'event_poster', true)],
        'event_poster_alt' => ['Poster alt text', get_post_meta($post->ID, 'event_poster_alt', true)],
        'event_ticket_url' => ['Ticket URL', get_post_meta($post->ID, 'event_ticket_url', true)],
        'event_ticket_label' => ['Ticket label', get_post_meta($post->ID, 'event_ticket_label', true)],
        'event_ticket_text' => ['Ticket sentence prefix', get_post_meta($post->ID, 'event_ticket_text', true)],
    ];

    echo '<p>Use the editor for the event description. Featured image can also be used as the poster.</p>';
    foreach ($fields as $key => $field) {
        printf(
            '<p><label for="%1$s"><strong>%2$s</strong></label><br><input type="text" class="widefat" id="%1$s" name="%1$s" value="%3$s"></p>',
            esc_attr($key),
            esc_html($field[0]),
            esc_attr($field[1])
        );
    }
}

function bruce_hamm_save_event_meta($post_id)
{
    if (!isset($_POST['bruce_hamm_event_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['bruce_hamm_event_nonce'])), 'bruce_hamm_event_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $keys = [
        'event_date',
        'event_date_display',
        'event_venue',
        'event_poster',
        'event_poster_alt',
        'event_ticket_url',
        'event_ticket_label',
        'event_ticket_text',
    ];

    foreach ($keys as $key) {
        if (!isset($_POST[$key])) {
            continue;
        }
        $value = sanitize_text_field(wp_unslash($_POST[$key]));
        if ('event_ticket_url' === $key || 'event_poster' === $key) {
            $value = esc_url_raw(wp_unslash($_POST[$key]));
        }
        update_post_meta($post_id, $key, $value);
    }
}

add_action('save_post_bruce_event', 'bruce_hamm_save_event_meta');

function bruce_hamm_get_events($upcoming = true)
{
    $today = gmdate('Y-m-d');
    $query = new WP_Query([
        'post_type' => 'bruce_event',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_key' => 'event_date',
        'orderby' => 'meta_value',
        'order' => $upcoming ? 'ASC' : 'DESC',
        'meta_query' => [
            [
                'key' => 'event_date',
                'value' => $today,
                'compare' => $upcoming ? '>=' : '<',
                'type' => 'DATE',
            ],
        ],
    ]);

    return $query->posts;
}

function bruce_hamm_render_event_card($post)
{
    $date = get_post_meta($post->ID, 'event_date', true);
    $date_display = get_post_meta($post->ID, 'event_date_display', true) ?: $date;
    $venue = get_post_meta($post->ID, 'event_venue', true);
    $poster = get_post_meta($post->ID, 'event_poster', true);
    $poster_alt = get_post_meta($post->ID, 'event_poster_alt', true) ?: get_the_title($post);
    $ticket_url = get_post_meta($post->ID, 'event_ticket_url', true);
    $ticket_label = get_post_meta($post->ID, 'event_ticket_label', true);
    $ticket_text = get_post_meta($post->ID, 'event_ticket_text', true);
    $detail = wp_strip_all_tags($post->post_content);

    if (!$poster && has_post_thumbnail($post)) {
        $poster = get_the_post_thumbnail_url($post, 'large');
    }

    $ticket_html = '';
    if ($ticket_url && $ticket_label) {
        $prefix = $ticket_text ? esc_html($ticket_text) . ' ' : '';
        $ticket_html = $prefix . '<a href="' . esc_url($ticket_url) . '" target="_blank" rel="noopener noreferrer">' . esc_html($ticket_label) . '</a>.';
    }

    $detail_html = esc_html($detail);
    if ($ticket_html) {
        $detail_html .= ' ' . $ticket_html;
    }

    ob_start();
    ?>
    <li class="event-card reveal">
      <?php if ($poster) : ?>
        <div class="event-poster">
          <img src="<?php echo esc_url($poster); ?>" alt="<?php echo esc_attr($poster_alt); ?>">
        </div>
      <?php endif; ?>
      <time class="event-date" datetime="<?php echo esc_attr($date); ?>"><?php echo esc_html($date_display); ?></time>
      <h3 class="event-title"><?php echo esc_html(get_the_title($post)); ?></h3>
      <?php if ($venue) : ?>
        <p class="event-venue"><?php echo esc_html($venue); ?></p>
      <?php endif; ?>
      <?php if ($detail_html) : ?>
        <p class="event-detail"><?php echo $detail_html; ?></p>
      <?php endif; ?>
    </li>
    <?php
    return ob_get_clean();
}
