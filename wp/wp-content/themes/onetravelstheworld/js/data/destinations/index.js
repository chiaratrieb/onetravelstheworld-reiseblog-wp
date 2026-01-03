import citytripsSpain from "./europe/spain/citytrips-spain.data.js";
import roadtripsSpain from "./europe/spain/roadtrips-spain.data.js";

import islandsThailand from "./asia/thailand/islands-thailand.data.js";
import backpackingThailand from "./asia/thailand/backpacking-thailand.data.js";

import highlightsBrazil from "./southamerica/brazil/highlights-brazil.data.js";

// Hier alles zusammenführen
export const destinations = [
  ...citytripsSpain,
  ...roadtripsSpain,
  ...islandsThailand,
  ...backpackingThailand,
  ...highlightsBrazil
];
