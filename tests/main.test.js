import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { initApp, initContactForm, initExclusiveAudio, initHeroSlider, initParticles, initYoutubeEmbeds } from '../js/app.js';

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
