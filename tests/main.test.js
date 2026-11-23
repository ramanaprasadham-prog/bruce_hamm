import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { initApp, initContactForm, initExclusiveAudio, initHeroSlider, initParticles, initYoutubeEmbeds, searchSite, similarSearches, textFromEvents, textFromPageHtml } from '../js/app.js';

function buildSiteFixture({ withAudio = false, withCanvas = false, withReveal = true } = {}) {
  document.body.innerHTML = `
    <header class="site-header"></header>
    <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
    <nav class="site-nav" aria-label="Main navigation">
      <a href="biography.html" class="nav-link">Biography</a>
      <a href="music.html" class="nav-link">Music</a>
      <a href="contact.html" class="nav-link nav-cta">Contact</a>
    </nav>
    <form class="site-search" role="search">
      <input class="site-search-input" type="search" name="q">
      <button class="site-search-submit" type="submit" aria-label="Search"></button>
      <div class="site-search-results" hidden></div>
    </form>
    ${withReveal ? '<p class="reveal">Reveal item</p><p class="reveal">Reveal item 2</p>' : ''}
    ${withCanvas ? '<canvas id="particle-canvas"></canvas>' : ''}
    ${withAudio ? '<audio id="a1"></audio><audio id="a2"></audio>' : ''}
    <span id="year"></span>
  `;
}

function mockAudioElement(id) {
  const audio = document.getElementById(id);
  audio.pause = vi.fn();
  return audio;
}

describe('initApp', () => {
  beforeEach(() => {
    document.body.innerHTML = '';
    window.scrollY = 0;
    vi.restoreAllMocks();
  });

  it('sets the current year in the footer element', () => {
    buildSiteFixture();
    initApp();

    expect(document.getElementById('year').textContent).toBe(String(new Date().getFullYear()));
  });

  it('toggles mobile navigation open and closed', () => {
    buildSiteFixture();
    initApp();

    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.site-nav');

    toggle.click();
    expect(nav.classList.contains('open')).toBe(true);
    expect(toggle.classList.contains('open')).toBe(true);
    expect(toggle.getAttribute('aria-expanded')).toBe('true');

    toggle.click();
    expect(nav.classList.contains('open')).toBe(false);
    expect(toggle.classList.contains('open')).toBe(false);
    expect(toggle.getAttribute('aria-expanded')).toBe('false');
  });

  it('closes navigation when a nav link is clicked', () => {
    buildSiteFixture();
    initApp();

    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.site-nav');

    toggle.click();
    nav.querySelector('a[href="music.html"]').click();

    expect(nav.classList.contains('open')).toBe(false);
    expect(toggle.classList.contains('open')).toBe(false);
    expect(toggle.getAttribute('aria-expanded')).toBe('false');
  });

  it('adds header scrolled class when page scrolls past threshold', () => {
    buildSiteFixture();
    initApp();

    const header = document.querySelector('.site-header');
    window.scrollY = 50;
    window.dispatchEvent(new Event('scroll'));

    expect(header.classList.contains('scrolled')).toBe(true);
  });

  it('removes header scrolled class when scroll returns to top', () => {
    buildSiteFixture();
    initApp();

    const header = document.querySelector('.site-header');
    window.scrollY = 50;
    window.dispatchEvent(new Event('scroll'));
    window.scrollY = 0;
    window.dispatchEvent(new Event('scroll'));

    expect(header.classList.contains('scrolled')).toBe(false);
  });

  it('reveals elements immediately when IntersectionObserver is unavailable', () => {
    const original = global.IntersectionObserver;
    delete global.IntersectionObserver;

    buildSiteFixture();
    initApp();

    document.querySelectorAll('.reveal').forEach((el) => {
      expect(el.classList.contains('visible')).toBe(true);
    });

    global.IntersectionObserver = original;
  });

  it('reveals elements when IntersectionObserver reports intersection', () => {
    let observerCallback;
    global.IntersectionObserver = vi.fn((callback) => {
      observerCallback = callback;
      return { observe: vi.fn(), unobserve: vi.fn() };
    });

    buildSiteFixture();
    initApp();

    const revealEl = document.querySelector('.reveal');
    observerCallback([{ isIntersecting: true, target: revealEl }]);

    expect(revealEl.classList.contains('visible')).toBe(true);

    vi.restoreAllMocks();
  });

  it('assigns staggered transition delays to reveal elements', () => {
    global.IntersectionObserver = vi.fn(() => ({
      observe: vi.fn(),
      unobserve: vi.fn(),
    }));

    buildSiteFixture();
    initApp();

    const reveals = document.querySelectorAll('.reveal');
    expect(reveals[0].style.transitionDelay).toBe('0s');
    expect(reveals[1].style.transitionDelay).toBe('0.08s');

    vi.restoreAllMocks();
  });
});

