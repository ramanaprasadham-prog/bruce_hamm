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

function bruce_hamm_page_hero()
{
    ?>
  <section class="page-hero">
    <div class="page-hero-inner">
      <?php $eyebrow = bruce_hamm_get('eyebrow'); ?>
      <?php if ('' !== $eyebrow) : ?>
        <p class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></p>
      <?php endif; ?>
      <h1 class="page-title reveal"><?php echo esc_html(get_the_title(bruce_hamm_current_page_id())); ?></h1>
      <?php $subtitle = bruce_hamm_get('subtitle'); ?>
      <?php if ('' !== $subtitle) : ?>
        <p class="page-subtitle reveal"><?php echo esc_html($subtitle); ?></p>
      <?php endif; ?>
    </div>
  </section>
    <?php
}

/** The text of the page editor, rendered the way WordPress normally renders it. */
function bruce_hamm_page_body()
{
    $post = get_post(bruce_hamm_current_page_id());
    if (!$post) {
        return '';
    }

    return apply_filters('the_content', $post->post_content);
}

/** The page editor text as one plain paragraph (used for the home page headline text). */
function bruce_hamm_page_body_plain()
{
    $post = get_post(bruce_hamm_current_page_id());
    if (!$post) {
        return '';
    }

    return trim(preg_replace('/\s+/', ' ', wp_strip_all_tags(strip_shortcodes($post->post_content))));
}

function bruce_hamm_slider($slides, $label)
{
    $slides = array_values(array_filter((array) $slides, function ($slide) {
        return !empty($slide['image']);
    }));
    if (!$slides) {
        return;
    }
    ?>
        <div class="hero-slider reveal" data-autoplay="6000">
          <div class="hero-slider-viewport">
            <div class="hero-slider-track">
              <?php foreach ($slides as $i => $slide) : ?>
                <figure class="hero-slide<?php echo 0 === $i ? ' is-active' : ''; ?>">
                  <div class="carousel-slide-frame">
                    <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['alt']); ?>">
                  </div>
                </figure>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="hero-slider-dots" role="tablist" aria-label="<?php echo esc_attr($label); ?>">
            <?php foreach ($slides as $i => $slide) : ?>
              <button type="button" class="hero-slider-dot<?php echo 0 === $i ? ' is-active' : ''; ?>" aria-label="<?php echo esc_attr('Show photo ' . ($i + 1)); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"></button>
            <?php endforeach; ?>
          </div>
        </div>
    <?php
}

/**
 * Pulls the video id and optional start time (in seconds) out of a YouTube address.
 *
 * @return array{id:string,start:int}|null
 */
function bruce_hamm_parse_youtube($url)
{
    $url = trim((string) $url);
    if ('' === $url) {
        return null;
    }

    $id = '';
    if (preg_match('/^[A-Za-z0-9_-]{11}$/', $url)) {
        $id = $url;
    } elseif (preg_match('~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:embed/|shorts/|live/|v/)|[?&]v=)([A-Za-z0-9_-]{11})~', $url, $m)) {
        $id = $m[1];
    }
    if ('' === $id) {
        return null;
    }

    $start = 0;
    if (preg_match('/[?&#](?:t|start)=([0-9hms]+)/', $url, $m)) {
        $time = $m[1];
        if (preg_match('/^\d+$/', $time)) {
            $start = (int) $time;
        } elseif (preg_match('/^(?:(\d+)h)?(?:(\d+)m)?(?:(\d+)s)?$/', $time, $parts)) {
            $start = ((int) ($parts[1] ?? 0)) * 3600 + ((int) ($parts[2] ?? 0)) * 60 + ((int) ($parts[3] ?? 0));
        }
    }

    return ['id' => $id, 'start' => $start];
}

/* ------------------------------------------------------------------------
 * Contact form (Formspree)
 * --------------------------------------------------------------------- */

const BRUCE_HAMM_DEFAULT_FORMSPREE_ID = 'maqgqnee';

/**
 * Accepts a bare form id or a pasted Formspree address (https://formspree.io/f/xxxx)
 * and returns just the id, or '' when it does not look valid.
 */
function bruce_hamm_normalize_formspree_id($value)
{
    $value = trim((string) $value);
    if ('' === $value) {
        return '';
    }

    if (preg_match('~formspree\.io/f/([A-Za-z0-9]+)/?(?:[?#].*)?$~i', $value, $m)) {
        $value = $m[1];
    }

    return preg_match('/^[A-Za-z0-9]+$/', $value) ? $value : '';
}

function bruce_hamm_formspree_id()
{
    $saved = get_theme_mod('bruce_hamm_formspree_id', BRUCE_HAMM_DEFAULT_FORMSPREE_ID);
    $id = bruce_hamm_normalize_formspree_id($saved);

    return (string) apply_filters('bruce_hamm_formspree_id', $id);
}


/** Escapes $text and turns the first occurrence of $phrase into a link. */
function bruce_hamm_link_phrase($text, $phrase, $url)
{
    $position = stripos($text, $phrase);
    if (false === $position) {
        return esc_html($text);
    }

    return esc_html(substr($text, 0, $position))
        . '<a href="' . esc_url($url) . '">' . esc_html(substr($text, $position, strlen($phrase))) . '</a>'
        . esc_html(substr($text, $position + strlen($phrase)));
}
