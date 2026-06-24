import { describe, it, expect } from 'vitest';
import { readFileSync } from 'node:fs';
import { resolve } from 'node:path';
import {
  buildEventCard,
  buildPastHtml,
  buildUpcomingHtml,
} from '../js/events-page.js';

const root = resolve(import.meta.dirname, '..');
const eventsData = JSON.parse(readFileSync(resolve(root, 'content/events.json'), 'utf8'));

describe('content/events.json', () => {
  it('includes past events with poster paths', () => {
    expect(eventsData.past).toHaveLength(2);
    expect(eventsData.past[0].poster).toBe('images/events/path-of-raga.png');
    expect(eventsData.past[1].poster).toBe('images/events/joyful-summer-evening-of-raga.png');
  });
});

describe('buildUpcomingHtml', () => {
  it('shows empty message when there are no upcoming events', () => {
    const html = buildUpcomingHtml(eventsData);

    expect(html).toContain('events-empty');
    expect(html).toContain(eventsData.emptyMessage);
    expect(html).toContain('href="contact.html"');
  });

  it('renders upcoming events in date order', () => {
    const html = buildUpcomingHtml({
      emptyMessage: 'None yet.',
      upcoming: [
        {
          title: 'Later Event',
          date: '2026-12-01',
          dateDisplay: 'December 1, 2026',
          venue: 'Venue B',
          detail: 'Details B',
        },
        {
          title: 'Sooner Event',
          date: '2026-06-01',
          dateDisplay: 'June 1, 2026',
          venue: 'Venue A',
          detail: 'Details A',
        },
      ],
    });

    expect(html.indexOf('Sooner Event')).toBeLessThan(html.indexOf('Later Event'));
    expect(html).toContain('event-list');
  });
});

describe('buildPastHtml', () => {
  it('renders past events newest first', () => {
    const html = buildPastHtml(eventsData);

    expect(html).toContain('Path of Raga');
    expect(html).toContain('Joyful Summer Evening of Raga');
    expect(html.indexOf('Path of Raga')).toBeLessThan(html.indexOf('Joyful Summer Evening of Raga'));
  });
});

describe('buildEventCard', () => {
  it('escapes HTML in event fields', () => {
    const html = buildEventCard({
      title: '<script>alert(1)</script>',
      date: '2026-01-01',
      dateDisplay: 'Jan 1',
      venue: 'Safe venue',
      detail: 'Safe detail',
    });

    expect(html).not.toContain('<script>');
    expect(html).toContain('&lt;script&gt;alert(1)&lt;/script&gt;');
  });

  it('includes optional ticket link', () => {
    const html = buildEventCard(eventsData.past[1]);

    expect(html).toContain('href="https://www.groupmuse.com"');
    expect(html).toContain('Groupmuse.com');
  });
});
