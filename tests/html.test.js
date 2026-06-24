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

      it('includes Path of Raga branding in the header', () => {
        expect(html).toContain('class="brand-text">Path of Raga</span>');
        expect(html).toContain('src="images/logo.png"');
        expect(html).toContain('alt="Path of Raga"');
      });

      it('includes expanded footer with navigation links', () => {
        expect(html).toContain('id="year"');
        expect(html).toContain('footer-inner');
        expect(html).toContain('footer-bottom');
        expect(html).toContain('footer-logo');
        expect(html).toContain('aria-label="Footer navigation"');
        expect(html).toContain('href="lessons.html"');
        expect(html).toContain('href="contact.html"');
      });
    });
  });
});

describe('index.html', () => {
  const html = readPage('index.html');

  it('uses Path of Raga in the page title', () => {
    expect(html).toContain('<title>Path of Raga — Sarod Virtuoso</title>');
  });

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
    expect(html).not.toContain('images/homepage-bg/bruce-portrait.png');
    expect(html.indexOf('hero-carousel-wrap')).toBeLessThan(html.indexOf('hero-main'));
    expect(html.indexOf('hero-panel-image')).toBeLessThan(html.indexOf('hero-panel-content'));
    expect(html.indexOf('hero-panel-content')).toBeLessThan(html.indexOf('hero-panel-stats'));
  });

  it('includes four homepage carousel slides with matching dot controls', () => {
    expect(html.match(/<figure class="hero-slide/g)?.length).toBe(4);
    expect(html.match(/<button type="button" class="hero-slider-dot/g)?.length).toBe(4);
    expect(html).toContain('images/homepage-bg/duo-performance.png');
    expect(html).toContain('images/homepage-bg/sitar-closeup.png');
    expect(html).toContain('images/homepage-bg/outdoor-group.png');
    expect(html).toContain('images/homepage-bg/stage-group.png');
  });
});

describe('gallery.html', () => {
  const html = readPage('gallery.html');

  it('includes studio portrait and other gallery photos', () => {
    expect(html).toContain('images/gallery/bruce-portrait.png');
    expect(html).toContain('gallery-item-portrait');
    expect(html).toContain('images/gallery/ensemble-performance.png');
    expect(html).toContain('images/gallery/bruce-with-gregg-johnson.png');
    expect(html).toContain('images/gallery/bruce-with-friend.png');
    expect(html).toContain('images/gallery/bruce-2016-calcutta.png');
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
    expect(html).toContain('View Events');
    expect(html).toContain('href="events.html"');
    expect(html).toContain('href="lessons.html"');
    expect(html).toContain('Learn Sarod and Sitar');
  });
});

describe('lessons.html', () => {
  const html = readPage('lessons.html');

  it('includes teaching biography and group lesson photo', () => {
    expect(html).toContain('Bruce is an experienced teacher');
    expect(html).toContain('Ali Akbar Khan College in 1982');
    expect(html).toContain('private instrumental lessons and vocal lessons');
    expect(html).toContain('lessons-photo');
    expect(html).toContain('images/lessons/group-lesson.png');
    expect(html).toContain('Inquire About Lessons');
  });
});

describe('videos.html', () => {
  const html = readPage('videos.html');

  it('includes embedded performance videos', () => {
    expect(html).toContain('video-grid');
    expect(html).toContain('video-card');
    expect(html).toContain('data-youtube-id');
    expect(html).toContain('video-play-btn');
    expect(html).toContain('data-youtube-id="Nql_MzalBMY"');
    expect(html).toContain('https://www.youtube.com/watch?v=Nql_MzalBMY');
  });

  it('links to Joanna Mack YouTube channel', () => {
    expect(html).toContain('https://www.youtube.com/@JoannaMack');
    expect(html).toContain('Joanna Mack on YouTube');
    expect(html).toContain('images/videos/joanna-mack.png');
  });

  it('links to Bruce Hamm YouTube channel', () => {
    expect(html).toContain('https://www.youtube.com/@oobssarode');
    expect(html).toContain('Bruce Hamm on YouTube');
    expect(html).toContain('images/gallery/bruce-portrait.png');
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

  it('loads events from CMS data via events-page.js', () => {
    expect(html).toContain('Upcoming Events');
    expect(html).toContain('Past Events');
    expect(html).toContain('events-section');
    expect(html).toContain('id="events-upcoming"');
    expect(html).toContain('id="events-past"');
    expect(html).toContain('js/events-page.js');
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

describe('page titles', () => {
  const titles = {
    'index.html': 'Path of Raga — Sarod Virtuoso',
    'biography.html': 'Biography — Path of Raga',
    'gallery.html': 'Gallery — Path of Raga',
    'music.html': 'Music — Path of Raga',
    'videos.html': 'Videos — Path of Raga',
    'events.html': 'Events — Path of Raga',
    'lessons.html': 'Lessons — Path of Raga',
    'contact.html': 'Contact — Path of Raga',
  };

  Object.entries(titles).forEach(([page, title]) => {
    it(`${page} uses the Path of Raga title`, () => {
      expect(readPage(page)).toContain(`<title>${title}</title>`);
    });
  });
});

describe('asset files', () => {
  const assets = [
    'images/homepage-hero.png',
    'images/lessons/group-lesson.png',
    'images/videos/joanna-mack.png',
    'images/logo.png',
    'images/gallery/bruce-portrait.png',
    'images/gallery/ensemble-performance.png',
    'images/gallery/bruce-with-gregg-johnson.png',
    'images/gallery/bruce-with-friend.png',
    'images/gallery/bruce-2016-calcutta.png',
    'images/homepage-bg/duo-performance.png',
    'images/homepage-bg/sitar-closeup.png',
    'images/homepage-bg/outdoor-group.png',
    'images/homepage-bg/stage-group.png',
    'content/events.json',
    'admin/config.yml',
    'admin/index.html',
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
