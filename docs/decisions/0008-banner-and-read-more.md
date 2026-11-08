# 0008 – Start page keyword banner and "Läs vidare" notebooks

- **Date:** 2026-11-01
- **Status:** Decided, built
- **Decided by:** Caroline Lindström (the design and the answers below); built by Claude Code

## In short

Under the hero, a **band of keywords** glides past in an endless loop, a hand-drawn doodle after each word. Then **"Läs vidare"**: a carousel of 3D notebooks, one per page. On hover or keyboard focus the book tilts, the elastic band slips off, the cover opens, and a doodle is drawn on the first page. Both follow `Main.dc.html` (the version of 2026-11-01 with the notebooks), and both are built like the hero (0007): markup in the theme, the words in WordPress.

## Keyword banner

| Design | Theme | Why |
| --- | --- | --- |
| Keywords written into the page | **Appearance → Customize → "Startsidan"**, one per line | Same split as the hero (0007) |
| The whole band hidden from screen readers | The first copy is a list labelled "Nyckelord" (Caroline's choice); the three copies that make the loop seamless are hidden | The keywords say what the site is about, and HTML, CSS and JavaScript aren't in the hero's text |
| Two copies, sliding by half | Four copies, sliding by half | Two copies of six words are about 1,500 px; on a wider screen a gap would show at the end of the loop |
| Loops forever, pauses on hover | Same, and only with motion on | Moves for more than 5 seconds, so it must be possible to pause it ([WCAG 2.2.2](https://www.w3.org/WAI/WCAG22/Understanding/pause-stop-hide.html)): "Pausa rörelse" stops it |

## "Läs vidare" notebooks

| Design | Theme | Why |
| --- | --- | --- |
| Four fixed pages, texts in the page | The pages in the menu at **"Läs vidare på startsidan"**, in its order (without that menu: Om mig, Tillgänglighet, Prestanda, Frontend lead). The text is the page's **excerpt**; the cover words come from a box in the page editor, **"Ord på bokomslaget"** | Everything about a page's notebook is written on that page. The excerpt will also be the page's meta description (Caroline's choice, checklist section 10) |
| Carousel: a region, slides as list items with a role description | The WAI-ARIA carousel pattern ([APG](https://www.w3.org/WAI/ARIA/apg/patterns/carousel/)): the section is the carousel, each slide a group "1 av 4", no autoplay. A screen reader hears "Bild 2 av 4" after the buttons are used | A list item can't also be a group; the pattern is what screen readers expect |
| Dots 12 px | The same dots inside 44 × 44 px buttons | Targets of at least 44 px (CLAUDE.md); 12 px dots 22 px apart would fail even WCAG 2.5.8's 24 px minimum |
| Arrows always active, no press effect; dots without hover | "Föregående" and "Nästa" are marked `aria-disabled` at the ends but stay focusable. Pressed, they shrink to 96 % and spring back like the header buttons; dots grow a little on hover and keyboard focus. The current dot isn't clickable: no pointer, no hover, though it stays a button marked as current for screen readers (Caroline's call) | A disabled button would drop keyboard focus. The press follows 0004 ("Feedback") |
| Faded slides can be clicked | The links in the slides that aren't current ignore the pointer (`pointer-events: none`). A click or tap on such a slide moves the row to it, and over it the pointer is an arrow the way it will go (the site's arrow in a white disc, a small SVG cursor, with the system's resize arrow as fallback). Tab into one scrolls to it and makes it current | Caroline's call: a click on the edge would follow a link the visitor can barely see, so it moves the row instead (2026-11-08). **An exception to "no click handlers on `div`"** (CLAUDE.md), also Caroline's call: the listener on the row is only a pointer shortcut for "Föregående"/"Nästa", which are real buttons, and keyboard and screen readers already reach every slide. The browser doesn't scroll to a focused element that already peeks in, so `read-more.js` does |
| Slides that aren't current are faded to 45 % | Same | Caroline's call: the notebook is a picture that carries no information of its own (its text repeats the slide's title), and a faded slide is never fully in view until it becomes current, since Tab, swiping and the buttons all scroll it in. The accessibility tests skip the notebooks and the faded slides (`hideElements` in `.pa11yci.json` and `.pa11yci.dark.json`); the current slide's own text is tested as usual |
| Scrolls with a hidden scrollbar, inside the page's width | Same with JavaScript, but the row runs to both edges of the window on every screen, the first slide in line with the heading (Caroline's call). **Without JavaScript:** the notebooks are listed one under the other, and the buttons are hidden | Content readable without JavaScript, and no buttons that don't work |
| The notebook only opens on hover | Hover and keyboard focus everywhere. **On touch screens** the current notebook opens by itself once the carousel is half on screen and the scrolling has stopped, and the next one after a swipe. Only with motion on | Hover never happens on a phone, and the book is the best part |
| Pen colours on the page | Same | Caroline's call: decoration, see above |
| The cover swings out above the slide | The scroller has room above the slides (taken from the heading's margin) | A sideways scroller clips upwards too, and cut off the open cover |
| Springy curves .7 to .8 s, opening and closing | Opening springs with `--ease-book` (a softer overshoot than `--ease-spring`); closing uses `--ease-out`, with no overshoot | 0004: springs only for small details, never for things going away. And on closing, the overshoot swung the cover half a degree past closed: it lies only half a pixel above the page, so its edge slid behind the paper and the cover looked too narrow for a moment (found by Caroline) |
| The doodle on the page is invisible until drawn | It's always drawn; the draw-in animation only plays when the cover opens, with motion on | With motion off there is no animation, and the doodle would otherwise never show |

## For accessibility testing

The notebook is decoration (`aria-hidden`) and is skipped by the tests (above). It's still built so axe could read it: the shadow is a real span, the ruled lines are on the `::before` of a layer of their own, the label's stripe is a border, and the page's text is `visibility: hidden` while the closed cover covers it.

## Files

| What | Where |
| --- | --- |
| Markup | `carolinelindstrom/template-parts/front/banner.php`, `carolinelindstrom/template-parts/front/read-more.php` |
| Words, menu, excerpts, cover words | `carolinelindstrom/inc/start-page.php`, `carolinelindstrom/inc/setup.php` |
| Doodles | `carolinelindstrom/inc/doodles.php` |
| Styles | `carolinelindstrom/assets/css/components/keyword-banner.css`, `read-more.css` (carousel), `notebook.css` (the book) |
| Carousel buttons and touch | `carolinelindstrom/assets/js/read-more.js` |
