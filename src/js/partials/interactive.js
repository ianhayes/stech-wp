/* ============================================================================
   STECH interactive.js — front-end handlers for the interactive Gutenberg
   blocks whose behaviour used to live in page-scoped demo scripts.
   Generic, class-based, self-initialising. Keys off the classes/attributes the
   theme's render.php actually emits (NOT the demo getElementById hooks).

   Handles: info-tabs · photo-slider · program-selector · testimonial-carousel.
   blocks.js still owns: mobile nav · accordion · announcement dismiss · stats.
   Every handler no-ops when its block is absent, supports multiple instances
   per page, and honours prefers-reduced-motion.
   ============================================================================ */
(function () {
  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  var REDUCE = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ------------------------------------------------------------------ *
   * Info Tabs — .info-tabs / .info-tabs__btn[data-tab] / .info-tabs__panel[data-panel]
   * Buttons carry role=tab + aria-selected; panels carry aria-hidden.
   * Full roving-tabindex keyboard support (arrows / Home / End / Enter / Space).
   * ------------------------------------------------------------------ */
  function initInfoTabs() {
    document.querySelectorAll('.info-tabs').forEach(function (root) {
      var btns = Array.prototype.slice.call(root.querySelectorAll('.info-tabs__btn'));
      var panels = Array.prototype.slice.call(root.querySelectorAll('.info-tabs__panel'));
      if (!btns.length || !panels.length) return;

      function select(id, focus) {
        btns.forEach(function (b) {
          var on = b.getAttribute('data-tab') === id;
          b.setAttribute('aria-selected', on ? 'true' : 'false');
          b.setAttribute('tabindex', on ? '0' : '-1');
          if (on && focus) b.focus();
        });
        panels.forEach(function (p) {
          p.setAttribute('aria-hidden', p.getAttribute('data-panel') === id ? 'false' : 'true');
        });
      }

      // Establish roving tabindex from the server-set aria-selected state.
      var initial = btns.filter(function (b) { return b.getAttribute('aria-selected') === 'true'; })[0] || btns[0];
      select(initial.getAttribute('data-tab'), false);

      btns.forEach(function (b, idx) {
        b.addEventListener('click', function () { select(b.getAttribute('data-tab'), false); });
        b.addEventListener('keydown', function (e) {
          var target = null;
          switch (e.key) {
            case 'ArrowRight':
            case 'ArrowDown': target = btns[(idx + 1) % btns.length]; break;
            case 'ArrowLeft':
            case 'ArrowUp': target = btns[(idx - 1 + btns.length) % btns.length]; break;
            case 'Home': target = btns[0]; break;
            case 'End': target = btns[btns.length - 1]; break;
            case 'Enter':
            case ' ':
            case 'Spacebar': e.preventDefault(); select(b.getAttribute('data-tab'), false); return;
            default: return;
          }
          e.preventDefault();
          select(target.getAttribute('data-tab'), true);
        });
      });
    });
  }

  /* ------------------------------------------------------------------ *
   * Photo Slider — [data-slider] / [data-slider-track] / .photo-slider__slide
   * Dots are built into [data-slider-dots]. Auto-advance pauses on
   * hover/focus and is disabled entirely under reduced-motion.
   * ------------------------------------------------------------------ */
  function initPhotoSliders() {
    document.querySelectorAll('[data-slider]').forEach(function (root) {
      var track = root.querySelector('[data-slider-track]');
      var dotsWrap = root.querySelector('[data-slider-dots]');
      if (!track) return;
      var slides = track.querySelectorAll('.photo-slider__slide');
      var total = slides.length;
      if (total < 2) return;

      var current = 0;
      var timer = null;

      if (dotsWrap) {
        for (var i = 0; i < total; i++) {
          (function (idx) {
            var dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'photo-slider__dot';
            dot.setAttribute('aria-label', 'Go to slide ' + (idx + 1));
            dot.setAttribute('aria-current', idx === 0 ? 'true' : 'false');
            dot.addEventListener('click', function () { goTo(idx); restart(); });
            dotsWrap.appendChild(dot);
          })(i);
        }
      }
      var dots = dotsWrap ? dotsWrap.querySelectorAll('.photo-slider__dot') : [];

      function goTo(idx) {
        current = (idx + total) % total;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        Array.prototype.forEach.call(dots, function (d, i) {
          d.setAttribute('aria-current', i === current ? 'true' : 'false');
        });
      }
      function start() {
        if (REDUCE) return;
        clearInterval(timer);
        timer = setInterval(function () { goTo(current + 1); }, 6000);
      }
      function restart() { start(); }
      function stop() { clearInterval(timer); }

      // Keyboard: arrows move between slides when the region is focused.
      root.setAttribute('tabindex', root.getAttribute('tabindex') || '0');
      root.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowRight') { e.preventDefault(); goTo(current + 1); restart(); }
        else if (e.key === 'ArrowLeft') { e.preventDefault(); goTo(current - 1); restart(); }
      });

      root.addEventListener('mouseenter', stop);
      root.addEventListener('mouseleave', start);
      root.addEventListener('focusin', stop);
      root.addEventListener('focusout', start);

      goTo(0);
      start();
    });
  }

  /* ------------------------------------------------------------------ *
   * Program Selector — .program-selector
   *   filters: [data-filters] > .programs__filter[data-filter] (aria-pressed)
   *   cards:   .program-card[data-category] (server-rendered in one page)
   *   carousel: [data-track] / [data-prev] / [data-next] / [data-dots]
   * Cards are re-paginated (8 per page) into .carousel__page groups on filter.
   * ------------------------------------------------------------------ */
  var PROGRAMS_PER_PAGE = 8;
  function initProgramSelectors() {
    document.querySelectorAll('.program-selector').forEach(function (root) {
      var track = root.querySelector('[data-track]');
      var dotsWrap = root.querySelector('[data-dots]');
      var prevBtn = root.querySelector('[data-prev]');
      var nextBtn = root.querySelector('[data-next]');
      var filterBtns = Array.prototype.slice.call(root.querySelectorAll('.programs__filter'));
      if (!track) return;

      // Snapshot every card node once; we re-home them into fresh pages.
      var cards = Array.prototype.slice.call(track.querySelectorAll('.program-card'));
      if (!cards.length) return;

      var activeFilter = 'all';
      var pressed = filterBtns.filter(function (b) { return b.getAttribute('aria-pressed') === 'true'; })[0];
      if (pressed) activeFilter = pressed.getAttribute('data-filter') || 'all';
      var current = 0;

      function buildPages() {
        var list = cards.filter(function (c) {
          return activeFilter === 'all' || c.getAttribute('data-category') === activeFilter;
        });
        track.innerHTML = '';
        var totalPages = Math.max(1, Math.ceil(list.length / PROGRAMS_PER_PAGE));
        for (var pi = 0; pi < totalPages; pi++) {
          var page = document.createElement('div');
          page.className = 'carousel__page';
          list.slice(pi * PROGRAMS_PER_PAGE, pi * PROGRAMS_PER_PAGE + PROGRAMS_PER_PAGE).forEach(function (c) {
            page.appendChild(c);
          });
          track.appendChild(page);
        }
        return totalPages;
      }

      function buildDots(totalPages) {
        if (!dotsWrap) return;
        dotsWrap.innerHTML = '';
        for (var i = 0; i < totalPages; i++) {
          (function (idx) {
            var dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'carousel__dot';
            dot.setAttribute('aria-label', 'Go to page ' + (idx + 1));
            dot.addEventListener('click', function () { goTo(idx); });
            dotsWrap.appendChild(dot);
          })(i);
        }
        var single = totalPages <= 1;
        if (dotsWrap) dotsWrap.style.display = single ? 'none' : '';
        if (prevBtn) prevBtn.style.display = single ? 'none' : '';
        if (nextBtn) nextBtn.style.display = single ? 'none' : '';
      }

      function goTo(idx) {
        var totalPages = track.children.length;
        current = Math.max(0, Math.min(totalPages - 1, idx));
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        if (prevBtn) prevBtn.disabled = current === 0;
        if (nextBtn) nextBtn.disabled = current === totalPages - 1;
        if (dotsWrap) {
          dotsWrap.querySelectorAll('.carousel__dot').forEach(function (d, i) {
            d.setAttribute('aria-current', i === current ? 'true' : 'false');
          });
        }
      }

      function applyFilter(filter) {
        activeFilter = filter;
        filterBtns.forEach(function (b) {
          b.setAttribute('aria-pressed', (b.getAttribute('data-filter') || 'all') === filter ? 'true' : 'false');
        });
        var totalPages = buildPages();
        buildDots(totalPages);
        goTo(0);
      }

      filterBtns.forEach(function (b) {
        b.addEventListener('click', function () { applyFilter(b.getAttribute('data-filter') || 'all'); });
      });
      if (prevBtn) prevBtn.addEventListener('click', function () { goTo(current - 1); });
      if (nextBtn) nextBtn.addEventListener('click', function () { goTo(current + 1); });

      applyFilter(activeFilter);
    });
  }

  /* ------------------------------------------------------------------ *
   * Testimonial Carousel — .testimonial-carousel[data-carousel]
   *   [data-track] holds server-rendered .testimonial-carousel__page groups
   *   [data-prev] / [data-next] / [data-dots]
   * Dots are built to match the existing pages; keyboard operable.
   * ------------------------------------------------------------------ */
  function initTestimonialCarousels() {
    document.querySelectorAll('.testimonial-carousel[data-carousel]').forEach(function (root) {
      var track = root.querySelector('[data-track]');
      var dotsWrap = root.querySelector('[data-dots]');
      var prevBtn = root.querySelector('[data-prev]');
      var nextBtn = root.querySelector('[data-next]');
      if (!track) return;
      var pages = track.querySelectorAll('.testimonial-carousel__page');
      var total = pages.length;

      var current = 0;

      if (dotsWrap) {
        for (var i = 0; i < total; i++) {
          (function (idx) {
            var dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'testimonial-carousel__dot';
            dot.setAttribute('aria-label', 'Go to testimonial page ' + (idx + 1));
            dot.addEventListener('click', function () { goTo(idx); });
            dotsWrap.appendChild(dot);
          })(i);
        }
      }

      function goTo(idx) {
        current = Math.max(0, Math.min(total - 1, idx));
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        if (prevBtn) prevBtn.disabled = current === 0;
        if (nextBtn) nextBtn.disabled = current === total - 1;
        if (dotsWrap) {
          dotsWrap.querySelectorAll('.testimonial-carousel__dot').forEach(function (d, i) {
            d.setAttribute('aria-current', i === current ? 'true' : 'false');
          });
        }
      }

      // Hide controls entirely when there's a single page.
      if (total <= 1) {
        if (dotsWrap) dotsWrap.style.display = 'none';
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
      }

      if (prevBtn) prevBtn.addEventListener('click', function () { goTo(current - 1); });
      if (nextBtn) nextBtn.addEventListener('click', function () { goTo(current + 1); });

      root.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowRight') { e.preventDefault(); goTo(current + 1); }
        else if (e.key === 'ArrowLeft') { e.preventDefault(); goTo(current - 1); }
      });

      goTo(0);
    });
  }

  ready(function () {
    initInfoTabs();
    initPhotoSliders();
    initProgramSelectors();
    initTestimonialCarousels();
  });
})();
