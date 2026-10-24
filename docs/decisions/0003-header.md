# 0003 – Header: where it differs from the design

- **Date:** 2026-10-23
- **Status:** Decided, built
- **Decided by:** Caroline Lindström

## In short

The header follows `Main.dc.html`: logo, menu with a sliding line and sparkles, the "Pausa rörelse" and "Ljust läge" toggles, a "Meny" button on phones and a scroll progress bar. A few details differ from the design, for accessibility or because of how the theme works. This file lists them.

## Decision

| Design | Theme | Why |
| --- | --- | --- |
| The line under the current page is yellow in light mode too | Same yellow `#D9A900` (`--color-ring-yellow`), and the current page's text is bold | The yellow is only 2.1:1 on the light background, so the line is treated as decoration. Marking the current page isn't required for AA (2.4.8 Location is AAA), and `aria-current` tells screen readers. Bold text is the visible sign that doesn't depend on the line (Caroline's call) |
| Toggles show their label from 721 px | Icon only below 1000 px, with the label kept for screen readers | With labels, the menu wrapped onto three rows at tablet width |
| One line that slides from the current page to the hovered link | Only while motion is on. When motion is off, the current page keeps its line and the hovered or focused link gets its own underline | Without the slide the line jumps, which looked broken (Caroline's call) |
| The header scrolls away with the page | Sticky when the window is at least 600 px tall and JavaScript runs. `header.js` keeps `--header-height` up to date (`ResizeObserver`), and `scroll-padding-top` on `<html>` keeps that strip free, so focus, the skip link and `#anchors` never end up under it (WCAG 2.4.11) | Caroline wanted a sticky header. Short windows (phones held sideways, 400 % zoom) keep it in the flow so it never covers much of the screen. Find in page (Cmd+F) may still put a match under the header in some browsers |
| (Not in the design) | Scrolling down makes the sticky header compact (8 px padding instead of 12 px, made up for with a margin so the page doesn't jump) and see-through: `--color-glass` (65 % of `--color-bg` in dark mode, 40 % in light mode) with `blur(10px)`. Scrolling up brings it back. Solid with `prefers-reduced-transparency: reduce` | Caroline's call. Text over the glass keeps its contrast through a halo of background color around the letters (`--text-halo`, on the menu links and the logo): per WCAG's Understanding 1.4.3, a wide halo counts as the text's background. Buttons are solid, and focus rings in the header get a solid backing ring (double-ring technique) |
| The mobile menu fades in, in the flow | A panel on top of the content under the header row that slides down: a one-row grid whose row grows from `0fr` to `1fr`. That animates height, not only transform and opacity. A tap outside closes it, it scrolls inside if taller than the screen, and the page behind is locked while it's open. The header turns solid and `main` and the footer get `blur(2.3px) opacity(.5)` while it's open (`blur(2.3px) brightness(.9)` with `prefers-reduced-transparency: reduce`) | Pushing the page down while scrolled makes Safari jump (it has no scroll anchoring). The panel is small and only animates on a tap |
| The open menu button is yellow in both modes | Lilac in light mode | Yellow faded into the light background (Caroline's call) |
| (Not in the design) | After following a menu link, the new page picks up where the last one left off: on a phone an open menu is drawn open and slides shut, and the progress bar counts down from how far you had scrolled to 0, then follows the scroll again, and the last page's link hands over to the new one: the weight (DM Sans is variable, so it animates), the color and the phone bar fading over. The desktop line starts under the new link: it was already there, since the clicked link was hovered or focused. The last page's link is marked by a small inline script after the menu in `site-nav.php` (`data-nav-from`, `.navlink--was-current`, `.nav-handoff`). `header.js` leaves a handoff in `sessionStorage` (`clind-handoff`), a script in `<head>` (`clind_handoff_script()` in `inc/enqueue.php`) adds `.menu-closing` / `.progress-returning` and `--progress-from` to `<html>` before paint, and `header.js` removes them afterwards. Only with motion on; the menu is never marked as expanded. Back/forward from the browser cache closes an open menu | Caroline wanted the menu to feel like it stays across page loads. Cross-document View Transitions could do it without the flag, but support is still uneven: a possible later experiment |
| The progress bar is always shown | Hidden when motion is off, and in browsers without scroll-driven animations | It moves, so it follows the motion rule (Caroline's choice) |
| The toggles and menu button always show | Shown only when JavaScript runs (`.js` on `<html>`). Without it the menu stays open on phones | Buttons that do nothing are worse than no buttons |
| Toggles keep their text ("Pausa rörelse", "Ljust läge") and show the state as pressed | The text and icon change to what pressing does ("Spela rörelse", "Mörkt läge", play, moon). No `aria-pressed`, since a changing text plus "pressed" would contradict each other. Both texts are stacked in one grid cell, so the button keeps its width | Caroline's call; no layout shift on click |
| Pressing a toggle | Saves a fixed choice ("Ljust"/"Mörkt", motion "På"/"Av") over "Som min enhet" | Caroline's choice. Back to "Som min enhet" through "Inställningar" in the footer |
| Order on phones: logo, toggles, menu button | Same look, but the keyboard reaches the menu button and the open menu before the toggles | The links follow straight after the button that opens them |

## Where it lives

| What | File |
| --- | --- |
| Markup | `carolinelindstrom/header.php`, `template-parts/header/site-nav.php`, `template-parts/header/toggles.php` |
| Toggles, menu button | `carolinelindstrom/assets/js/header.js` |
| Menu link label (reserved bold width) and sparkle markup | `clind_nav_item_title()` in `carolinelindstrom/inc/template-tags.php` |
| Styles | `assets/css/layout/header.css`, `components/toggles.css`, `menu-button.css`, `nav-indicator.css`, `scroll-progress.css` |