describe('searchSite', () => {
  it('returns matching pages for a query', () => {
    const results = searchSite('lessons');
    expect(results.some((page) => page.url === 'lessons.html')).toBe(true);
  });

  it('returns an empty list for blank queries', () => {
    expect(searchSite('   ')).toEqual([]);
  });

  it('ranks title matches above keyword matches', () => {
    const results = searchSite('music');
    expect(results[0].url).toBe('music.html');
  });

  it('matches words that appear only in the page body', () => {
    const pages = [
      {
        title: 'Music',
        url: 'music.html',
        snippet: 'Listen to Morning Ragas, Evening Ragas, and other sarod recordings.',
        keywords: 'music recordings',
        text: 'Rag Bilaskhani Todi Alap Jor. Chandranandan with Marcus Wise.',
      },
    ];

    const results = searchSite('todi', pages);
    expect(results).toHaveLength(1);
    expect(results[0].url).toBe('music.html');
    expect(results[0].snippet.toLowerCase()).toContain('todi');
  });

  it('requires every word to appear on the same page', () => {
    const pages = [
      {
        title: 'Music',
        url: 'music.html',
        snippet: 'Morning Ragas.',
        keywords: '',
        text: 'Rag Bilaskhani Todi.',
      },
    ];

    expect(searchSite('todi gallery', pages)).toEqual([]);
    expect(searchSite('todi', pages)).toHaveLength(1);
  });
});

describe('textFromEvents', () => {
  it('keeps visitor-facing event text and leaves out file paths', () => {
    const text = textFromEvents({
      emptyMessage: 'Coming soon.',
      upcoming: [],
      past: [
        {
          title: 'Joyful Summer Evening of Raga',
          date: '2026-05-16',
          dateDisplay: 'Saturday, May 16, 2026',
          venue: 'Blacow · Fremont, CA',
          detail: 'Joanna Mack (sitar), Bruce Hamm (sarod).',
          ticketUrl: 'https://www.groupmuse.com',
          ticketLabel: 'Groupmuse.com',
          poster: 'images/events/joyful-summer-evening-of-raga.png',
        },
      ],
    });

    expect(text).toContain('Joanna Mack');
    expect(text).toContain('Groupmuse.com');
    expect(text).toContain('Coming soon.');
    expect(text).not.toContain('images/events');
    expect(text).not.toContain('https://www.groupmuse.com');
  });
});

describe('similarSearches', () => {
  const pages = [
    {
      title: 'Music',
      url: 'music.html',
      snippet: 'Listen to Morning Ragas and other sarod recordings.',
      keywords: 'music recordings',
      text: 'Rag Bilaskhani Todi Alap Jor.',
    },
    {
      title: 'Gallery',
      url: 'gallery.html',
      snippet: 'Performance photos from decades on stage.',
      keywords: 'gallery photos',
      text: '',
    },
  ];

  it('suggests a close word when nothing matches exactly', () => {
    const suggestions = similarSearches('tody', pages);
    expect(suggestions.map((item) => item.query)).toContain('todi');
    expect(suggestions[0].pageTitle).toBe('Music');
  });

  it('does not suggest alternatives for an exact match', () => {
    expect(similarSearches('todi', pages)).toEqual([]);
  });

  it('returns nothing when the query is not close to the site', () => {
    expect(similarSearches('xyzzy', pages)).toEqual([]);
  });

  it('corrects a misspelled word inside a longer phrase', () => {
    const eventPage = [
      {
        title: 'Events',
        url: 'events.html',
        snippet: 'Upcoming concerts.',
        keywords: 'events',
        text: 'Student recital in Milpitas.',
      },
    ];

    const suggestions = similarSearches('studnt recital', eventPage);

    expect(suggestions[0].query).toBe('student recital');
    expect(suggestions[0].pageTitle).toBe('Events');
  });
});

