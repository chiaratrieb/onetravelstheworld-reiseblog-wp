export function assetUrl(input) {
  const s = String(input || "").trim();
  if (!s) return "";

  // Absolute URLs / data URIs unverändert lassen
  if (/^(https?:)?\/\//i.test(s) || s.startsWith("data:")) return s;

  const theme = (window.OTW_THEME_URI || "").replace(/\/$/, "");

  // 1) Deine bisherige alte Struktur: "./assets/..."
  if (s.startsWith("./assets/")) return theme + s.replace(".", "");

  // 2) "assets/..." ohne Punkt
  if (s.startsWith("assets/")) return theme + "/" + s;

  // 3) User hat "wp-content/..." angegeben
  if (s.startsWith("wp-content/")) return "/" + s;

  // 4) User hat versehentlich "wp/wp-content/..." angegeben -> korrigieren
  if (s.startsWith("wp/wp-content/")) return "/" + s.replace(/^wp\//, "");

  // Fallback: als site-root Pfad interpretieren
  return s.startsWith("/") ? s : "/" + s;
}
