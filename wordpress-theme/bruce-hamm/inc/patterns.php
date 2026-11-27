<?php
if (!defined('ABSPATH')) {
    exit;
}

function bruce_hamm_register_patterns()
{
    if (!function_exists('register_block_pattern')) {
        return;
    }

    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category('bruce-hamm', ['label' => 'Bruce Hamm']);
    }

    register_block_pattern('bruce-hamm/bio-section', [
        'title' => 'Biography section',
        'description' => 'A biography card with a small label (such as a year), a heading and text. Use it on the Biography page.',
        'categories' => ['bruce-hamm'],
        'content' => bruce_hamm_bio_section_block('Year or label', 'Section heading', 'Write this part of the biography here.'),
    ]);
}

add_action('init', 'bruce_hamm_register_patterns');
