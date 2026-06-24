import { describe, it, expect } from 'vitest';
import { readFileSync } from 'node:fs';
import { resolve } from 'node:path';

const css = readFileSync(resolve(import.meta.dirname, '../css/styles.css'), 'utf8');

const requiredSelectors = [
  '.site-header',
  '.site-header.scrolled',
  '.site-nav',
  '.site-nav.open',
  '.nav-toggle',
  '.hero',
  '.page-hero',
  '.page-title',
  '.bio-layout',
  '.bio-block',
  '.music-layout',
  '.album-column',
  '.album-cover',
  '.track-list',
  '.track-card',
  '.coming-soon',
  '.other-tracks',
  '.tracklist-preview',
  '.contact-form',
  '.form-group',
  '.cta-band',
  '.site-footer',
  '.footer-inner',
  '.footer-bottom',
  '.brand-logo',
  '.lessons-photo',
  '.reveal.visible',
  '.ambient',
  '#particle-canvas',
];

describe('styles.css', () => {
  it('defines core layout and component selectors', () => {
    requiredSelectors.forEach((selector) => {
      expect(css).toContain(selector);
    });
  });

  it('uses the site theme variables', () => {
    expect(css).toContain('--accent: #c9a227');
    expect(css).toContain('--accent-muted: #9a8868');
    expect(css).toContain('--font-display: "Cormorant Garamond"');
    expect(css).toContain('--font-body: "Source Sans 3"');
  });

  it('includes ambient background elements', () => {
    expect(css).toContain('.grid-overlay');
    expect(css).toContain('.gradient-orb');
  });

  it('includes responsive navigation and music layout rules', () => {
    expect(css).toContain('@media (max-width: 768px)');
    expect(css).toContain('.music-layout');
    expect(css).toContain('grid-template-columns: 1fr');
  });

  it('includes unfold animation styles for scroll reveals', () => {
    expect(css).toContain('rotateX(16deg)');
    expect(css).toContain('perspective: 1400px');
    expect(css).toContain('@keyframes hero-bg-marquee');
  });

  it('styles the circular brand logo and footer layout', () => {
    expect(css).toContain('.brand-logo');
    expect(css).toContain('border-radius: 50%');
    expect(css).toContain('.footer-inner');
    expect(css).toContain('.footer-bottom');
    expect(css).toContain('.lessons-photo');
  });

  it('uses masonry-style gallery spacing and portrait framing', () => {
    expect(css).toContain('.gallery-grid');
    expect(css).toContain('column-count: 2');
    expect(css).toContain('.gallery-item-portrait');
    expect(css).toContain('object-fit: initial');
  });

  it('respects reduced motion preferences', () => {
    expect(css).toContain('@media (prefers-reduced-motion: reduce)');
    expect(css).toContain('#particle-canvas');
    expect(css).toContain('display: none');
  });
});