describe('textFromPageHtml', () => {
  it('reads the main text and image descriptions, not the navigation', () => {
    const text = textFromPageHtml(`
      <header><a href="music.html">Music</a></header>
      <main>
        <h1>Events</h1>
        <p>Student recital in Milpitas.</p>
        <img alt="Bruce Hamm performing in Kolkata, 2019">
        <audio>Your browser does not support audio playback.</audio>
      </main>
      <footer>Contact</footer>
    `);

    expect(text).toContain('Milpitas');
    expect(text).toContain('Kolkata');
    expect(text).not.toContain('Contact');
    expect(text).not.toContain('does not support audio playback');
  });
});

describe('initSiteSearch', () => {
  it('shows matching results as the query is typed', () => {
    buildSiteFixture();
    initApp();

    const input = document.querySelector('.site-search-input');
    input.value = 'gallery';
    input.dispatchEvent(new Event('input'));

    const results = document.querySelector('.site-search-results');
    expect(results.hidden).toBe(false);
    expect(results.textContent).toContain('Gallery');
  });

  it('shows an empty message when nothing matches', () => {
    buildSiteFixture();
    initApp();

    const input = document.querySelector('.site-search-input');
    input.value = 'xyzzy';
    input.dispatchEvent(new Event('input'));

    expect(document.querySelector('.site-search-empty').textContent).toBe('No matching pages.');
  });

  it('offers similar searches for a close spelling', () => {
    buildSiteFixture();
    initApp();

    const input = document.querySelector('.site-search-input');
    input.value = 'galery';
    input.dispatchEvent(new Event('input'));

    expect(document.querySelector('.site-search-similar-label').textContent).toBe('Similar searches');
    const suggestion = document.querySelector('.site-search-similar-link');
    expect(suggestion.textContent.toLowerCase()).toContain('gallery');

    suggestion.click();
    expect(input.value.toLowerCase()).toContain('gallery');
    expect(document.querySelector('.site-search-result').textContent).toContain('Gallery');
  });

  it('hides results when Escape is pressed or the user clicks outside the form', () => {
    buildSiteFixture();
    initApp();

    const input = document.querySelector('.site-search-input');
    const results = document.querySelector('.site-search-results');
    input.value = 'gallery';
    input.dispatchEvent(new Event('input'));
    expect(results.hidden).toBe(false);

    input.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));
    expect(results.hidden).toBe(true);

    input.value = 'gallery';
    input.dispatchEvent(new Event('input'));
    document.body.dispatchEvent(new MouseEvent('click', { bubbles: true }));
    expect(results.hidden).toBe(true);
  });
});

describe('initExclusiveAudio', () => {
  beforeEach(() => {
    document.body.innerHTML = '';
    vi.restoreAllMocks();
  });

  it('pauses the previously playing track when a new track starts', () => {
    buildSiteFixture({ withAudio: true, withReveal: false });
    const audio1 = mockAudioElement('a1');
    const audio2 = mockAudioElement('a2');

    initExclusiveAudio();

    audio1.dispatchEvent(new Event('play'));
    audio2.dispatchEvent(new Event('play'));

    expect(audio1.pause).toHaveBeenCalledTimes(1);
  });

  it('does not pause the same track when it receives another play event', () => {
    buildSiteFixture({ withAudio: true, withReveal: false });
    const audio1 = mockAudioElement('a1');

    initExclusiveAudio();

    audio1.dispatchEvent(new Event('play'));
    audio1.dispatchEvent(new Event('play'));

    expect(audio1.pause).not.toHaveBeenCalled();
  });

  it('allows a previously paused track to play again after another track stops', () => {
    buildSiteFixture({ withAudio: true, withReveal: false });
    const audio1 = mockAudioElement('a1');
    const audio2 = mockAudioElement('a2');

    initExclusiveAudio();

    audio1.dispatchEvent(new Event('play'));
    audio2.dispatchEvent(new Event('play'));
    audio2.dispatchEvent(new Event('pause'));
    audio1.dispatchEvent(new Event('play'));

    expect(audio1.pause).toHaveBeenCalledTimes(1);
  });

  it('clears active track when playback ends', () => {
    buildSiteFixture({ withAudio: true, withReveal: false });
    const audio1 = mockAudioElement('a1');
    const audio2 = mockAudioElement('a2');

    initExclusiveAudio();

    audio1.dispatchEvent(new Event('play'));
    audio1.dispatchEvent(new Event('ended'));
    audio2.dispatchEvent(new Event('play'));

    expect(audio1.pause).not.toHaveBeenCalled();
  });
});

