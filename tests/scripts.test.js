import { describe, it, expect } from 'vitest';
import { readFileSync } from 'node:fs';
import { resolve } from 'node:path';

const root = resolve(import.meta.dirname, '..');
const mainJs = readFileSync(resolve(root, 'js/main.js'), 'utf8');
const appJs = readFileSync(resolve(root, 'js/app.js'), 'utf8');

const sharedBehaviorSnippets = [
  "header.classList.toggle('scrolled', window.scrollY > 20)",
  'initYoutubeEmbeds();',
  'initExclusiveAudio();',
  'initParticles();',
  'initContactForm();',
  'initHeroSlider();',
  'https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&rel=0${startParam}',
  'Thank you — your message has been sent.',
  'slides.length !== dots.length) return',
  'Math.min(80, Math.floor((w * h) / 18000))',
];

describe('site javascript sources', () => {
  it('loads main.js on every html page', () => {
    const pages = [
      'index.html',
      'biography.html',
      'gallery.html',
      'music.html',
      'videos.html',
      'events.html',
      'lessons.html',
      'contact.html',
    ];

    pages.forEach((page) => {
      const html = readFileSync(resolve(root, page), 'utf8');
      expect(html).toContain('<script src="js/main.js"></script>');
    });
  });

  it('loads site-config.js only on the contact page', () => {
    const pages = [
      'index.html',
      'biography.html',
      'gallery.html',
      'music.html',
      'videos.html',
      'events.html',
      'lessons.html',
      'contact.html',
    ];

    pages.forEach((page) => {
      const html = readFileSync(resolve(root, page), 'utf8');
      if (page === 'contact.html') {
        expect(html).toContain('<script src="js/site-config.js"></script>');
      } else {
        expect(html).not.toContain('js/site-config.js');
      }
    });
  });

  sharedBehaviorSnippets.forEach((snippet) => {
    it(`keeps shared behavior in main.js and app.js: ${snippet}`, () => {
      expect(mainJs).toContain(snippet);
      expect(appJs).toContain(snippet);
    });
  });
});
