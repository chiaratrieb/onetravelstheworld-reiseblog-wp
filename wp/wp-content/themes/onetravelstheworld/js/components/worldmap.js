export function initWorldmap() {
  const map = document.querySelector('[data-worldmap-map]');
  const nav = document.querySelector('[data-worldmap-nav]');
  if (!nav) return;

  /* =========================
     ACCORDION: Kontinente
  ========================= */
  nav.querySelectorAll('.worldmap__toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.worldmap__item');
      const panel = item?.querySelector('.worldmap__panel');
      if (!panel) return;

      const isOpen = btn.getAttribute('aria-expanded') === 'true';

      // close all
      nav.querySelectorAll('.worldmap__toggle').forEach(b => b.setAttribute('aria-expanded', 'false'));
      nav.querySelectorAll('.worldmap__panel').forEach(p => (p.hidden = true));

      // open current if it was closed
      if (!isOpen) {
        btn.setAttribute('aria-expanded', 'true');
        panel.hidden = false;
      }
    });
  });

  /* =========================
     MARKER: Kontinente (immer sichtbar)
  ========================= */
  if (!map) return;

  let markers = [];
  try {
    markers = JSON.parse(map.getAttribute('data-markers') || '[]');
  } catch (e) {
    console.warn('[OTW] worldmap markers JSON invalid', e);
  }

  if (!markers.length) return;

  // Map-Content leeren und Marker rendern
  map.innerHTML = '';

  markers.forEach(m => {
    const a = document.createElement('a');
    a.className = 'worldmap__marker';
    a.href = m.url || '#';
    a.style.left = `${m.x}%`;
    a.style.top = `${m.y}%`;

    // nur für Debug/Styling/Filter später
    if (m.slug) a.dataset.continent = String(m.slug);

    a.innerHTML = `
      <div class="worldmap__photo">
        <img src="${m.img || ''}" alt="">
      </div>
      <div class="worldmap__label">${m.label || ''}</div>
    `;

    map.appendChild(a);
  });
}
