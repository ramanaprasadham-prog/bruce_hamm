<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Starting text for pages whose body is edited in the normal WordPress page editor.
 * These are written into the page once (on activation / first load) and are never
 * overwritten afterwards, so edits made in the dashboard always win.
 */

function bruce_hamm_paragraph_block($text)
{
    return "<!-- wp:paragraph -->\n<p>" . esc_html($text) . "</p>\n<!-- /wp:paragraph -->";
}

function bruce_hamm_bio_section_block($year, $title, $text)
{
    return '<!-- wp:group {"className":"bio-block reveal"} -->' . "\n"
        . '<div class="wp-block-group bio-block reveal">'
        . '<!-- wp:paragraph {"className":"bio-year"} -->' . "\n"
        . '<p class="bio-year">' . esc_html($year) . "</p>\n"
        . "<!-- /wp:paragraph -->\n\n"
        . "<!-- wp:heading -->\n"
        . '<h2 class="wp-block-heading">' . esc_html($title) . "</h2>\n"
        . "<!-- /wp:heading -->\n\n"
        . bruce_hamm_paragraph_block($text)
        . "</div>\n"
        . "<!-- /wp:group -->\n\n";
}

function bruce_hamm_default_biography_content()
{
    $sections = [
        [
            '1974',
            'Ali Akbar College',
            'Bruce Hamm is a disciple of the revered maestro Ustad Ali Akbar Khan. He began studying the sitar and vocal music with Khansahib at Ali Akbar College of Music in 1974. After several years of studying the sitar, Bruce decided to switch his main instrument to the sarod. He pursued a serious and continuous study of classical instrumental and vocal music with his guru over the span of nearly four decades.',
        ],
        [
            '1981',
            'Formal Discipleship',
            "In 1981 he had the honor of tying the thread (Gandha Bandhan) with Khansahib to become a formal disciple. At that time he became an instructor at the Ali Akbar College of Music. He continued as an assistant teacher at the AACM until his guru's death in 2009. Bruce was one of a handful of disciples chosen by Khansahib to teach at the AACM after his passing.",
        ],
        [
            'Stage',
            'Performances & Collaborations',
            "Bruce had the opportunity on multiple occasions to accompany his Guru Ali Akbar Khan onstage. He regularly performs solo concerts throughout the US, Europe and India. He has also had the privilege of performing with many of India's leading artists, including Swapan Chaudhuri, Zakir Hussain and Anindo Chatterjee.",
        ],
        [
            'Today',
            'Continuing the Legacy',
            "Bruce continues his mentor's legacy today by teaching anyone who is willing to dedicate themselves to this music. He is co-founder, along with Joanna Mack, of the non-profit organization Path of Raga, which is committed to the preservation of the music of the Maihar Gharana.",
        ],
        [
            'Roots',
            'Roots',
            'Bruce was born into a musical family and immersed in music from early childhood. His father, Charles Hamm, was a composer and later a famous musicologist. His mother, Helen Hamm, was an opera singer. His younger brother Stu Hamm is a noted bassist. As a child Bruce played piano, trumpet, French horn and guitar. As a teenager he found his true love, Indian Classical Music.',
        ],
    ];

    $out = '';
    foreach ($sections as $section) {
        $out .= bruce_hamm_bio_section_block($section[0], $section[1], $section[2]);
    }

    return trim($out);
}

function bruce_hamm_default_home_content()
{
    return bruce_hamm_paragraph_block(
        'Bruce Hamm is a long-time disciple of Ustad Ali Akbar Khan — an accomplished performer and respected teacher in the California Bay Area, carrying forward a legacy of nearly five decades.'
    );
}

function bruce_hamm_default_lessons_content()
{
    $paragraphs = [
        'Bruce is an experienced teacher and enjoys sharing his knowledge with students of all ages and levels.',
        'He began teaching at the Ali Akbar Khan College in 1982, assisting beginning students with their instruments and helping them understand the compositions. He has taught both vocal and instrumental classes ever since.',
        'He began his studies on Sitar and then switched to Sarode during his time with Khansahib. He is able to teach Classical Hindustani music for Sitar, Sarode, or any other Indian or Western instrument. Khansahib always said you have to sing before you can play, so at least half of Bruce’s education was an immersion in the vocal tradition — The old dhrupads and khyals collected by Baba Allauddin Khan, and the many beautiful compositions of Khansahib.',
        'Please inquire for availability of private instrumental lessons and vocal lessons.',
    ];

    return implode("\n\n", array_map('bruce_hamm_paragraph_block', $paragraphs));
}

/**
 * Pages whose body text is edited in the page editor, with the placeholder text the
 * first version of this theme stored there (replaced once, only if still untouched).
 */
function bruce_hamm_editor_pages()
{
    return [
        'home' => [
            'default' => 'bruce_hamm_default_home_content',
            'legacy' => 'Bruce Hamm is a long-time disciple of Ustad Ali Akbar Khan — an accomplished performer and respected teacher in the California Bay Area.',
        ],
        'biography' => [
            'default' => 'bruce_hamm_default_biography_content',
            'legacy' => 'A lifelong journey through North Indian classical music under the guidance of a legendary maestro. Disciple of Ustad Ali Akbar Khan, instructor at Ali Akbar College of Music, and co-founder of Path of Raga.',
        ],
        'lessons' => [
            'default' => 'bruce_hamm_default_lessons_content',
            'legacy' => 'Private instrumental lessons and vocal study in North Indian classical music for sarod, sitar, and other instruments.',
        ],
    ];
}

const BRUCE_HAMM_CONTENT_VERSION = '2';

function bruce_hamm_seed_editor_content()
{
    foreach (bruce_hamm_editor_pages() as $slug => $config) {
        $page = get_page_by_path($slug);
        if (!$page) {
            continue;
        }

        $current = trim(wp_strip_all_tags($page->post_content));
        if ('' !== $current && $current !== $config['legacy']) {
            continue;
        }

        wp_update_post([
            'ID' => $page->ID,
            'post_content' => wp_slash(call_user_func($config['default'])),
        ]);
    }

    update_option('bruce_hamm_content_version', BRUCE_HAMM_CONTENT_VERSION);
}

function bruce_hamm_maybe_upgrade_content()
{
    if (get_option('bruce_hamm_content_version') === BRUCE_HAMM_CONTENT_VERSION) {
        return;
    }

    bruce_hamm_seed_editor_content();
}

add_action('init', 'bruce_hamm_maybe_upgrade_content', 20);
