<?php
if (!defined('ABSPATH')) {
    exit;
}

function bruce_hamm_asset($path)
{
    return get_template_directory_uri() . '/assets/' . ltrim($path, '/');
}

function bruce_hamm_page_url($slug)
{
    $page = get_page_by_path($slug);
    return $page ? get_permalink($page) : home_url('/' . trim($slug, '/') . '/');
}

function bruce_hamm_current_slug()
{
    if (is_front_page()) {
        return 'home';
    }

    $object = get_queried_object();
    if ($object instanceof WP_Post) {
        return $object->post_name;
    }

    return '';
}

function bruce_hamm_nav_class($slug)
{
    $classes = ['nav-link'];
    if ('contact' === $slug) {
        $classes[] = 'nav-cta';
    }
    if ($slug === bruce_hamm_current_slug()) {
        $classes[] = 'active';
    }
    return implode(' ', $classes);
}

function bruce_hamm_search_pages()
{
    $pages = [
        [
            'title' => 'Home',
            'url' => home_url('/'),
            'snippet' => 'Sarod virtuoso, performer and teacher in the California Bay Area.',
            'keywords' => 'bruce hamm sarod sitar virtuoso ali akbar khan homepage',
        ],
        [
            'slug' => 'biography',
            'snippet' => 'Disciple of Ustad Ali Akbar Khan, performer, and teacher of the Maihar Gharana.',
            'keywords' => 'biography disciple ali akbar khan aacm gandha bandhan zakir hussain swapan chaudhuri path of raga stu hamm charles hamm',
        ],
        [
            'slug' => 'gallery',
            'snippet' => 'Performance photos and archival images from decades on stage.',
            'keywords' => 'gallery photos images allaudin festival calcutta jabalpur swapan concert',
        ],
        [
            'slug' => 'music',
            'snippet' => 'Listen to Morning Ragas, Evening Ragas, and other sarod recordings.',
            'keywords' => 'music recordings morning ragas evening ragas chandranandan jogia kalengra audio tracks',
        ],
        [
            'slug' => 'videos',
            'snippet' => 'Watch concert footage and performances on YouTube.',
            'keywords' => 'videos youtube performance concert rag marwa chandranandan',
        ],
        [
            'slug' => 'events',
            'snippet' => 'Upcoming concerts and past appearances.',
            'keywords' => 'events concerts schedule upcoming past appearances',
        ],
        [
            'slug' => 'lessons',
            'snippet' => 'Private instrumental lessons and vocal study in North Indian classical music.',
            'keywords' => 'lessons teaching students sarod sitar vocal class private lessons',
        ],
        [
            'slug' => 'contact',
            'snippet' => 'Reach out for performances, lessons, or general inquiries.',
            'keywords' => 'contact email inquire booking performances form',
        ],
    ];

    $results = [];
    foreach ($pages as $page) {
        if (empty($page['slug'])) {
            $results[] = $page;
            continue;
        }

        $results[] = [
            'title' => ucfirst($page['slug']),
            'url' => bruce_hamm_page_url($page['slug']),
            'snippet' => $page['snippet'],
            'keywords' => $page['keywords'],
        ];
    }

    return $results;
}
