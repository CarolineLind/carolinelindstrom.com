# 0009 – Start page code editor

- **Date:** 2026-11-07
- **Status:** Decided, built
- **Decided by:** Caroline Lindström (the design and the answers below); built by Claude Code

## In short

Between "Läs vidare" and the footer, a code editor window introduces Caroline as a JavaScript object, with a file tree beside it and a terminal below. The `'light'` in `tema: 'light'` is a button that switches to light mode, and the terminal answers. With motion on, the code types itself out when it scrolls into view. It follows `Main.dc.html`, and like the hero (0007) it's built into the theme: the lines live in `inc/code-editor.php`, not in WordPress.

## Where the theme differs from the design

| Design | Theme | Why |
| --- | --- | --- |
| The `'light'` button is as tall as the line, about 28 px | It looks the same, but an invisible `::before` makes the hit area at least 44 × 44 px. It reaches into the lines above and below, which hold nothing else to press | Targets of at least 44 px (CLAUDE.md). Caroline: a bigger hit area is fine, but the token has to look the same |
| In light mode the button is still there and does nothing | In light mode, and without JavaScript, `'light'` is plain text. The page's colours are chosen from `<html>`'s `data-theme`, so the right version shows on first paint | Caroline's call: there's nothing left to press. A button that does nothing would confuse keyboard and screen reader users |
| The terminal answers in an `aria-live` region | When the button is pressed it disappears, so focus moves to the terminal's answer, which a screen reader then reads. No `aria-live` | Focus must not be lost when the focused element vanishes ([WCAG 2.4.3](https://www.w3.org/WAI/WCAG22/Understanding/focus-order.html)). Moving focus already gets the answer read out, and an `aria-live` region as well would read it twice |
| Typing empties the code and fills it in again, letter by letter | The real code always stays in the page. With motion on, an `aria-hidden`, `inert` copy is laid on top and typed out, while the real code underneath is hidden with `opacity: 0`, which screen readers still read. The typing stops and the real code shows straight away if the code gets focus or a press, or if motion is turned off | The code is content: it has to be readable without JavaScript and for screen readers while it's being typed. The copy covers exactly the same area, so nothing moves (CLS) |
| The typing speed is about 3 characters per frame (about 180 a second), then the caret blinks for 2.4 s more | 50 characters a second (Caroline: slower), so about 7.5 s, and the caret lingers for 1.2 s. Time-based, so it's equally fast on 60 and 120 Hz screens | It moves for more than 5 seconds, so it must be possible to pause it ([WCAG 2.2.2](https://www.w3.org/WAI/WCAG22/Understanding/pause-stop-hide.html)): "Pausa rörelse" stops it and shows the whole code, as with the footer's stars (0004). Focus or a press in the code stops it too |
| The code is typed one character after another | Like in a real editor, `'`, `{` and `[` close themselves: the partner shows up after the caret at once, and the typing "types over" it later. A `{` or `[` that closes on a later line shows `{}` until the line break, then its `}` drops to a line of its own below the caret (with indent and line number) and is pushed down as lines are typed | Caroline's idea: that's how it feels to write code. Only the typing copy does this; the real code is unchanged |
| Typing could make the window grow at the end | No layout shift while typing (measured: 0, desktop and phone). The lines are joined with newlines only between them, and the copy's light token stays hidden until the typing reaches it | A newline after the last line gave the caret a line 19, so the window grew by a line and shrank again. The empty token's padding rode down the lines. Both counted as CLS |
| The terminal grows when the answer appears | The answer's two lines hold their space from the start (`visibility: hidden`), and the code area has two empty lines below the code (`--code-empty-lines`), like a real editor | Caroline: nothing should shift when the answer appears (CLS) |
| Line numbers at 60 % of the muted colour | Same: 3.5:1 in dark mode and 2.9:1 in light mode | Caroline's call: they're decoration and carry no meaning, and are hidden from screen readers. Text that is pure decoration is exempt from the contrast requirement ([WCAG 1.4.3](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html), "Incidental"). axe doesn't flag them, since they're `aria-hidden` |
| File tree rows 30 px tall | 30 px with a mouse, `--tap-min` (44 px) on touch screens (`pointer: coarse`) | Touch targets of 44 px. With a mouse the design's 30 px is above WCAG 2.5.8's 24 px minimum ([Understanding 2.5.8](https://www.w3.org/WAI/WCAG22/Understanding/target-size-minimum.html)). The tree is shorter than the code, so the window keeps its height either way |
| Strings in light mode are dark pink (`#A8326E`) | Same: `--color-code-string` changed from dark yellow to the design's pink (6.3:1 on white) | Caroline's call. The token is shared, so code blocks in page content turn pink in light mode too |
| 64 px above the window and 96 px below | `--section-space` both above and below (96 px, 72 px on phones) | Caroline: the spacing felt uneven. Now it's the same as other sections |
| `tema.css` and `README.md` in the tree | Plain text, not links | Caroline's call: they're "dead" files. The four pages are real links |

## How to change it

- **The code:** `clind_code_editor_lines()` in `inc/code-editor.php`, one string per line, with `{k:…}`, `{s:…}`, `{p:…}` and `{c:…}` marking the colours. `{light}` is the button.
- **The terminal's text:** `template-parts/front/code-editor.php`.
- **Typing speed:** `SPEED` in `assets/js/code-editor.js`, in characters per millisecond.
