export function renderNewsletter(sectionEl, data) {
  const kicker = sectionEl.querySelector("[data-nl-kicker]");
  const title  = sectionEl.querySelector("[data-nl-title]");
  const intro  = sectionEl.querySelector("[data-nl-intro]");
  const btn    = sectionEl.querySelector("[data-nl-submit]");
  const link   = sectionEl.querySelector("[data-nl-privacy-link]");

  if (kicker) kicker.textContent = data.kicker || "";
  if (title)  title.textContent  = data.title || "";
  if (intro)  intro.textContent  = data.intro || "";
  if (btn)    btn.textContent    = data.buttonText || "Abonnieren";
  if (link)   link.href          = data.privacyHref || "#";

  // Optional: Basic Frontend-Validation UX (ohne Backend)
  const form = sectionEl.querySelector("[data-nl-form]");
  if (form) {
    form.addEventListener("submit", (e) => {
      // nur Demo: verhindert echtes Submit, solange du noch kein Tool angebunden hast
      e.preventDefault();
      form.reportValidity();
    });
  }
}