describe('initParticles', () => {
  beforeEach(() => {
    document.body.innerHTML = '';
    vi.restoreAllMocks();
  });

  it('returns early when the particle canvas is missing', () => {
    const rafSpy = vi.spyOn(window, 'requestAnimationFrame');

    initParticles();

    expect(rafSpy).not.toHaveBeenCalled();
  });

  it('returns early when reduced motion is preferred', () => {
    buildSiteFixture({ withCanvas: true, withReveal: false });
    vi.spyOn(window, 'matchMedia').mockReturnValue({ matches: true });

    const rafSpy = vi.spyOn(window, 'requestAnimationFrame');
    initParticles();

    expect(rafSpy).not.toHaveBeenCalled();
  });

  it('starts the animation loop when canvas is present', () => {
    buildSiteFixture({ withCanvas: true, withReveal: false });
    vi.spyOn(window, 'matchMedia').mockReturnValue({ matches: false });

    const canvas = document.getElementById('particle-canvas');
    canvas.getContext = vi.fn(() => ({
      clearRect: vi.fn(),
      beginPath: vi.fn(),
      arc: vi.fn(),
      fill: vi.fn(),
      moveTo: vi.fn(),
      lineTo: vi.fn(),
      stroke: vi.fn(),
    }));

    const rafSpy = vi.spyOn(window, 'requestAnimationFrame').mockReturnValue(1);
    initParticles();

    expect(canvas.getContext).toHaveBeenCalledWith('2d');
    expect(rafSpy).toHaveBeenCalled();
  });
});

describe('initContactForm', () => {
  beforeEach(() => {
    vi.restoreAllMocks();
    window.BruceHammSite = { formspreeFormId: 'YOUR_FORMSPREE_FORM_ID' };
    document.body.innerHTML = `
      <form class="contact-form" action="https://formspree.io/f/YOUR_FORMSPREE_FORM_ID" method="POST">
        <input type="text" name="name" value="Test User">
        <input type="email" name="email" value="visitor@example.com">
        <textarea name="message">Hello</textarea>
        <p class="form-status" hidden role="status"></p>
        <button type="submit">Send Message</button>
      </form>
    `;
  });

  it('shows a configuration error when Formspree ID is not set', () => {
    initContactForm();

    document.querySelector('.contact-form').requestSubmit();

    const status = document.querySelector('.form-status');
    expect(status.hidden).toBe(false);
    expect(status.textContent).toContain('js/site-config.js');
  });

  it('submits successfully through Formspree and shows confirmation', async () => {
    window.BruceHammSite = { formspreeFormId: 'abc123xy' };
    const fetchSpy = vi.spyOn(globalThis, 'fetch').mockResolvedValue({ ok: true });

    initContactForm();
    document.querySelector('.contact-form').requestSubmit();

    await vi.waitFor(() => {
      expect(document.querySelector('.form-status').textContent).toContain('Thank you');
    });

    expect(fetchSpy).toHaveBeenCalledWith(
      'https://formspree.io/f/abc123xy',
      expect.objectContaining({ method: 'POST' })
    );
  });

  it('uses the legacy email endpoint when an email address is configured', async () => {
    window.BruceHammSite = { formspreeFormId: 'owner@example.com' };
    const fetchSpy = vi.spyOn(globalThis, 'fetch').mockResolvedValue({ ok: true });

    initContactForm();
    document.querySelector('.contact-form').requestSubmit();

    await vi.waitFor(() => {
      expect(document.querySelector('.form-status').textContent).toContain('Thank you');
    });

    expect(fetchSpy).toHaveBeenCalledWith(
      'https://formspree.io/owner%40example.com',
      expect.objectContaining({ method: 'POST' })
    );
  });

  it('shows the server error and re-enables the button when Formspree rejects the message', async () => {
    window.BruceHammSite = { formspreeFormId: 'abc123xy' };
    vi.spyOn(globalThis, 'fetch').mockResolvedValue({
      ok: false,
      json: async () => ({ error: 'Form is closed.' }),
    });

    initContactForm();
    document.querySelector('.contact-form').requestSubmit();

    await vi.waitFor(() => {
      expect(document.querySelector('.form-status').textContent).toBe('Form is closed.');
    });

    const button = document.querySelector('[type="submit"]');
    expect(button.disabled).toBe(false);
    expect(button.textContent).toBe('Send Message');
  });

  it('shows a network error when the request cannot be sent', async () => {
    window.BruceHammSite = { formspreeFormId: 'abc123xy' };
    vi.spyOn(globalThis, 'fetch').mockRejectedValue(new Error('offline'));

    initContactForm();
    document.querySelector('.contact-form').requestSubmit();

    await vi.waitFor(() => {
      expect(document.querySelector('.form-status').textContent).toContain('Network error');
    });
  });
});

