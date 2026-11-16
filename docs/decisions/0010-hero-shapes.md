# 0010 – Start page hero: doodles that rise with the scroll

- **Date:** 2026-11-08
- **Status:** Decided, built
- **Decided by:** Caroline Lindström

## In short

Twelve hand-drawn doodles lie behind the hero's paper and portrait. As the page scrolls they rise, drift a little sideways and turn, each at its own speed, so they slide apart and the hero gets depth. The portrait joins in: the photo rises and tips back, the lilac plate lags behind and the yellow dot floats off. This replaces the portrait's hover effect from 0007.

The shapes, their places and their speeds come from a prototype Caroline tried out (a Claude artifact, "Hero med parallaxformer"), at **strength 2.0**: every move is twice the prototype's base values. Caroline raised it to 2.5 on 2026-11-16, to make the rise easier to notice.

## Decision

| What | How | Why |
| --- | --- | --- |
| Strength 2.5 | One token, `--hero-shapes-amount: 2.5`, multiplies every scroll move in the hero, the portrait's too | Caroline's call after trying 0 to 3 in the prototype (2.0), raised to 2.5 on 2026-11-16 so the rise is easier to notice. One number to turn it up or down |
| Phones follow the prototype | The same sideways drift as on desktop. The four shapes in the side margins lie on top of the paper's edge, small and centred in the 24 px gutter | Caroline's call. At 2.5 the star drifts up to 20 px inwards: check that no shape reaches the text on a real phone (see "Check") |
| Phones and tablets: the portrait lies over the paper | Below 1000 px the order is portrait, paper, yellow dot, lilac plate: as the portrait rises with the scroll it floats over the paper's bottom edge, while the dot and the plate slide under it. The dot and the plate are their own layer (`.start-hero__photo-back`) beside the image, with the same moves, because a moving element keeps its pseudo-elements in its own stacking context | Caroline's call (2026-11-16). Checked on a 390 px screen with motion on: the portrait reaches the paper's edge but never the text, and the link stays in view until it slides under the header (WCAG 2.4.11) |
| Phones and tablets: the portrait starts straight | Its own scroll number (`--pp`) waits until half of it is in view (`entry 50%` instead of `cover 0%`), and the move eases in over the first quarter screen of scrolling (`--p`), so it's straight as the page opens even where it's fully in view | Caroline's call (2026-11-16): on tall phones it was already in view, and tilted, when the page opened |
| The portrait's hover effect is removed | The plate and the dot follow the scroll on every device, no hover state | Caroline's call: one behaviour everywhere instead of hover on desktop and scroll on touch (0007) |
| Scroll-driven animations in CSS | Three numbers, `--p` (page), `--pp` (portrait) and `--pb` (hero's bottom edge), are animated on `.start-hero` with `animation-timeline` and inherited | No script on the main path. Chrome and Edge 115+, Safari and iOS Safari 26+, Firefox 159+ ([caniuse](https://caniuse.com/mdn-css_properties_animation-timeline_view), checked 2026-11-08) |
| Fallback | `start-hero.js` writes the same three numbers at most once per frame, only with motion on | Older browsers, above all iOS before 26 |
| Only `translate`, `rotate`, `scale` | The scroll moves the separate properties, so the entrance's `transform` and the SVG's centring stay untouched | Animate only transform-like properties (CLAUDE.md) |
| Motion off | Everything lies still at its start point; the two "late" shapes stay hidden below the hero | `data-motion` rule (0002). Parallax is named as non-essential motion under WCAG 2.3.3 ([Understanding 2.3.3](https://www.w3.org/WAI/WCAG22/Understanding/animation-from-interactions.html), AAA); the site's pause button covers it |
| Pop-in | The shapes pop in once, one after another (`--ease-out`, `--dur-slide`, 90 ms apart), finished in under 2 s | Entering motion (0004). Shorter than 5 s, and the scroll moves only while the visitor scrolls, so nothing moves on its own for longer than 5 s (WCAG 2.2.2, [Understanding 2.2.2](https://www.w3.org/WAI/WCAG22/Understanding/pause-stop-hide.html)) |
| Layout changed | From 1000 px: a grid with the portrait in its own column (`--photo-col`), and the same space (`--hero-side`) at both screen edges and between paper and portrait. Below 1000 px: the portrait centred under the paper | The shapes' places are % of the hero; they need room at the edges and in the gap, as in the prototype |
| A disc behind each shape | Each SVG sits on a round disc of the page's colour, `--color-bg` (what shows behind the sky), with a 10 px glow of the same colour (`--color-shape-halo`, `--shape-halo-blur`) | Caroline's call: without it the shapes ran together with the dots in the background. Not on the four side-margin shapes below 1000 px (`front-m`): they lie on the paper's edge, where the disc covered the paper (Caroline's call, 2026-11-16) |
| Smaller heading | The hero's h1 uses `--fs-hero-title` (`clamp(52px, 7vw, 112px)`) instead of `--fs-h1` | With the portrait in its own column the paper is narrower, and from about 1600 px the heading broke onto three lines. Now it's two lines at every desktop width (measured 1000 to 1920 px) |
| More shapes on wide screens | Eight extra shapes (reused doodles) fill the side margins: four from 1440 px (`hero-shape--wide`), four more from 1800 px (`--wider`). They're placed across the margin (`mx`, 0 at the screen edge, 1 at the content), not in % of the hero; the original twelve stay where they are | Caroline's call (2026-11-16): the margin grows faster than the % places (about 344 px a side at 1920 px), so wide screens had big empty areas between the content and the edge shapes. Still in the 2 s pop-in: they slot in between the others (`i: 1.5` and so on) |
| The hero fills the first screen on desktop | From 1000 px wide, the hero and the keyword banner (wrapped in `.start-top`) fill the screen together, up to a 1440 px tall screen (`--hero-screen-max`). The paper and the portrait are centred; the shapes spread out with the hero, and seven more fill the bands above and below on tall screens (`tall` from 1000 px high, `taller` from 1300 px) | Caroline's call (2026-11-16): more room for the background on tall screens, with the banner still in view. The header's height at the top is a fixed token (`--header-height-top`), because `--header-height` follows the header as it shrinks while scrolling and would make the hero jump. With much larger text the banner can end a little below the screen |
| Doodles | Six new ones in `inc/doodles.php` (lone star, sun, pen ring, cloud, blob, squiggle), next to the banner's four | One place for all hand-drawn SVG |

## Cost

- Twenty-seven inline SVGs (fifteen only shown on wide or tall screens), a few KB of HTML on the start page.
- The fallback measures the hero on every scrolled frame in older browsers.
- Shape places are tuned to the current text length. Much longer text in Customize changes the paper's height, and the shapes move with it (they're placed in % of the hero).

## Check (on real devices)

- iPhone: the star (top right), sparkle, spiral and sun in the side margins never cover text while scrolling.
- iOS before 26 (the fallback): the shapes follow the scroll smoothly.

## How to change it later

- **Stronger or weaker:** `--hero-shapes-amount` in `tokens.css` (0 = still).
- **A shape's place, size or speed:** its row in `template-parts/front/hero-shapes.php` (`x`, `y`, `size`, `rise`, `dx`, `spin`; `-m` for phones, `-t` for tablets; `mx` for the wide-screen ones).
- **More or fewer shapes on wide or tall screens:** add or remove `wide` / `wider` / `tall` / `taller` rows; the breakpoints are in `hero-shapes.css`.
- **How tall the hero gets:** `--hero-screen-max` in `tokens.css`.
- **The portrait's moves:** `--photo-rise`, `--photo-spin`, `--photo-plate-*` and `--photo-dot-*` in `tokens.css`.

## Files

| What | Where |
| --- | --- |
| Shapes and their places | `carolinelindstrom/template-parts/front/hero-shapes.php` |
| Shape styles | `carolinelindstrom/assets/css/components/hero-shapes.css` |
| Scroll progress and the portrait's moves | `carolinelindstrom/assets/css/components/start-hero-scroll.css` |
| Fallback | `carolinelindstrom/assets/js/start-hero.js` (SCROLL) |
| SVG paths | `carolinelindstrom/inc/doodles.php` |
