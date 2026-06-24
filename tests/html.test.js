import { describe, it, expect } from 'vitest';
import { readFileSync, existsSync } from 'node:fs';
import { resolve } from 'node:path';

const root = resolve(import.meta.dirname, '..');

function readPage(filename) {
  return readFileSync(resolve(root, filename), 'utf8');
}

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

const navLinks = [
  'biography.html',
  'gallery.html',
  'music.html',
  'videos.html',
  'events.html',
  'lessons.html',
  'contact.html',
];

describe('shared site structure', () => {
  pages.forEach((page) => {
    describe(page, () => {
      const html = readPage(page);

      it('includes responsive viewport metadata', () => {
        expect(html).toContain('name="viewport"');
        expect(html).toContain('width=device-width');
      });

      it('loads shared styles and main javascript', () => {
        expect(html).toContain('href="css/styles.css"');
        expect(html).toContain('<script src="js/main.js"></script>');
      });

      it('includes ambient background and site header', () => {
        expect(html).toContain('class="ambient"');
        expect(html).toContain('id="particle-canvas"');
        expect(html).toContain('class="site-header"');
        expect(html).toContain('class="site-nav"');
      });

      it('includes full primary navigation', () => {
        navLinks.forEach((href) => {
          expect(html).toContain(`href="${href}"`);
        });
      });

      it('includes footer year placeholder', () => {
        expect(html).toContain('id="year"');
        expect(html).toContain('Bruce Hamm');
      });
    });
  });
});

describe('index.html', () => {
  const html = readPage('index.html');

  it('includes hero content and call-to-action links', () => {
    expect(html).toContain('The Art of');
    expect(html).toContain('href="biography.html"');
    expect(html).toContain('href="lessons.html"');
  });

  it('includes hero stats, carousel above featured photo', () => {
    expect(html).toContain('class="hero-stats"');
    expect(html).toContain('hero-carousel-wrap');
    expect(html).toContain('hero-slider');
    expect(html).toContain('hero-main');
    expect(html).toContain('hero-main-row');
    expect(html).toContain('hero-panel-content');
    expect(html).toContain('hero-panel-stats');
    expect(html).toContain('images/homepage-hero.png');
    expect(html).toContain('images/homepage-bg/bruce-portrait.png');
    expect(html.indexOf('hero-carousel-wrap')).toBeLessThan(html.indexOf('hero-main'));
    expect(html.indexOf('hero-panel-image')).toBeLessThan(html.indexOf('hero-panel-content'));
    expect(html.indexOf('hero-panel-content')).toBeLessThan(html.indexOf('hero-panel-stats'));
  });
});

describe('biography.html', () => {
  const html = readPage('biography.html');

  it('includes timeline biography blocks', () => {
    expect(html).toContain('Beginnings at Ali Akbar College');
    expect(html).toContain('Formal Discipleship');
    expect(html).toContain('Performances &amp; Collaborations');
    expect(html).toContain('Continuing the Legacy');
  });

  it('includes collaborator highlights and CTA band', () => {
    expect(html).toContain('Zakir Hussain');
    expect(html).toContain('Swapan Chaudhuri');
    expect(html).toContain('href="events.html"');
    expect(html).toContain('href="lessons.html"');
  });
});

describe('music.html', () => {
  const html = readPage('music.html');

  it('includes both album covers', () => {
    expect(html).toContain('images/morning-ragas-cover.png');
    expect(html).toContain('images/evening-ragas-cover.png');
    expect(html).toContain('Morning Ragas');
    expect(html).toContain('Evening Ragas');
  });

  it('includes morning ragas playable tracks', () => {
    expect(html).toContain('Rag Jogia Kalengra – Tintal');
    expect(html).toContain('Rag Jogia Kalengra – Jhaptal');
    expect(html).toContain('Rag Bilaskhani Todi Alap Jor – Slow Tintal');
    expect(html).toContain('src="audio/1-Audio-Track-6.mp3"');
    expect(html).toContain('src="audio/2-Audio-Track-11.mp3"');
    expect(html).toContain('src="audio/3-Audio-Track-7.mp3"');
  });

  it('includes evening ragas coming soon tracklist', () => {
    expect(html).toContain('Coming Soon');
    expect(html).toContain('Rag Hem Bihag – Alap &amp; Jor');
    expect(html).toContain('Rag Gawati – Gat in Tintal');
    expect(html).toContain('22.25');
    expect(html).toContain('10.26');
  });

  it('includes other tracks with audio players', () => {
    expect(html).toContain('Other Tracks');
    expect(html).toContain('Chandranadan – Alap');
    expect(html).toContain('Chandranandan – Fast Gat w/ Marcus Wise, tabla');
    expect(html).toContain('src="audio/Chandranandan-Alap-Mono.mp3"');
    expect(html).toContain('src="audio/Chandranandan-Fast-Gat.mp3"');
  });

  it('includes eight audio elements total', () => {
    expect(html.match(/<audio\b/g)?.length).toBe(8);
  });
});

describe('events.html', () => {
  const html = readPage('events.html');

  it('includes upcoming and past event sections', () => {
    expect(html).toContain('Upcoming Events');
    expect(html).toContain('Past Events');
    expect(html).toContain('events-section');
    expect(html).toContain('event-list');
    expect(html).toContain('Path of Raga');
    expect(html).toContain('Joyful Summer Evening of Raga');
    expect(html).toContain('images/events/path-of-raga.png');
    expect(html).toContain('images/events/joyful-summer-evening-of-raga.png');
  });
});

describe('contact.html', () => {
  const html = readPage('contact.html');

  it('includes required contact form fields', () => {
    expect(html).toContain('class="contact-form"');
    expect(html).toContain('id="name"');
    expect(html).toContain('id="email"');
    expect(html).toContain('id="message"');
    expect(html).toContain('required');
  });

  it('submits to Formspree with spam protection and status feedback', () => {
    expect(html).toContain('https://formspree.io/f/maqgqnee');
    expect(html).toContain('name="_gotcha"');
    expect(html).toContain('name="_subject"');
    expect(html).toContain('class="form-status"');
    expect(html).toContain('js/site-config.js');
  });
});

describe('asset files', () => {
  const assets = [
    'images/homepage-hero.png',
    'images/homepage-bg/bruce-portrait.png',
    'images/homepage-bg/duo-performance.png',
    'images/homepage-bg/sitar-closeup.png',
    'images/homepage-bg/outdoor-group.png',
    'images/homepage-bg/stage-group.png',
    'images/events/path-of-raga.png',
    'images/events/joyful-summer-evening-of-raga.png',
    'images/morning-ragas-cover.png',
    'images/evening-ragas-cover.png',
    'audio/1-Audio-Track-6.mp3',
    'audio/2-Audio-Track-11.mp3',
    'audio/3-Audio-Track-7.mp3',
    'audio/Chandranandan-Alap-Mono.mp3',
    'audio/Chandranandan-Vilambit-Tintal_final_mono.mp3',
    'audio/Hindol-Hem-Vilambit.mp3',
    'audio/BhoopMand_web_final.mp3',
    'audio/Chandranandan-Fast-Gat.mp3',
  ];

  assets.forEach((asset) => {
    it(`includes ${asset} on disk`, () => {
      expect(existsSync(resolve(root, asset))).toBe(true);
    });
  });
});
