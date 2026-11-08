# 0007 – Start page hero: built in the theme, words in Customize

- **Date:** 2026-11-01
- **Status:** Decided, built. The portrait's hover effect and layout were replaced by 0010
- **Decided by:** Caroline Lindström

## In short

The hero at the top of the start page is markup in the theme (`template-parts/front/hero.php`), not blocks in the editor. Caroline still owns the words and the picture: the heading, text and link are set under **Appearance → Customize → "Startsidan"**, and the portrait is the start page's featured image. The sheet of paper tilts toward the mouse, as in the design (`Main.dc.html`).

## Background

The first version was built from blocks in the editor (columns, groups, a `[sky]` shortcode for the stars), with the CSS aimed at WordPress's own wrappers such as `.wp-block-group__inner-container`. The tilt didn't work: the design moves the paper with a script that writes where the mouse is, and only the CSS had been carried over.

Three ways were weighed:

| | Editor blocks | Theme template | Template + Customize (chosen) |
| --- | --- | --- | --- |
| Change text without code | Yes | No | Yes |
| Markup the theme controls | No: WordPress's wrappers can change with an update or a block setting | Yes | Yes |
| Can content break it? | Yes: a removed class or moved block breaks the design silently; a removed shortcode prints `[sky]` | No | No |
| The script finds the paper by | WordPress's class names | Its own class | Its own class |

The template with Customize fields gives stable markup and keeps the words Caroline's, the same way as "Kontakt i sidfoten" (0006).

## Decision

| Design | Theme | Why |
| --- | --- | --- |
| Hero written into the page | Theme markup; the words in Customize → "Startsidan", the portrait as the start page's featured image | See above. The h1 is the hero's heading; the rest of the start page's content follows below |
| The paper tilts toward the mouse | Only with motion on (`data-motion="on"`) and a pointer that can hover (`(hover: hover) and (pointer: fine)`). The script only writes `--nx` and `--ny`; the CSS does the rest. Pausing motion lays it flat at once | Motion rules (0002, 0004). Touch screens have no pointer to follow |
| (Not in the design) | With the link focused by keyboard, the paper leans a little (`:has(:focus-visible)`) | Hover effects also work with the keyboard (CLAUDE.md). Browsers without `:has()` skip the rule and the paper stays flat |
| The shadow is a `box-shadow` that changes with the mouse | The shadow is its own layer behind the paper, moved with `transform` | Animate only `transform` and `opacity` (CLAUDE.md): a changing shadow is repainted on every mouse move |
| Text rises in over .8 s, delays .12 to .38 s | `--dur-slide` (.55 s) with `--ease-out`, delays in steps of `--dur-stagger` | Page-level motion is 450 to 600 ms in Material's ranges (0004); the first screen should settle quickly |
| Start state `opacity: 0` on the elements | The start state is in the keyframes (`fill-mode: both`) | With motion off, animations are switched off (`utilities.css`); an `opacity: 0` on the element would then hide the text for good |
| Ruled lines and the margin line on the paper itself | On the pseudo-elements of an empty `aria-hidden` layer | axe can't check contrast through a gradient behind text (CLAUDE.md, Gotchas). The lines are 5 % of the text colour |
| Text link: arrow, gap grows on hover | The arrow moves with `transform` on hover **and** keyboard focus | No layout work on hover; keyboard parity |
| Background: dots, glow and stars | The footer's dots and stars (`sky.css`, shared through `template-parts/sky.php`), fading in towards the portrait | Caroline's call. The stars twinkle only with motion on (WCAG 2.2.2, as in 0006) |
| Photo placeholder | The featured image, cropped to 4:5 with `object-fit: cover`, never lazy-loaded and fetched first (`fetchpriority="high"`) | It's the largest image on the first screen (LCP) |
| Portrait as wide as the screen on phones, aligned left | (0010 moved the portrait under the paper below 1000 px, at most 320 px on tablets.) From 720 px down: centred, at most 260 px (`--photo-max-mobile`) and 3/4 of the width | Caroline's call: full width it filled the screen and looked off-centre |
| Portrait with a thin border | No border | Caroline's call |
| The shapes behind the portrait step apart on hover | **Replaced by 0010:** they follow the scroll on every device. Before that: same with a mouse. **On touch screens** they follow the scroll: apart as the portrait comes up to the middle of the screen, back together as it scrolls on out, both ways. A scroll-driven animation (`animation-timeline`) in CSS; where that isn't supported, `start-hero.js` sets them apart while the portrait is above the line a third of the way up the screen. Only with motion on. **On scroll they move 2.5 times as far as on hover** (15 px instead of 6; `--photo-apart-scroll`) | Caroline's call: on phones hover never happens, and the effect should come and go with the scroll. At the hover distance it was barely noticed while scrolling. Scroll-driven animations: Chrome and Edge 115+, Safari and iOS Safari 26+, Firefox only behind a setting visitors turn on themselves ([caniuse](https://caniuse.com/mdn-css_properties_animation-timeline_view), checked 2026-11-01). The fallback line is the footer's (0006) |

## Cost

- The hero's look can't be changed in the editor, only its words and picture.
- The tilt isn't seen on touch screens.

## How to change it later

- **Other words or another portrait:** Customize → "Startsidan", and the start page's featured image.
- **How far it tilts, how deep the text floats, how fast it follows:** `--paper-tilt-x`, `--paper-tilt-y`, `--paper-depth` and `--dur-follow` in `tokens.css`.
- **Back to editor blocks:** remove `get_template_part( 'template-parts/front/hero' )` from `front-page.php`. The script needs an element with the class `start-hero`.

## Files

| What | Where |
| --- | --- |
| Markup | `carolinelindstrom/template-parts/front/hero.php` |
| Customize fields and content | `carolinelindstrom/inc/start-page.php` |
| Styles | `carolinelindstrom/assets/css/components/start-hero.css` |
| Tilt | `carolinelindstrom/assets/js/start-hero.js` |
| Stars | `carolinelindstrom/template-parts/sky.php`, `carolinelindstrom/assets/css/components/sky.css` |
