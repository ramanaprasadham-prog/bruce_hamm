<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(is_front_page() ? 'page-home' : ''); ?>>
<?php wp_body_open(); ?>
  <div class="ambient" aria-hidden="true">
    <canvas id="particle-canvas"></canvas>
    <div class="gradient-orb orb-1"></div>
    <div class="gradient-orb orb-2"></div>
    <div class="gradient-orb orb-3"></div>
    <div class="grid-overlay"></div>
  </div>

  <header class="site-header">
    <div class="header-inner">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="brand">
        <span class="brand-text">Bruce Hamm</span>
      </a>
      <nav class="site-nav" aria-label="Main navigation">
        <a href="<?php echo esc_url(bruce_hamm_page_url('biography')); ?>" class="<?php echo esc_attr(bruce_hamm_nav_class('biography')); ?>">Biography</a>
        <a href="<?php echo esc_url(bruce_hamm_page_url('gallery')); ?>" class="<?php echo esc_attr(bruce_hamm_nav_class('gallery')); ?>">Gallery</a>
        <a href="<?php echo esc_url(bruce_hamm_page_url('music')); ?>" class="<?php echo esc_attr(bruce_hamm_nav_class('music')); ?>">Music</a>
        <a href="<?php echo esc_url(bruce_hamm_page_url('videos')); ?>" class="<?php echo esc_attr(bruce_hamm_nav_class('videos')); ?>">Videos</a>
        <a href="<?php echo esc_url(bruce_hamm_page_url('events')); ?>" class="<?php echo esc_attr(bruce_hamm_nav_class('events')); ?>">Events</a>
        <a href="<?php echo esc_url(bruce_hamm_page_url('lessons')); ?>" class="<?php echo esc_attr(bruce_hamm_nav_class('lessons')); ?>">Lessons</a>
        <a href="<?php echo esc_url(bruce_hamm_page_url('contact')); ?>" class="<?php echo esc_attr(bruce_hamm_nav_class('contact')); ?>">Contact</a>
      </nav>
      <form class="site-search" role="search">
        <label class="visually-hidden" for="site-search-q">Search the site</label>
        <input id="site-search-q" class="site-search-input" type="search" name="s" placeholder="Search" autocomplete="off" aria-autocomplete="list" aria-controls="site-search-results">
        <button class="site-search-submit" type="submit" aria-label="Search">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </button>
        <div class="site-search-results" id="site-search-results" hidden role="listbox"></div>
      </form>
      <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>
