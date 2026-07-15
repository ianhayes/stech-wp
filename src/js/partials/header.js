/**
 * Sticky-header scroll state. blocks.js already handles the mobile nav toggle
 * (aria-expanded) and all block behaviours; this only adds the condensed
 * "is-scrolled" state used by the shadow on scroll.
 */
(function () {
  const header = document.querySelector('.site-header');
  if (!header) return;

  let ticking = false;
  const update = () => {
    header.classList.toggle('is-scrolled', window.scrollY > 8);
    ticking = false;
  };

  window.addEventListener(
    'scroll',
    () => {
      if (!ticking) {
        window.requestAnimationFrame(update);
        ticking = true;
      }
    },
    { passive: true }
  );
  update();
})();
