export function getRouteContext() {
  const hash = window.location.hash || ""; // z.B. "#/europe/spain"
  const cleaned = hash.replace(/^#\/?/, ""); // "europe/spain"
  const parts = cleaned.split("/").filter(Boolean);

  // Hash Variante:
  // 1) /#/europe/spain
  // 2) /#/europe  (kontinent landing)
  let continent = parts[0] || "";
  let country = parts[1] || "";

  // Fallback Query Variante: ?continent=europe&country=spain
  if (!continent) {
    const url = new URL(window.location.href);
    continent = url.searchParams.get("continent") || "";
    country = url.searchParams.get("country") || "";
  }

  return { continent, country };
}
