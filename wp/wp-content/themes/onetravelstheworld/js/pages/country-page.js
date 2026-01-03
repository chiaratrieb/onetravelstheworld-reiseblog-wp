import { getRouteContext } from "../queries/url-context.js";
import { getCountryPageModel } from "../queries/content-queries.js";

export function initCountryPage() {
  function render() {
    const { continent, country } = getRouteContext();
    const model = getCountryPageModel({ continent, country });
    // ... mounts rendern / verstecken ...
  }

  render();
  window.addEventListener("hashchange", render);
}
