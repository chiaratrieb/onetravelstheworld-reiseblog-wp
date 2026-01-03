import { assetUrl } from "./asset-url.js";


export function renderSplitSection(sectionEl, data) {
  sectionEl.classList.remove("split-section--beige");
  if (data.background === "beige") sectionEl.classList.add("split-section--beige");

  const kickerEl = sectionEl.querySelector("[data-split-kicker]");
  const titleEl  = sectionEl.querySelector("[data-split-title]");
  const textEl   = sectionEl.querySelector("[data-split-text]");
  const scriptEl = sectionEl.querySelector("[data-split-script]");

  if (kickerEl) kickerEl.textContent = data.kicker || "";
  if (titleEl) titleEl.innerHTML = data.title || "";

  if (textEl) {
    if (Array.isArray(data.text)) {
      textEl.innerHTML = data.text.map(p => `<p class="split-section__text">${p}</p>`).join("");
    } else {
      textEl.innerHTML = data.text || "";
    }
  }

  if (scriptEl) {
    const val = data.script || "";
    scriptEl.textContent = val;
    scriptEl.style.display = val ? "" : "none";
  }

  const img = sectionEl.querySelector("[data-split-image]");
if (img) {
  img.src = assetUrl(data.image) || assetUrl("./assets/images/placeholder.jpg");
  img.alt = data.imageAlt || "";
  img.loading = "lazy";
  img.decoding = "async";
}


  const btn = sectionEl.querySelector("[data-split-button]");
  if (btn) {
    const label = data.buttonText || "";
    btn.textContent = label;
    btn.href = data.buttonHref || "#";
    btn.style.display = label ? "" : "none";
  }
}
