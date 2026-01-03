import { initNavDropdowns } from "./components/nav-dropdowns.js";
import { renderSplitSection } from "./components/split-section.js";
import { renderRecommendations } from "./components/recommendations.js";
import { STATIC_CONTENT } from "./content/frontpage.static.js";
import { renderNewsletter } from "./components/newsletter.js";

function cloneTemplate(id) {
  const tpl = document.getElementById(id);
  if (!tpl) throw new Error(`Template #${id} nicht gefunden.`);
  return tpl.content.firstElementChild.cloneNode(true);
}

/**
 * Mountet statische Komponenten (Split/Recommendations/Newsletter) aus STATIC_CONTENT
 * anhand von data-component + data-template + data-key
 */
function mountStaticComponents() {
  const mounts = document.querySelectorAll("[data-component][data-template][data-key]");

  mounts.forEach(mountEl => {
    const component = mountEl.getAttribute("data-component");
    const templateId = mountEl.getAttribute("data-template");
    const key = mountEl.getAttribute("data-key");

    const data = STATIC_CONTENT[key];
    if (!data) {
      console.warn("Keine STATIC_CONTENT Daten für key:", key);
      return;
    }

    const sectionEl = cloneTemplate(templateId);
    mountEl.innerHTML = "";
    mountEl.appendChild(sectionEl);

    if (component === "split") return renderSplitSection(sectionEl, data);
    if (component === "recommendations") return renderRecommendations(sectionEl, data);
    if (component === "newsletter") return renderNewsletter(sectionEl, data);

    console.warn("Unbekannte component:", component);
  });
}

/**
 * Carousel UI: NUR Prev/Next Scroll.
 * Wichtig: Hier wird NICHT gerendert (keine data.js Inhalte).
 * Die Karten kommen serverseitig aus WordPress (PHP).
 */
function initCarouselUI() {
  document.querySelectorAll("[data-carousel-section]").forEach(sectionEl => {
    const track = sectionEl.querySelector("[data-carousel-track]");
    const prev = sectionEl.querySelector("[data-carousel-prev]");
    const next = sectionEl.querySelector("[data-carousel-next]");
    if (!track || !prev || !next) return;

    const getStep = () => {
      const card = track.querySelector(".card");
      if (!card) return 0;
      const gap = parseFloat(getComputedStyle(track).gap || 0);
      return card.offsetWidth + gap;
    };

    prev.addEventListener("click", () => {
      track.scrollBy({ left: -getStep(), behavior: "smooth" });
    });

    next.addEventListener("click", () => {
      track.scrollBy({ left: getStep(), behavior: "smooth" });
    });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  initNavDropdowns();

  // 1) Statische Sections mounten (Split/Recommendations/Newsletter)
  mountStaticComponents();

  
});
