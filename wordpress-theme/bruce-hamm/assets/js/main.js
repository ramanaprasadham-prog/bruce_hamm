(function () {
  'use strict';

  document.documentElement.classList.add('js');

  const yearEl = document.getElementById('year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  const header = document.querySelector('.site-header');
  if (header) {
    window.addEventListener(
      'scroll',
      () => {
        header.classList.toggle('scrolled', window.scrollY > 20);
      },
      { passive: true }
    );
  }

  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.site-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('open');
      toggle.classList.toggle('open', open);
      toggle.setAttribute('aria-expanded', String(open));
    });

    nav.querySelectorAll('.nav-link').forEach((link) => {
      link.addEventListener('click', () => {
        nav.classList.remove('open');
        toggle.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  const revealEls = document.querySelectorAll('.reveal');
  if (revealEls.length) {
    const sectionCounters = new Map();

    function showReveal(el) {
      el.classList.add('visible');
    }

    function isInViewport(el) {
      const rect = el.getBoundingClientRect();
      return rect.top < window.innerHeight * 0.95 && rect.bottom > 0;
    }

    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              showReveal(entry.target);
              observer.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.05, rootMargin: '0px 0px -5% 0px' }
      );

      revealEls.forEach((el) => {
        const sectionKey = el.closest('section')?.className || 'page';
        const index = sectionCounters.get(sectionKey) || 0;
        sectionCounters.set(sectionKey, index + 1);
        el.style.transitionDelay = `${Math.min(index * 0.08, 0.4)}s`;

        if (isInViewport(el)) {
          showReveal(el);
        } else {
          observer.observe(el);
        }
      });

      requestAnimationFrame(() => {
        revealEls.forEach((el) => {
          if (!el.classList.contains('visible') && isInViewport(el)) {
            showReveal(el);
          }
        });
      });
    } else {
      revealEls.forEach(showReveal);
    }
  }

  initYoutubeEmbeds();
  initExclusiveAudio();
  initParticles();
  initContactForm();
  initHeroSlider();
  initSiteSearch();

  const SITE_SEARCH_PAGES = (window.BruceHammSite && Array.isArray(window.BruceHammSite.searchPages) && window.BruceHammSite.searchPages.length)
    ? window.BruceHammSite.searchPages
    : [
    {
      title: 'Home',
      url: 'index.html',
      snippet: 'Sarod virtuoso, performer and teacher in the California Bay Area.',
      keywords: 'bruce hamm sarod sitar virtuoso ali akbar khan homepage',
    },
    {
      title: 'Biography',
      url: 'biography.html',
      snippet: 'Disciple of Ustad Ali Akbar Khan, performer, and teacher of the Maihar Gharana.',
      keywords: 'biography disciple ali akbar khan aacm gandha bandhan zakir hussain swapan chaudhuri path of raga stu hamm charles hamm',
    },
    {
      title: 'Gallery',
      url: 'gallery.html',
      snippet: 'Performance photos and archival images from decades on stage.',
      keywords: 'gallery photos images allaudin festival calcutta jabalpur swapan concert',
    },
    {
      title: 'Music',
      url: 'music.html',
      snippet: 'Listen to Morning Ragas, Evening Ragas, and other sarod recordings.',
      keywords: 'music recordings morning ragas evening ragas chandranandan jogia kalengra audio tracks',
    },
    {
      title: 'Videos',
      url: 'videos.html',
      snippet: 'Watch concert footage and performances on YouTube.',
      keywords: 'videos youtube performance concert rag marwa chandranandan',
    },
    {
      title: 'Events',
      url: 'events.html',
      snippet: 'Upcoming concerts and past appearances.',
      keywords: 'events concerts schedule upcoming past appearances',
    },
    {
      title: 'Lessons',
      url: 'lessons.html',
      snippet: 'Private instrumental lessons and vocal study in North Indian classical music.',
      keywords: 'lessons teaching students sarod sitar vocal class private lessons',
    },
    {
      title: 'Contact',
      url: 'contact.html',
      snippet: 'Reach out for performances, lessons, or general inquiries.',
      keywords: 'contact email inquire booking performances form',
    },
  ];

  function searchSite(query) {
    const terms = String(query || '')
      .toLowerCase()
      .trim()
      .split(/\s+/)
      .filter(Boolean);
    if (!terms.length) return [];

    return SITE_SEARCH_PAGES.map((page) => {
      const haystack = `${page.title} ${page.snippet} ${page.keywords}`.toLowerCase();
      if (!terms.every((term) => haystack.includes(term))) return null;
      const score = terms.reduce((sum, term) => {
        if (page.title.toLowerCase().includes(term)) return sum + 3;
        if (page.snippet.toLowerCase().includes(term)) return sum + 2;
        return sum + 1;
      }, 0);
      return { title: page.title, url: page.url, snippet: page.snippet, score };
    })
      .filter(Boolean)
      .sort((a, b) => b.score - a.score || a.title.localeCompare(b.title));
  }

  function initSiteSearch() {
    const form = document.querySelector('.site-search');
    if (!form) return;

    const input = form.querySelector('.site-search-input');
    const resultsEl = form.querySelector('.site-search-results');
    if (!input || !resultsEl) return;

    function hideResults() {
      resultsEl.hidden = true;
      resultsEl.innerHTML = '';
    }

    function showResults(query) {
      const matches = searchSite(query);
      resultsEl.innerHTML = '';

      if (!String(query || '').trim()) {
        hideResults();
        return;
      }

      if (!matches.length) {
        const empty = document.createElement('p');
        empty.className = 'site-search-empty';
        empty.textContent = 'No matching pages.';
        resultsEl.appendChild(empty);
        resultsEl.hidden = false;
        return;
      }

      matches.forEach((match) => {
        const link = document.createElement('a');
        link.className = 'site-search-result';
        link.href = match.url;
        link.setAttribute('role', 'option');

        const title = document.createElement('strong');
        title.textContent = match.title;
        const snippet = document.createElement('span');
        snippet.textContent = match.snippet;

        link.appendChild(title);
        link.appendChild(snippet);
        resultsEl.appendChild(link);
      });

      resultsEl.hidden = false;
    }

    input.addEventListener('input', () => {
      showResults(input.value);
    });

    input.addEventListener('focus', () => {
      if (input.value.trim()) showResults(input.value);
    });

    form.addEventListener('submit', (event) => {
      event.preventDefault();
      const matches = searchSite(input.value);
      if (matches[0]) {
        window.location.href = matches[0].url;
      } else {
        showResults(input.value);
      }
    });

    document.addEventListener('click', (event) => {
      if (!form.contains(event.target)) hideResults();
    });

    input.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        hideResults();
        input.blur();
      }
    });
  }

  const FORMSPREE_PLACEHOLDER_ID = 'YOUR_FORMSPREE_FORM_ID';

  function getFormspreeEndpoint() {
    const config = window.BruceHammSite || {};
    const formId = config.formspreeFormId;
    if (!formId || formId === FORMSPREE_PLACEHOLDER_ID) return null;
    if (formId.includes('@')) {
      return `https://formspree.io/${encodeURIComponent(formId)}`;
    }
    return `https://formspree.io/f/${formId}`;
  }

  function setContactFormStatus(form, type, message) {
    const statusEl = form.querySelector('.form-status');
    if (!statusEl) return;
    statusEl.hidden = !message;
    statusEl.className = type ? `form-status form-status--${type}` : 'form-status';
    statusEl.textContent = message || '';
  }

  function initContactForm() {
    const form = document.querySelector('.contact-form');
    if (!form) return;

    const endpoint = getFormspreeEndpoint();
    if (endpoint) {
      form.action = endpoint;
    }

    form.addEventListener('submit', async (event) => {
      event.preventDefault();

      if (!getFormspreeEndpoint()) {
        setContactFormStatus(
          form,
          'error',
          'This form is not configured yet. Add your Formspree form ID in js/site-config.js.'
        );
        return;
      }

      const submitBtn = form.querySelector('[type="submit"]');
      const originalLabel = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = 'Sending…';
      setContactFormStatus(form, '', '');

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: { Accept: 'application/json' },
        });

        if (response.ok) {
          form.reset();
          setContactFormStatus(form, 'success', 'Thank you — your message has been sent.');
        } else {
          const data = await response.json().catch(() => ({}));
          const message =
            typeof data.error === 'string'
              ? data.error
              : 'Something went wrong. Please try again or email Bruce directly.';
          setContactFormStatus(form, 'error', message);
        }
      } catch {
        setContactFormStatus(form, 'error', 'Network error. Please check your connection and try again.');
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalLabel;
      }
    });
  }

  function initHeroSlider() {
    document.querySelectorAll('.hero-slider').forEach((slider) => {
      const slides = Array.from(slider.querySelectorAll('.hero-slide'));
      const dots = Array.from(slider.querySelectorAll('.hero-slider-dot'));
      if (!slides.length || slides.length !== dots.length) return;

      let activeIndex = slides.findIndex((slide) => slide.classList.contains('is-active'));
      if (activeIndex < 0) activeIndex = 0;

      let timerId = null;
      const interval = Number(slider.dataset.autoplay) || 6000;
      const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      function showSlide(index) {
        activeIndex = (index + slides.length) % slides.length;

        slides.forEach((slide, i) => {
          slide.classList.toggle('is-active', i === activeIndex);
        });

        dots.forEach((dot, i) => {
          const isActive = i === activeIndex;
          dot.classList.toggle('is-active', isActive);
          dot.setAttribute('aria-selected', String(isActive));
        });
      }

      function stopAutoplay() {
        if (timerId) {
          window.clearInterval(timerId);
          timerId = null;
        }
      }

      function startAutoplay() {
        stopAutoplay();
        if (prefersReduced || interval <= 0) return;
        timerId = window.setInterval(() => {
          showSlide(activeIndex + 1);
        }, interval);
      }

      dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
          showSlide(index);
          startAutoplay();
        });
      });

      slider.addEventListener('mouseenter', stopAutoplay);
      slider.addEventListener('mouseleave', startAutoplay);
      slider.addEventListener('focusin', stopAutoplay);
      slider.addEventListener('focusout', startAutoplay);

      showSlide(activeIndex);
      startAutoplay();
    });
  }

  function initYoutubeEmbeds() {
    document.querySelectorAll('.video-lazy').forEach((frame) => {
      const videoId = frame.dataset.youtubeId;
      const title = frame.dataset.youtubeTitle || 'YouTube video';
      const start = frame.dataset.youtubeStart;
      const button = frame.querySelector('.video-play-btn');
      if (!videoId || !button) return;

      button.addEventListener('click', () => {
        const watchUrl = start
          ? `https://www.youtube.com/watch?v=${videoId}&t=${start}s`
          : `https://www.youtube.com/watch?v=${videoId}`;

        if (window.location.protocol === 'file:') {
          window.open(watchUrl, '_blank', 'noopener,noreferrer');
          return;
        }

        const startParam = start ? `&start=${start}` : '';
        const iframe = document.createElement('iframe');
        iframe.src = `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&rel=0${startParam}`;
        iframe.title = title;
        iframe.allow =
          'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
        iframe.allowFullscreen = true;
        iframe.referrerPolicy = 'strict-origin-when-cross-origin';
        iframe.loading = 'lazy';

        frame.innerHTML = '';
        frame.appendChild(iframe);
      });
    });
  }

  function initExclusiveAudio() {
    let activeAudio = null;

    document.querySelectorAll('audio').forEach((audio) => {
      audio.addEventListener('play', () => {
        if (activeAudio && activeAudio !== audio) {
          activeAudio.pause();
        }
        activeAudio = audio;
      });

      audio.addEventListener('pause', () => {
        if (activeAudio === audio) {
          activeAudio = null;
        }
      });

      audio.addEventListener('ended', () => {
        if (activeAudio === audio) {
          activeAudio = null;
        }
      });
    });
  }

  function initParticles() {
    const canvas = document.getElementById('particle-canvas');
    if (!canvas) return;

    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) return;

    const ctx = canvas.getContext('2d');
    let particles = [];
    let w = 0;
    let h = 0;
    let animId;

    function resize() {
      w = canvas.width = window.innerWidth;
      h = canvas.height = window.innerHeight;
    }

    function createParticles(count) {
      particles = Array.from({ length: count }, () => ({
        x: Math.random() * w,
        y: Math.random() * h,
        vx: (Math.random() - 0.5) * 0.35,
        vy: (Math.random() - 0.5) * 0.35,
        r: Math.random() * 1.5 + 0.5,
        alpha: Math.random() * 0.4 + 0.15,
      }));
    }

    function draw() {
      ctx.clearRect(0, 0, w, h);

      particles.forEach((p, i) => {
        p.x += p.vx;
        p.y += p.vy;

        if (p.x < 0) p.x = w;
        if (p.x > w) p.x = 0;
        if (p.y < 0) p.y = h;
        if (p.y > h) p.y = 0;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(201, 162, 39, ${p.alpha})`;
        ctx.fill();

        for (let j = i + 1; j < particles.length; j++) {
          const q = particles[j];
          const dx = p.x - q.x;
          const dy = p.y - q.y;
          const dist = Math.sqrt(dx * dx + dy * dy);
          if (dist < 120) {
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(q.x, q.y);
            ctx.strokeStyle = `rgba(201, 162, 39, ${0.06 * (1 - dist / 120)})`;
            ctx.lineWidth = 0.5;
            ctx.stroke();
          }
        }
      });

      animId = requestAnimationFrame(draw);
    }

    resize();
    createParticles(Math.min(80, Math.floor((w * h) / 18000)));
    draw();

    window.addEventListener(
      'resize',
      () => {
        cancelAnimationFrame(animId);
        resize();
        createParticles(Math.min(80, Math.floor((w * h) / 18000)));
        draw();
      },
      { passive: true }
    );
  }
})();
