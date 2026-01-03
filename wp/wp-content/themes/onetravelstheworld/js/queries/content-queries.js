// js/queries/content-queries.js
import { ALL_CONTENT } from "./content-registry.js";

export function queryContent(query = {}) {
  const {
    continent = "",
    country = "",
    tags = [],
    limit = 999
  } = query;

  const tagList = Array.isArray(tags) ? tags : (tags ? [tags] : []);

  const res = ALL_CONTENT.filter(item => {
    if (continent && item.continent !== continent) return false;
    if (country && item.country !== country) return false;

    if (tagList.length) {
      const itTags = item.tags || [];
      const ok = tagList.every(t => itTags.includes(t));
      if (!ok) return false;
    }

    return true;
  });

  return res.slice(0, limit);
}

export function getDestinationIndex(filters = {}) {
  const { tag = null } = filters;

  const map = new Map(); // continent -> Map(country -> count)

  ALL_CONTENT.forEach(it => {
    if (!it?.continent || !it?.country) return;

    if (tag && !(it.tags || []).includes(tag)) return;

    if (!map.has(it.continent)) map.set(it.continent, new Map());
    const cMap = map.get(it.continent);
    cMap.set(it.country, (cMap.get(it.country) || 0) + 1);
  });

  const out = {};
  map.forEach((countriesMap, cont) => {
    out[cont] = Array.from(countriesMap.entries())
      .map(([country, count]) => ({ country, count }))
      .sort((a, b) => a.country.localeCompare(b.country, "de"));
  });

  return out;
}
