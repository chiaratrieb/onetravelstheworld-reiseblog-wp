export function renderRecommendations(sectionEl, data) {
  const grid = sectionEl.querySelector("[data-rec-grid]");
  if (!grid) return;

  grid.innerHTML = "";

  (data.items || []).forEach(item => {
    const card = document.createElement("article");
    card.className = "rec-card";

    const iconWrap = document.createElement("div");
    iconWrap.className = "rec-icon";
    iconWrap.innerHTML = item.iconSvg || "";

    const p = document.createElement("p");
    p.className = "rec-text";
    p.innerHTML = item.html || "";

    card.appendChild(iconWrap);
    card.appendChild(p);
    grid.appendChild(card);
  });
}