describe('initHeroSlider', () => {
  beforeEach(() => {
    vi.useFakeTimers();
    vi.spyOn(window, 'matchMedia').mockReturnValue({ matches: false });
    document.body.innerHTML = `
      <div class="hero-slider" data-autoplay="3000">
        <div class="hero-slider-track">
          <figure class="hero-slide is-active"></figure>
          <figure class="hero-slide"></figure>
          <figure class="hero-slide"></figure>
        </div>
        <div class="hero-slider-dots">
          <button type="button" class="hero-slider-dot is-active" aria-selected="true"></button>
          <button type="button" class="hero-slider-dot" aria-selected="false"></button>
          <button type="button" class="hero-slider-dot" aria-selected="false"></button>
        </div>
      </div>
    `;
  });

  afterEach(() => {
    vi.useRealTimers();
  });

  it('advances slides on dot click and autoplay', () => {
    initHeroSlider();

    document.querySelectorAll('.hero-slider-dot')[1].click();
    const slides = document.querySelectorAll('.hero-slide');
    expect(slides[1].classList.contains('is-active')).toBe(true);

    vi.advanceTimersByTime(3000);
    expect(slides[2].classList.contains('is-active')).toBe(true);
  });

  it('stops autoplay while the pointer is over the slider', () => {
    initHeroSlider();
    const slider = document.querySelector('.hero-slider');
    const slides = document.querySelectorAll('.hero-slide');

    slider.dispatchEvent(new Event('mouseenter'));
    vi.advanceTimersByTime(3000);

    expect(slides[0].classList.contains('is-active')).toBe(true);
  });

  it('does not autoplay when reduced motion is preferred', () => {
    vi.spyOn(window, 'matchMedia').mockReturnValue({ matches: true });
    initHeroSlider();

    vi.advanceTimersByTime(3000);

    expect(document.querySelectorAll('.hero-slide')[0].classList.contains('is-active')).toBe(true);
  });

  it('leaves the slider unchanged when the dots do not match the slides', () => {
    document.querySelector('.hero-slider-dot').remove();
    initHeroSlider();

    document.querySelector('.hero-slider-dot').click();

    expect(document.querySelectorAll('.hero-slide')[0].classList.contains('is-active')).toBe(true);
  });
});

describe('initYoutubeEmbeds', () => {
  beforeEach(() => {
    document.body.innerHTML = `
      <div class="video-lazy" data-youtube-id="abc123" data-youtube-title="Test performance video">
        <button type="button" class="video-play-btn">Play</button>
      </div>
    `;
  });

  it('replaces the lazy frame with a YouTube iframe on play click', () => {
    initYoutubeEmbeds();

    document.querySelector('.video-play-btn').click();

    const iframe = document.querySelector('.video-lazy iframe');
    expect(iframe).toBeTruthy();
    expect(iframe.src).toContain('youtube-nocookie.com/embed/abc123');
    expect(iframe.title).toBe('Test performance video');
    expect(iframe.allowFullscreen).toBe(true);
  });

  it('includes a start time when data-youtube-start is provided', () => {
    document.body.innerHTML = `
      <div class="video-lazy" data-youtube-id="xyz789" data-youtube-start="111" data-youtube-title="Timed video">
        <button type="button" class="video-play-btn">Play</button>
      </div>
    `;

    initYoutubeEmbeds();
    document.querySelector('.video-play-btn').click();

    expect(document.querySelector('iframe').src).toContain('start=111');
  });

  it('opens YouTube in a new tab when running from a file URL', () => {
    const openSpy = vi.spyOn(window, 'open').mockImplementation(() => null);
    Object.defineProperty(window, 'location', {
      configurable: true,
      value: { protocol: 'file:' },
    });

    initYoutubeEmbeds();
    document.querySelector('.video-play-btn').click();

    expect(openSpy).toHaveBeenCalledWith(
      'https://www.youtube.com/watch?v=abc123',
      '_blank',
      'noopener,noreferrer'
    );
  });
});
