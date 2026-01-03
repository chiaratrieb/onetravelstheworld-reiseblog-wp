import { assetUrl } from "./asset-url.js";

export function renderCarouselSection(sectionEl, data) {
  sectionEl.querySelector('[data-carousel-kicker]').textContent = data.kicker;
  sectionEl.querySelector('[data-carousel-title]').textContent = data.title;

  const track = sectionEl.querySelector('[data-carousel-track]');
  track.innerHTML = "";

  data.items.forEach(item => {
    const card = document.createElement("article");
    card.className = "card card--carousel";

    const imgUrl = assetUrl(item.image);
card.innerHTML = `
  <div class="card__media">
    <div class="card__img" style="background-image:url('${imgUrl}')"></div>
    <div class="card__meta">${item.country.toUpperCase()}</div>
  </div>
  <h3 class="card__title">${item.title}</h3>
`;

    track.appendChild(card);
  });

  initCarousel(sectionEl);
}

function escapeHtml(str) {
  return String(str)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

/* Slider-Logik */
function initCarousel(sectionEl) {
  const track = sectionEl.querySelector('[data-carousel-track]');
  const prev = sectionEl.querySelector('[data-carousel-prev]');
  const next = sectionEl.querySelector('[data-carousel-next]');

  const getStep = () => {
    const card = track.querySelector('.card');
    if (!card) return 0;
    const gap = parseFloat(getComputedStyle(track).gap || 0);
    return card.offsetWidth + gap;
  };

  prev.addEventListener('click', () => {
    track.scrollBy({ left: -getStep(), behavior: 'smooth' });
  });

  next.addEventListener('click', () => {
    track.scrollBy({ left: getStep(), behavior: 'smooth' });
  });
}
