/* ============================================================================
   STECH blocks.js — generic, class-based behaviors for the block system.
   Include once per page (no per-page wiring needed). Self-initializes.
   Handles: mobile nav toggle · FAQ accordion · announcement dismiss.
   (Carousel / tabs / slider remain page-scoped where used.)
   ============================================================================ */
(function () {
  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }
  ready(function () {

    /* Mobile nav toggle */
    document.querySelectorAll('.menu-toggle').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var nav = btn.parentElement.querySelector('.main-nav');
        if (!nav) return;
        var open = nav.classList.toggle('is-open');
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    });

    /* FAQ accordion */
    document.querySelectorAll('.accordion__trigger').forEach(function (btn) {
      var panel = btn.nextElementSibling;
      if (!panel) return;
      if (btn.getAttribute('aria-expanded') === 'true') {
        panel.style.maxHeight = panel.scrollHeight + 'px';
      }
      btn.addEventListener('click', function () {
        var open = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', open ? 'false' : 'true');
        panel.style.maxHeight = open ? '0px' : panel.scrollHeight + 'px';
      });
    });

    /* Announcement dismiss */
    document.querySelectorAll('.announcement__close').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var bar = btn.closest('.announcement');
        if (bar) bar.hidden = true;
      });
    });

  });
})();

/* ============================================================================
   Animated stats — rings, placement pie, and counters animate on scroll-in.
   Markup: wrap the section in [data-animate-stats].
     ring: <div class="stat-ring" data-value="95"> ... <span class="stat-ring__num" data-suffix="%">0</span>
     pie:  <div class="placement-pie" data-segments="28,69,3"><div class="placement-pie__chart"></div>
   ============================================================================ */
(function () {
  function ready(fn){ if (document.readyState !== 'loading') fn(); else document.addEventListener('DOMContentLoaded', fn); }
  ready(function () {
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function countUp(el, target, suffix) {
      if (reduce) { el.textContent = target + suffix; return; }
      var dur = 1300, start = null;
      function tick(ts) {
        if (start === null) start = ts;
        var p = Math.min(1, (ts - start) / dur);
        var eased = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(target * eased) + suffix;
        if (p < 1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
    }

    function animateRoot(root) {
      root.querySelectorAll('.stat-ring[data-value]').forEach(function (ring) {
        var val = parseFloat(ring.getAttribute('data-value')) || 0;
        var bar = ring.querySelector('.stat-ring__bar');
        var num = ring.querySelector('.stat-ring__num');
        if (bar) requestAnimationFrame(function () { bar.style.strokeDashoffset = (100 - val); });
        if (num) countUp(num, val, num.getAttribute('data-suffix') || '%');
      });
      root.querySelectorAll('.placement-pie[data-segments]').forEach(function (pie) {
        var seg = (pie.getAttribute('data-segments') || '').split(',').map(Number);
        var chart = pie.querySelector('.placement-pie__chart');
        if (chart && seg.length >= 2) {
          var a = (seg[0] / 100) * 360, b = ((seg[0] + seg[1]) / 100) * 360;
          requestAnimationFrame(function () { chart.style.setProperty('--pa', a + 'deg'); chart.style.setProperty('--pb', b + 'deg'); });
        }
      });
    }

    var roots = document.querySelectorAll('[data-animate-stats]');
    if (!roots.length) return;
    if (!('IntersectionObserver' in window)) { roots.forEach(animateRoot); return; }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) { if (e.isIntersecting) { animateRoot(e.target); io.unobserve(e.target); } });
    }, { threshold: 0.3 });
    roots.forEach(function (r) { io.observe(r); });
  });
})();
