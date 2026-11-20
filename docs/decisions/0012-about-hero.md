# 0012 – "Om mig" hero: polaroids you can drag, and reading time on every page

- **Date:** 2026-11-17
- **Status:** Decided, built
- **Decided by:** Caroline Lindström; built by Claude Code

## In short

The top of "Om mig" follows the design's story version (`OmMigB.dc.html`): the h1 and a short text beside a pile of three taped polaroids, with three doodles rocking around them. The pile spreads on hover and focus, each polaroid can be dragged with a mouse or a finger, and a button, "Blanda bilderna", scatters them. Like the start page hero (0007), it is markup in the theme with the words and images in Customize → "Om mig".

Every page also gets a row with reading time and word count above it, and the publish and update dates below it, as on all of the design's page boards.

## Decisions

**Built in the theme, words and pictures in Customize.** The same reasons as 0007: the script needs markup the theme controls, and content can't break it. Caroline first leaned towards the text in the editor and the pictures in Customize; that would split one component across two places that must line up, so it was all moved to Customize (Caroline's call, 2026-11-17). The rest of the page stays block editor content.

**The text comes first in the markup.** The pile is drawn first (CSS `order`), but the h1 is read first by screen readers.

**Drag on touch, but only on the polaroids** (Caroline's call). `touch-action: none` is set on the polaroids alone, so a finger anywhere else scrolls the page. The cost: on a phone the pile is most of the screen's width, and a thumb that lands on a polaroid while scrolling moves the polaroid instead. To be tried on a real phone.

**The doodles can be dragged too** (Caroline's call, 2026-11-20), with the same limits as the polaroids. Their whole box takes the pointer, not only the thin lines, so they are easy to grab. "Blanda bilderna" leaves them where they are: they are decoration, and like the polaroids' places, where they lie carries no information.

**"Blanda bilderna" is the alternative to dragging.** WCAG 2.2 SC 2.5.7 Dragging Movements (AA) says: "All functionality that uses a dragging movement for operation can be achieved by a single pointer without dragging, unless dragging is essential". It needs an alternative with *equivalent functionality*, not the same component ([Understanding 2.5.7](https://www.w3.org/WAI/WCAG22/Understanding/dragging-movements)). The button rearranges the pile with one tap or a key press, which also covers 2.1.1 Keyboard. **Not settled by the source:** the Understanding document doesn't discuss decorative or playful dragging, and its examples (a slider, a colour wheel) let the alternative choose the end point. The button scatters at random instead. We judge that enough here, since where the polaroids lie carries no information and nothing is lost without dragging; if an audit says otherwise, the stricter fix is "tap a polaroid, then tap where it should go".

**Up to half off the screen, never lost.** A dragged polaroid can go up to half its width past the hero's sides (Caroline's call, 2026-11-20: it's more fun to push one off the edge), and stops at its top and bottom. Half is always left to grab it back by. "Blanda bilderna" keeps them wholly inside. The hero clips sideways with `overflow-x: clip`, so the page never scrolls sideways. The polaroids and doodles stack from z-index 4 under the button (10), so they never cover it, and under the text, which has a ring of the page's colour (`--text-halo`) in case one is dragged under it.

**Smaller spread to the left than the design.** The design moves the back polaroid 80 px left on hover, which only fits in the margin of a 1440 px window; anywhere narrower it went off screen. It moves 6 px left and 30 px up instead. The hero also has a little more room above (72 px, not 56) so the spread doesn't cover the reading time.

**The pile scales with its width.** Every place and size is the design's px at full width (500, or 332 on phones), multiplied by `--u`, one design px at the pile's real width (`100cqw / --pile-w`). The pile fits from 320 px without its own numbers per width.

**The doodles rock all the time,** which is motion longer than 5 seconds (SC 2.2.2). They only run with motion on and stop with the header's motion button, like the rest of the site's ambient motion (0004).

## Reading time

A range, from an average reader to a slower one: words ÷ 240 to words ÷ 175, rounded up. Adults read silently at 238 words a minute on average (non-fiction, English), and read aloud at 183 (Brysbaert 2019, ["How many words do we read per minute? A review and meta-analysis of reading rate"](https://doi.org/10.1016/j.jml.2019.104047), *Journal of Memory and Language* 109; figures from the abstract). The fast end is that average; the slow end is a little below reading aloud, for someone who takes their time. **Not verified:** a Swedish figure (none was found) and the spread of readers around the average (the paper itself couldn't be read here); the 175 is a judgement, easy to change in `inc/page-meta.php`. The word count includes the "Om mig" hero's words, since the visitor reads them too.

"Uppdaterad" only shows when the page was changed on a later day than it was published.
