import { getDestinationIndex } from "../queries/content-queries.js";

export function initNavDropdowns() {
  initClassicDropdowns();
  initMegaDestinations();
}

/* =========================
   Klassische Dropdowns
========================= */
function initClassicDropdowns() {
  const dropdownItems = document.querySelectorAll(".nav__item--dropdown:not(.nav__item--mega)");

  function closeAll() {
    dropdownItems.forEach(item => {
      item.classList.remove("is-open");
      const btn = item.querySelector(".nav__drop");
      if (btn) btn.setAttribute("aria-expanded", "false");
    });
  }

  dropdownItems.forEach(item => {
    const btn = item.querySelector(".nav__drop");
    if (!btn) return;

    btn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation(); // ✅ verhindert, dass global click direkt wieder schließt

      const isOpen = item.classList.contains("is-open");
      closeAll();
      if (!isOpen) {
        item.classList.add("is-open");
        btn.setAttribute("aria-expanded", "true");
      }
    });
  });

  document.addEventListener("click", (e) => {
    if (!e.target.closest(".nav__item--dropdown")) closeAll();
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeAll();
  });
}

/* =========================
   Mega Dropdown: Reiseziele (Best Practice)
========================= */
function initMegaDestinations() {
  const megaRoot = document.querySelector('[data-mega="destinations"]');
  if (!megaRoot) return;

  const trigger = megaRoot.querySelector(".nav__drop");
  const dropdown = megaRoot.querySelector(".dropdown--mega");
  const tabs = megaRoot.querySelectorAll(".mega__tab");
  const panels = megaRoot.querySelectorAll(".mega__panel");

  if (!trigger || !dropdown || !tabs.length || !panels.length) return;

  function open() {
    megaRoot.classList.add("is-open");
    trigger.setAttribute("aria-expanded", "true");
  }

  function close() {
    megaRoot.classList.remove("is-open");
    trigger.setAttribute("aria-expanded", "false");
  }

  function setActive(continentSlug) {
    tabs.forEach(btn => {
      btn.classList.toggle("is-active", btn.dataset.continent === continentSlug);
    });

    panels.forEach(panel => {
      panel.classList.toggle("is-active", panel.dataset.panel === continentSlug);
    });
  }

  // Default: erster Tab, der ein Panel hat
  (function initDefault() {
    const firstPanel = panels[0];
    if (!firstPanel) return;
    setActive(firstPanel.dataset.panel);
  })();

  // Öffnen/Schließen nur per Klick auf Trigger
  trigger.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation(); // wichtig: verhindert "outside click" sofort
    if (megaRoot.classList.contains("is-open")) close();
    else open();
  });

  // Klick innerhalb des Dropdowns soll NICHT schließen
  dropdown.addEventListener("click", (e) => {
    e.stopPropagation();
  });

  // Tabs klicken -> Panel wechseln
  tabs.forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const slug = btn.dataset.continent;
      if (!slug) return;
      setActive(slug);
    });
  });

  // Klick auf Kontinent-Übersicht oder Land -> schließen
  megaRoot.addEventListener("click", (e) => {
    const link = e.target.closest("a");
    if (!link) return;

    // schließt bei Klick auf:
    // - Kontinent-Link (mega__right-title)
    // - Länder-Link (mega__country)
    if (link.classList.contains("mega__right-title") || link.classList.contains("mega__country")) {
      close();
    }
  });

  // Outside click -> schließen
  document.addEventListener("click", (e) => {
    if (!megaRoot.contains(e.target)) close();
  });

  // ESC -> schließen
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") close();
  });
}
