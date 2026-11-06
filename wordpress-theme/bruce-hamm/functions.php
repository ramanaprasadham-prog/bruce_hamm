<?php
if (!defined('ABSPATH')) {
    exit;
}

define('BRUCE_HAMM_VERSION', '1.0.0');

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/cpt-events.php';
require get_template_directory() . '/inc/setup.php';

function bruce_hamm_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('automatic-feed-links');
}

add_action('after_setup_theme', 'bruce_hamm_theme_setup');

function bruce_hamm_enqueue_assets()
{
    wp_enqueue_style(
        'bruce-hamm-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Source+Sans+3:wght@300;400;500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'bruce-hamm',
        bruce_hamm_asset('css/styles.css'),
        ['bruce-hamm-fonts'],
        BRUCE_HAMM_VERSION
    );

    wp_enqueue_script(
        'bruce-hamm',
        bruce_hamm_asset('js/main.js'),
        [],
        BRUCE_HAMM_VERSION,
        true
    );

    wp_localize_script('bruce-hamm', 'BruceHammSite', [
        'formspreeFormId' => apply_filters('bruce_hamm_formspree_id', 'maqgqnee'),
        'searchPages' => bruce_hamm_search_pages(),
    ]);
}

add_action('wp_enqueue_scripts', 'bruce_hamm_enqueue_assets');

function bruce_hamm_preconnect_fonts($urls, $relation_type)
{
    if ('preconnect' !== $relation_type) {
        return $urls;
    }

    $urls[] = [
        'href' => 'https://fonts.googleapis.com',
    ];
    $urls[] = [
        'href' => 'https://fonts.gstatic.com',
        'crossorigin',
    ];

    return $urls;
}

add_filter('wp_resource_hints', 'bruce_hamm_preconnect_fonts', 10, 2);
