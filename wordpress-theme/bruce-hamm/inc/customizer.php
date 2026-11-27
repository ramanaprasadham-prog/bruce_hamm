<?php
if (!defined('ABSPATH')) {
    exit;
}

function bruce_hamm_customize_register($wp_customize)
{
    $wp_customize->add_section('bruce_hamm_contact', [
        'title' => 'Contact Form',
        'priority' => 30,
        'description' => 'The Contact page sends messages through Formspree (formspree.io). Create a form there, then paste its ID or address here.',
    ]);

    $wp_customize->add_setting('bruce_hamm_formspree_id', [
        'default' => BRUCE_HAMM_DEFAULT_FORMSPREE_ID,
        'sanitize_callback' => 'bruce_hamm_normalize_formspree_id',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('bruce_hamm_formspree_id', [
        'label' => 'Formspree form ID',
        'description' => 'For example maqgqnee, or paste the whole address https://formspree.io/f/maqgqnee.',
        'section' => 'bruce_hamm_contact',
        'type' => 'text',
    ]);

    $wp_customize->add_section('bruce_hamm_footer', [
        'title' => 'Footer',
        'priority' => 31,
    ]);

    $wp_customize->add_setting('bruce_hamm_footer_tagline', [
        'default' => BRUCE_HAMM_DEFAULT_FOOTER_TAGLINE,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('bruce_hamm_footer_tagline', [
        'label' => 'Footer tagline',
        'section' => 'bruce_hamm_footer',
        'type' => 'textarea',
    ]);
}

add_action('customize_register', 'bruce_hamm_customize_register');
