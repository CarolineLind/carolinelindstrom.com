# 0005 – Hand-drawn logo, first-visit write-in and favicon

- **Date:** 2026-10-24
- **Status:** Decided, built
- **Decided by:** Caroline Lindström

## In short

The logo is the hand-drawn `<cl>` from `LogoB.dc.html`: three pen strokes in an inline SVG, no font. It writes itself in **once**, right after the visitor has made their choices in the first-visit dialog. The favicon is the `cl` stroke on a rounded plate, and it switches between light and dark with the **system** setting.

## Decision

| Design | Theme | Why |
| --- | --- | --- |
| The logo writes itself in on every page load (about 1.7 s) | Only once, after the first-visit dialog. The dialog's backdrop is the page background (`--color-bg`), so it follows the light/dark choice while choosing. On "Spara och gå vidare" (or Escape) the dialog slides down out of the window, and two veils in the background color take over: the header veil fades in 0.5 s and the logo starts writing while it fades. Once the logo is written (about 2.4 s after the click, a little slower than in the design), the content veil slides down with a soft edge a whole window tall (1.6 s); the content is fully shown after about 3.5 s. Every other page load shows the logo fully drawn | Caroline's call. A long animation on every click gets tiresome, and before the dialog nothing may move (0002). The header shows alone first, so the eye lands on the logo, and the logo is finished before the content appears (Caroline's call). A black curtain was tried first and looked heavy. It all ends within WCAG 2.2.2's 5 seconds |
| Its own curves and durations | Motion tokens: `--ease-in-out` for writing and the swash, `--ease-spring` over `--dur-slow` for the brackets, `--ease-in` over `--dur-base` for the dialog leaving. The stroke durations are tokens (`--dur-write-*`) | All motion comes from tokens (0004) |
| (Not in the design) | With motion off, the dialog closes straight away and the logo is drawn from the start | The motion rule in `utilities.css` |
| Reopening "Inställningar" | Keeps the semi-transparent backdrop and doesn't replay the logo | So the light/dark preview behind the dialog is still visible |
| Swash `#D9A900` in light mode | Kept | 2.1:1 on the light background. It only shows on hover and focus, as decoration. The brackets (4.7:1 light, 11.9:1 dark) and `cl` (text color) identify the link. Logotypes are exempt from 1.4.3 anyway, but the Understanding document warns that a logo that is also a link needs care, which is why the parts that identify it pass |
| (Not in the design) | In forced-colors mode (Windows high contrast) every stroke uses `LinkText` | The theme's colors are replaced by the system's |
| Favicon follows `prefers-color-scheme` | Same, and the system setting only, not the site's "Ljust läge" toggle | Caroline's call. Swapping the icon on the toggle needs JavaScript and doesn't work for a cached page |
| PNG/ICO fallback for Safari | `favicon-32.png` and `apple-touch-icon.png` (180 px), both the **dark** version (Caroline's call) | Safari ignores dark mode in SVG favicons (WebKit bug 309949, confirmed March 2026), so Safari likely always shows one version. The home screen icon has square corners: iOS draws its own (not checked against Apple's documentation) |
| WordPress Site Icon (Customizer) | Not printed: the theme owns the icons, and `/favicon.ico` redirects to the theme's PNG | One source for the icons, in the repo (Caroline's call) |
| `theme-color` | `#FBFAF6` light, `#15131F` dark, by `prefers-color-scheme` | Same as `--color-bg`. Like the favicon, it follows the system |

## Consequences

1. **Changing the favicon:** edit `assets/icons/favicon.svg`, then run `node tools/icons/render-icons.mjs` to render the PNGs again.
2. **The Site Icon set in Customizer does nothing on the front end.** It's still used in the admin.
3. **`/favicon.ico` is not testable in Local:** Local's nginx answers it with an empty 200 before WordPress sees it. On SiteGround it goes to WordPress (`do_faviconico`), unless a `favicon.ico` file is in the web root.
4. **If storage is blocked** (0002, point 3), the dialog and the write-in come back on every page.

## Where it lives

| What | File |
| --- | --- |
| Logo markup | `carolinelindstrom/inc/template-tags.php` (`clind_logo()`) |
| Logo styles, hover and write-in | `carolinelindstrom/assets/css/components/logo.css` |
| Backdrop and leaving | `carolinelindstrom/assets/css/components/preferences.css`, `leave()` in `assets/js/preferences.js` |
| The intro veils | `carolinelindstrom/assets/css/components/intro.css`, `openIntro()` in `assets/js/preferences.js` |
| Icons and `theme-color` | `carolinelindstrom/inc/icons.php`, `assets/icons/` |
| PNG rendering | `tools/icons/render-icons.mjs` |

## Sources

- Design: `LogoB.dc.html` in the design canvas
- [Understanding SC 1.4.3 Contrast (Minimum)](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html): logotypes, and the warning about logos that are links
- [WebKit bug 309949](https://bugs.webkit.org/show_bug.cgi?id=309949): Safari and dark mode in SVG favicons
- [Changes to CSS ::backdrop inheritance](https://developer.chrome.com/blog/css-backdrop-inheritance): `::backdrop` inherits custom properties from Chrome 122, Firefox 120, Safari 17.4, so the backdrop can use `--color-bg` and follow the light/dark preview
