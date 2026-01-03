import { queryContent } from "../queries/content-queries.js";
import { renderCarouselSection } from "../components/carousel-section.js";

function cloneTemplate(id) {
  const tpl = document.getElementById(id);
  if (!tpl) throw new Error(`Template #${id} nicht gefunden.`);
  return tpl.content.firstElementChild.cloneNode(true);
}

// Export: nur Carousels (Split/Recommendations kommen aus HTML/JSON)
export function initCarousels() {
  const configs = [
    {
      mount: "carousel-frontpage-top",
      kicker: "REISEPLANUNG LEICHT GEMACHT",
      title: "Top Reiseziele 2026",
      query: { continent: "europe", limit: 8 }
    },

    {
      mount: "carousel-frontpage-middle1",
      kicker: "KALTE JAHRESZEIT",
      title: "Die besten Winterdestinations in Europa",
      query: { continent: "europe", tags: ["winter"], limit: 8 }
    },
    
    {
      mount: "carousel-frontpage-accommodations",
      kicker: "",
      title: "",
      query: { continent: "", tags: ["accommodations"], limit: 8 }
    },
  
    {
      mount: "carousel-frontpage-middle2",
      kicker: "ENDTECKE DIE WELT ZU FUSS",
      title: "Meine besten Wandertipps",
      query: { continent: "europe", tags: ["hiking"], limit: 8 }
    },

    {
      mount: "carousel-frontpage-bottom",
      kicker: "FERNE WELTEN",
      title: "Best Off Südostasien",
      query: { continent: "asia", tags: ["southeastasia"], limit: 8 }
    }
  ];

  configs.forEach(cfg => {
    const mountEl = document.querySelector(`[data-mount="${cfg.mount}"]`);
    if (!mountEl) return;

    const sectionEl = cloneTemplate("tpl-carousel-section");
    mountEl.innerHTML = ""; // verhindert Doppel-Render
    mountEl.appendChild(sectionEl);

    const items = queryContent(cfg.query);
    renderCarouselSection(sectionEl, {
      kicker: cfg.kicker,
      title: cfg.title,
      items
    });
  });
}
