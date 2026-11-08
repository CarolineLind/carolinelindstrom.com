# 0011 – "Läs vidare": scrolling down moves the carousel sideways

- **Date:** 2026-11-08
- **Status:** Decided, built
- **Decided by:** Caroline Lindström; built by Claude Code

## In short

On desktop, the "Läs vidare" carousel (0008) becomes part of the page's scroll. When the section reaches the top of the window it glides to a stop under the header, and scrolling on moves the notebooks sideways instead of the page down. After the last notebook the section glides off and the page carries on. Nothing listens to the wheel: the page scrolls as usual and only *where* the carousel stands follows from it, so scrollbar, keys and trackpad all work and anyone can scroll past.

Where it can't work well, the carousel is the ordinary one from 0008.

## Decision

| What | How | Why |
| --- | --- | --- |
| When it's on | Windows wider than 1000 px, motion on (`data-motion="on"`), and the whole carousel fits in the window under the header. `read-more.js` tries it and measures; otherwise the ordinary carousel | Caroline's call: desktop first. On touch screens a sideways swipe would fight the page's scroll. Short windows, 200 % text zoom and 400 % page zoom don't fit, so they get the ordinary carousel automatically |
| No scroll hijacking | The section is made taller by the distance the row travels; its inside sticks (`position: sticky`) and the row's position is worked out from the page's scroll | Only the page scrolls, so every way of scrolling still works. The usual problems with "scrolljacking" (changed speed or direction, no way out) don't apply as long as the speed stays the page's ([NN/g, Scrolljacking 101](https://www.nngroup.com/articles/scrolljacking-101/), 2023) |
| Soft start and stop | Four stretches, each ending at the speed of the next: the section glides up and slows to still (`CATCH`, half the window's height), the row speeds up from still (`RAMP`), moves 1 px per px, slows to still, then the section speeds up to the page's speed as it lets go. The speeds follow smoothstep curves | Caroline found the hard stop and start too abrupt. `RAMP = 1` (a whole window's height) is Caroline's call after trying it |
| No gap after it | The inside keeps its natural height and is centred under the header; sticky lets go as soon as the row stops, and the inside is held back less and less instead. The section's own padding gives back the room the ordinary carousel has | An inside as tall as the window, and a sticky that let go late, left up to half a screen of empty background before the code editor. Now the gaps above and below are the same as without pinning (112 px and 64 px) |
| Scroll-driven animations | The glide and the row's movement are CSS animations on the section's `view-timeline` (`read-more-pin.css`); `read-more.js` only writes where each stretch starts and ends. The row's three stretches add up with `animation-composition: add` | JS that moves things lags a frame behind the page's scroll, and that showed as a jolt where sticky lets go. Chrome and Edge 115+, Safari and iOS Safari 26+, Firefox 159+ ([caniuse](https://caniuse.com/mdn-css_properties_animation-timeline_view), checked 2026-11-08); `animation-composition` is Baseline since July 2023 ([MDN](https://developer.mozilla.org/en-US/docs/Web/CSS/animation-composition)) |
| Fallback | Without scroll-driven animations, `read-more.js` moves the inside (`translateY`) and the row (`scrollLeft`) itself, at most once per frame | Older browsers. It can show the small jolt above |
| Keyboard and buttons | Tab into a notebook, the dots and "Föregående"/"Nästa" scroll the *page* to where that notebook stands | Focus must stay visible and unhidden ([WCAG 2.4.11](https://www.w3.org/WAI/WCAG22/Understanding/focus-not-obscured-minimum.html)): the browser doesn't scroll to an element that's only moved sideways |
| Motion off | The ordinary carousel | Moving sideways in answer to scrolling down is motion from interaction ([Understanding 2.3.3](https://www.w3.org/WAI/WCAG22/Understanding/animation-from-interactions.html), AAA); the site's motion rule (0002) covers it |
| Click on a faded notebook | Moves the carousel to it; over it the pointer is an arrow the way it will go, drawn like the "Föregående"/"Nästa" buttons when hovered. Recorded in 0008, with its exception to "no click handlers on `div`" | Caroline's call. It works in the ordinary carousel too |
| No hover right after such a click | The new current notebook's link ignores the pointer until the row has stopped and the pointer moves | The pointer is already over the notebook when it becomes current, and the book opened while the row was still gliding (a jolt) |
| The notebook's page edges | Hidden while the book is closed, shown when it tilts (`notebook.css`) | Moved by fractions of a pixel, the 3D renderer sometimes drew the edge-on page edge as a light line through the dark cover (found by Caroline). Closed and seen head-on, the edges show nothing anyway |
| Scroll snapping | Tried and removed | Caroline didn't like the feel. `mandatory` isn't possible: the whole page would snap to the four notebooks |

## Cost

- The section is several screens tall on desktop: the visitor scrolls through every notebook to get past it.
- Positions are measured on load, on resize, when the fonts are in and when motion is switched. Content above the section that changes height in another way leaves the stretches slightly off until the next resize.
- The pinned mode isn't covered by pa11y, which runs with motion off.

## Check (on real devices)

- Safari and Firefox on macOS: the glide in and out, and the last notebook, without jolts.
- VoiceOver on macOS: reading the carousel and moving between notebooks.
- Text zoom 200 % and page zoom 400 %: the ordinary carousel.

## Files

| What | Where |
| --- | --- |
| Pinned mode, scroll-driven animations | `carolinelindstrom/assets/css/components/read-more-pin.css` |
| Measuring, stretches, fallback, buttons, click | `carolinelindstrom/assets/js/read-more.js` |
| Pointer, hover held back | `carolinelindstrom/assets/css/components/read-more.css` |
| Page edges | `carolinelindstrom/assets/css/components/notebook.css` |
