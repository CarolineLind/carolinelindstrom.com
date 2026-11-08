# 0010 – Start page hero: doodles that rise with the scroll

- **Date:** 2026-11-08
- **Status:** Decided, built
- **Decided by:** Caroline Lindström

## In short

Twelve hand-drawn doodles lie behind the hero's paper and portrait. As the page scrolls they rise, drift a little sideways and turn, each at its own speed, so they slide apart and the hero gets depth. The portrait joins in: the photo rises and tips back, the lilac plate lags behind and the yellow dot floats off. This replaces the portrait's hover effect from 0007.

The shapes, their places and their speeds come from a prototype Caroline tried out (a Claude artifact, "Hero med parallaxformer"), at **strength 2.0**: every move is twice the prototype's base values.

## Decision

| What | How | Why |
| --- | --- | --- |
| Strength 2.0 | One token, `--hero-shapes-amount: 2`, multiplies every scroll move in the hero, the portrait's too | Caroline's call after trying 0 to 3 in the prototype. One number to turn it up or down |
| Phones follow the prototype | The same sideways drift as on desktop. The four shapes in the side margins lie on top of the paper's edge, small and centred in the 24 px gutter | Caroline's call. At 2.0 the star drifts up to 16 px inwards: check that no shape reaches the text on a real phone (see "Check") |
| The portrait's hover effect is removed | The plate and the dot follow the scroll on every device, no hover state | Caroline's call: one behaviour everywhere instead of hover on desktop and scroll on touch (0007) |
| Scroll-driven animations in CSS | Three numbers, `--p` (page), `--pp` (portrait) and `--pb` (hero's bottom edge), are animated on `.start-hero` with `animation-timeline` and inherited | No script on the main path. Chrome and Edge 115+, Safari and iOS Safari 26+, Firefox 159+ ([caniuse](https://caniuse.com/mdn-css_properties_animation-timeline_view), checked 2026-11-08) |
| Fallback | `start-hero.js` writes the same three numbers at most once per frame, only with motion on | Older browsers, above all iOS before 26 |
| Only `translate`, `rotate`, `scale` | The scroll moves the separate properties, so the entrance's `transform` and the SVG's centring stay untouched | Animate only transform-like properties (CLAUDE.md) |
| Motion off | Everything lies still at its start point; the two "late" shapes stay hidden below the hero | `data-motion` rule (0002). Parallax is named as non-essential motion under WCAG 2.3.3 ([Understanding 2.3.3](https://www.w3.org/WAI/WCAG22/Understanding/animation-from-interactions.html), AAA); the site's pause button covers it |
| Pop-in | The shapes pop in once, one after another (`--ease-out`, `--dur-slide`, 90 ms apart), finished in under 2 s | Entering motion (0004). Shorter than 5 s, and the scroll moves only while the visitor scrolls, so nothing moves on its own for longer than 5 s (WCAG 2.2.2, [Understanding 2.2.2](https://www.w3.org/WAI/WCAG22/Understanding/pause-stop-hide.html)) |
| Layout changed | From 1000 px: a grid with the portrait in its own column (`--photo-col`), and the same space (`--hero-side`) at both screen edges and between paper and portrait. Below 1000 px: the portrait centred under the paper | The shapes' places are % of the hero; they need room at the edges and in the gap, as in the prototype |
| A disc behind each shape | Each SVG sits on a round disc of the page's colour, `--color-bg` (what shows behind the sky), with a 10 px glow of the same colour (`--color-shape-halo`, `--shape-halo-blur`) | Caroline's call: without it the shapes ran together with the dots in the background |
| Smaller heading | The hero's h1 uses `--fs-hero-title` (`clamp(52px, 7vw, 112px)`) instead of `--fs-h1` | With the portrait in its own column the paper is narrower, and from about 1600 px the heading broke onto three lines. Now it's two lines at every desktop width (measured 1000 to 1920 px) |
| Doodles | Six new ones in `inc/doodles.php` (lone star, sun, pen ring, cloud, blob, squiggle), next to the banner's four | One place for all hand-drawn SVG |

## Cost

- Twelve inline SVGs, a few KB of HTML on the start page.
- The fallback measures the hero on every scrolled frame in older browsers.
- Shape places are tuned to the current text length. Much longer text in Customize changes the paper's height, and the shapes move with it (they're placed in % of the hero).

## Check (on real devices)

- iPhone: the star (top right), sparkle, spiral and sun in the side margins never cover text while scrolling.
- iOS before 26 (the fallback): the shapes follow the scroll smoothly.

## How to change it later

- **Stronger or weaker:** `--hero-shapes-amount` in `tokens.css` (0 = still).
- **A shape's place, size or speed:** its row in `template-parts/front/hero-shapes.php` (`x`, `y`, `size`, `rise`, `dx`, `spin`; `-m` for phones, `-t` for tablets).
- **The portrait's moves:** `--photo-rise`, `--photo-spin`, `--photo-plate-*` and `--photo-dot-*` in `tokens.css`.

## Files

| What | Where |
| --- | --- |
| Shapes and their places | `carolinelindstrom/template-parts/front/hero-shapes.php` |
| Shape styles | `carolinelindstrom/assets/css/components/hero-shapes.css` |
| Scroll progress and the portrait's moves | `carolinelindstrom/assets/css/components/start-hero-scroll.css` |
| Fallback | `carolinelindstrom/assets/js/start-hero.js` (SCROLL) |
| SVG paths | `carolinelindstrom/inc/doodles.php` |
