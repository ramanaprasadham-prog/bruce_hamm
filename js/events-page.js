function escapeHtml(value) {
  return String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function buildTicketHtml(event) {
  if (!event.ticketUrl || !event.ticketLabel) return '';

  const prefix = event.ticketText ? `${escapeHtml(event.ticketText)} ` : '';
  return `${prefix}<a href="${escapeHtml(event.ticketUrl)}" target="_blank" rel="noopener noreferrer">${escapeHtml(event.ticketLabel)}</a>.`;
}

export function todayIsoDate(referenceDate = new Date()) {
  const year = referenceDate.getFullYear();
  const month = String(referenceDate.getMonth() + 1).padStart(2, '0');
  const day = String(referenceDate.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

function eventKey(event) {
  return `${event.date}|${event.title}`;
}

export function applyEventRollover(data, referenceDate = new Date()) {
  const today = todayIsoDate(referenceDate);
  const upcoming = data.upcoming ?? [];
  const past = data.past ?? [];

  const stillUpcoming = [];
  const rolledPast = [];

  for (const event of upcoming) {
    if (event.date && event.date < today) {
      rolledPast.push(event);
    } else {
      stillUpcoming.push(event);
    }
  }

  const seen = new Set();
  const mergedPast = [];

  for (const event of [...rolledPast, ...past]) {
    const key = eventKey(event);
    if (seen.has(key)) continue;
    seen.add(key);
    mergedPast.push(event);
  }

  return {
    ...data,
    upcoming: stillUpcoming,
    past: mergedPast,
  };
}

export function buildEventCard(event, { reveal = true } = {}) {
  const posterHtml = event.poster
    ? `<div class="event-poster">
        <img src="${escapeHtml(event.poster)}" alt="${escapeHtml(event.posterAlt || event.title)}">
      </div>`
    : '';

  const ticketHtml = buildTicketHtml(event);
  const detailHtml = ticketHtml
    ? `${escapeHtml(event.detail)} ${ticketHtml}`
    : escapeHtml(event.detail);

  const revealClass = reveal ? ' reveal' : '';

  return `<li class="event-card${revealClass}">
    ${posterHtml}
    <time class="event-date" datetime="${escapeHtml(event.date)}">${escapeHtml(event.dateDisplay)}</time>
    <h3 class="event-title">${escapeHtml(event.title)}</h3>
    <p class="event-venue">${escapeHtml(event.venue)}</p>
    <p class="event-detail">${detailHtml}</p>
  </li>`;
}

export function buildUpcomingHtml(data) {
  if (!data.upcoming?.length) {
    const message = escapeHtml(
      data.emptyMessage || 'Coming soon. New concert dates and appearances will be posted here.'
    );
    return `<div class="events-empty">
      <p>${message}</p>
      <p>For booking inquiries, please <a href="contact.html">get in touch</a>.</p>
    </div>`;
  }

  const items = [...data.upcoming]
    .sort((a, b) => String(a.date).localeCompare(String(b.date)))
    .map((event) => buildEventCard(event))
    .join('');

  return `<ul class="event-list">${items}</ul>`;
}

export function buildPastHtml(data) {
  if (!data.past?.length) {
    return `<div class="events-empty"><p>No past events listed yet.</p></div>`;
  }

  const items = [...data.past]
    .sort((a, b) => String(b.date).localeCompare(String(a.date)))
    .map((event) => buildEventCard(event))
    .join('');

  return `<ul class="event-list">${items}</ul>`;
}

export async function initEventsPage(options = {}) {
  const fetchImpl = options.fetchImpl || fetch;
  const upcomingRoot = document.getElementById('events-upcoming');
  const pastRoot = document.getElementById('events-past');

  if (!upcomingRoot || !pastRoot) return;

  try {
    const response = await fetchImpl('content/events.json');
    if (!response.ok) throw new Error(`Failed to load events (${response.status})`);

    const data = await response.json();
    const displayData = applyEventRollover(data, options.referenceDate);
    upcomingRoot.innerHTML = buildUpcomingHtml(displayData);
    pastRoot.innerHTML = buildPastHtml(displayData);

    document.querySelectorAll('#events-upcoming .reveal, #events-past .reveal').forEach((el) => {
      el.classList.add('visible');
    });
  } catch {
    upcomingRoot.innerHTML = `<div class="events-empty">
      <p>Events are temporarily unavailable. Please check back soon.</p>
      <p>For booking inquiries, please <a href="contact.html">get in touch</a>.</p>
    </div>`;
    pastRoot.innerHTML = `<div class="events-empty"><p>Unable to load past events.</p></div>`;
  }
}

if (document.getElementById('events-upcoming')) {
  initEventsPage();
}
