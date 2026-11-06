<?php
if (!defined('ABSPATH')) {
    exit;
}

function bruce_hamm_activate_theme()
{
    bruce_hamm_create_pages();
    bruce_hamm_seed_events();

    if (!get_option('permalink_structure')) {
        update_option('permalink_structure', '/%postname%/');
    }

    flush_rewrite_rules();
}

add_action('after_switch_theme', 'bruce_hamm_activate_theme');

function bruce_hamm_create_pages()
{
    $pages = [
        [
            'title' => 'Home',
            'slug' => 'home',
            'content' => 'Bruce Hamm is a long-time disciple of Ustad Ali Akbar Khan — an accomplished performer and respected teacher in the California Bay Area.',
        ],
        [
            'title' => 'Biography',
            'slug' => 'biography',
            'content' => 'A lifelong journey through North Indian classical music under the guidance of a legendary maestro. Disciple of Ustad Ali Akbar Khan, instructor at Ali Akbar College of Music, and co-founder of Path of Raga.',
        ],
        [
            'title' => 'Gallery',
            'slug' => 'gallery',
            'content' => 'Performance photos and archival images from decades on stage, including Ali Akbar Khan, Swapan Chaudhuri, Calcutta, Jabalpur, and the Allaudin Khan Festival.',
        ],
        [
            'title' => 'Music',
            'slug' => 'music',
            'content' => 'Listen to Morning Ragas, Evening Ragas, Chandranandan, Jogia Kalengra, and other sarod recordings.',
        ],
        [
            'title' => 'Videos',
            'slug' => 'videos',
            'content' => 'Watch concert footage and performances on the Bruce Hamm YouTube channel.',
        ],
        [
            'title' => 'Events',
            'slug' => 'events',
            'content' => 'Upcoming concerts and past appearances.',
        ],
        [
            'title' => 'Lessons',
            'slug' => 'lessons',
            'content' => 'Private instrumental lessons and vocal study in North Indian classical music for sarod, sitar, and other instruments.',
        ],
        [
            'title' => 'Contact',
            'slug' => 'contact',
            'content' => 'Reach out for performances, lessons, or general inquiries.',
        ],
    ];

    $created = [];
    foreach ($pages as $page) {
        $existing = get_page_by_path($page['slug']);
        if ($existing) {
            $created[$page['slug']] = $existing->ID;
            continue;
        }

        $created[$page['slug']] = wp_insert_post([
            'post_title' => $page['title'],
            'post_name' => $page['slug'],
            'post_content' => $page['content'],
            'post_status' => 'publish',
            'post_type' => 'page',
        ]);
    }

    if (!empty($created['home'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', (int) $created['home']);
    }
}

function bruce_hamm_seed_events()
{
    if (get_option('bruce_hamm_events_seeded')) {
        return;
    }

    $events = [
        [
            'title' => 'Bruce Hamm',
            'content' => 'Student recital featuring students of Bruce Hamm and Joanna Mack. Free and open to all.',
            'event_date' => '2026-06-13',
            'event_date_display' => 'Saturday, June 13, 2026 · 3:00–7:00 PM',
            'event_venue' => 'Sri Krishna Balaram Mandir · 680 E Calaveras Blvd, Milpitas, CA 95035',
            'event_poster' => bruce_hamm_asset('images/events/path-of-raga.png'),
            'event_poster_alt' => 'Bruce Hamm — student recital poster',
        ],
        [
            'title' => 'Joyful Summer Evening of Raga',
            'content' => 'Sitar, Sarod and Tabla — Joanna Mack (sitar), Bruce Hamm (sarod), Amod Dandawate (tabla).',
            'event_date' => '2026-05-16',
            'event_date_display' => 'Saturday, May 16, 2026 · 5:30 PM',
            'event_venue' => 'Blacow · Fremont, CA',
            'event_poster' => bruce_hamm_asset('images/events/joyful-summer-evening-of-raga.png'),
            'event_poster_alt' => 'Joyful summer evening of Raga — concert poster',
            'event_ticket_url' => 'https://www.groupmuse.com',
            'event_ticket_label' => 'Groupmuse.com',
            'event_ticket_text' => 'Tickets were available on',
        ],
    ];

    foreach ($events as $event) {
        $id = wp_insert_post([
            'post_title' => $event['title'],
            'post_content' => $event['content'],
            'post_status' => 'publish',
            'post_type' => 'bruce_event',
            'post_date' => $event['event_date'] . ' 12:00:00',
        ]);

        if (is_wp_error($id) || !$id) {
            continue;
        }

        foreach ($event as $key => $value) {
            if (in_array($key, ['title', 'content'], true)) {
                continue;
            }
            update_post_meta($id, $key, $value);
        }
    }

    update_option('bruce_hamm_events_seeded', 1);
}
