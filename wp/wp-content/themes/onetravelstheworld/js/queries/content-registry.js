// js/data/content-registry.js
// Hier laufen alle Content-Daten zusammen (ein einziges Array)

import { CITYTRIPS_SPAIN } from "../data/destinations/europe/spain/citytrips-spain.data.js";
import { ROADTRIPS_SPAIN } from "../data/destinations/europe/spain/roadtrips-spain.data.js";

import { ISLANDS_THAILAND } from "../data/destinations/asia/thailand/islands-thailand.data.js";
import { BACKPACKING_THAILAND } from "../data/destinations/asia/thailand/backpacking-thailand.data.js";

import { HIGHLIGHTS_BRAZIL } from "../data/destinations/southamerica/brazil/highlights-brazil.data.js";

// Optional: weitere Bereiche (guides etc.)
import { BUDGET_GUIDE } from "../data/guides/budget-guide.data.js";

export const ALL_CONTENT = [
  ...CITYTRIPS_SPAIN,
  ...ROADTRIPS_SPAIN,
  ...ISLANDS_THAILAND,
  ...BACKPACKING_THAILAND,
  ...HIGHLIGHTS_BRAZIL,
  ...BUDGET_GUIDE
];
