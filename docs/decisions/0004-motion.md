# 0004 – Motion follows Material Design and Apple

- **Date:** 2026-10-24
- **Status:** Decided
- **Decided by:** Caroline Lindström

## In short

Every animation on the site follows the motion guidelines of **Material Design 3** and **Apple's Human Interface Guidelines**. In practice: things that appear slow down as they arrive, things that leave speed up as they go and are a bit quicker, and things that just change state use the same curve both ways. This file turns that into rules and tokens for this theme.

## Background

The site is a playground with a lot of motion, built one component at a time. Without shared rules, every component picks its own curve and duration, and the site feels uneven. The question came up with the mobile menu: it opened and closed with the same ease-out curve, and closing felt wrong. Both Material and Apple have answered this, and they agree on the basics.

## The rules

### 1. Enter, exit or change state?

Decide which kind of motion it is first. That gives the curve and the duration.

| Kind | Examples on this site | Curve | Duration |
| --- | --- | --- | --- |
| **Enter** (something appears) | Mobile menu opening, page dimming behind it, list items sliding in | Decelerate: `--ease-out` | `--dur-slow` for panels, `--dur-base` for smaller things |
| **Exit** (something leaves) | Mobile menu closing, dimming fading out | Accelerate: `--ease-in` | One step shorter than its enter: `--dur-base` for panels, `--dur-fast` for smaller things |
| **State change** (it stays on screen) | Sliding menu line, hamburger ↔ X, header becoming compact, toggles | Same curve both ways: `--ease-out` or `--ease-in-out`, or a spring for small details | Same both ways |
| **Feedback** (a press) | `scale(.96)` on buttons | `--ease-spring` | `--dur-fast` |

Why: something that arrives should settle softly where the eye is going (decelerate). Something that leaves isn't where attention is any more, so it should get out of the way quickly (accelerate, shorter). How much shorter is our own rule of thumb (one duration step): it wasn't found stated as a number in Material's documentation when checked on 2026-10-24.

### 2. Duration by size and distance

Bigger things and longer distances get more time, small things less. Material's ranges, mapped to our tokens:

| Size | Material | Token |
| --- | --- | --- |
| Small: color, icons, presses, focus | 50–200 ms | `--dur-fast` (.2s) |
| Medium: buttons, menus, panels | 250–400 ms | `--dur-base` (.3s), `--dur-slow` (.4s) |
| Large: across the screen, page-level | 450–600 ms | `--dur-slide` (.55s), up to .6s |

Nothing the visitor waits on should take longer than about half a second. Decorative motion that nobody waits for, like the sparkles, can be slower.

### 3. Springs and bounce

Apple builds its motion on springs: movement that feels physical and can be interrupted. A small overshoot (`--ease-spring`, `--ease-slide`) is part of this site's character, but only for **small details that stay on screen**: the sliding menu line, the X icon, button presses. Never for large panels or for things leaving the screen.

### 4. Staggered lists

Items in a list may come in one after another (the mobile menu links). Keep the gap small (30–50 ms per item), so the whole list has arrived within about 0.2 s plus its own duration.

Only on the way in. On the way out the container leaves as a whole and its contents go with it, without their own sequence: exits are simpler and quicker than entrances.

### 5. Interruptible

A visitor can change their mind mid-animation, for example by closing the menu while it's still opening. Use **CSS transitions** for state changes. They pick up from where the element is, while keyframe animations restart from the beginning. Keep `@keyframes` for things that only play once (list items coming in) or loop (sparkles).

### 6. Performance

Animate `transform` and `opacity` where possible (CLAUDE.md). An exception needs a reason in the code and in a decision record, like the mobile menu's height (0003).

### 7. Accessibility (this site's rules, on top of the guidelines)

- **Motion only runs with `data-motion="on"` on `<html>`** (`utilities.css`, decision 0002). Without it, everything changes instantly. Never bypass that rule.
- **Anything that moves for more than 5 seconds must be pausable** (WCAG 2.2.2). Nothing flashes more than three times a second (WCAG 2.3.1).
- **Transparency effects** (glass, faded content) get a solid alternative under `prefers-reduced-transparency: reduce`.
- **Motion never carries meaning on its own.** Everything must be understandable with motion off.

## Exceptions

- **The preferences dialog on the first visit** appears without animation. It's shown before the visitor has chosen anything about motion, when motion is off by rule, and it should feel immediate. Decided by Caroline. When it's reopened from "Inställningar" with motion on, it follows the rules: it slides up with `--ease-out` over `--dur-slow` and slides down with `--ease-in` over `--dur-base`, with the backdrop fading in step. The entrance uses `@starting-style` (Chrome and Edge 117, Firefox 129, Safari 17.5, [caniuse](https://caniuse.com/mdn-css_at-rules_starting-style)); older browsers show the dialog at once.

## Tokens

All in `carolinelindstrom/assets/css/base/tokens.css`. Add a new token here and in that file rather than writing a curve or duration in a component.

| Token | Value | Use |
| --- | --- | --- |
| `--ease-out` | `cubic-bezier(.2, .8, .2, 1)` | Enter, and state changes |
| `--ease-in` | `cubic-bezier(.3, 0, 1, 1)` | Exit (Material 3 "standard accelerate") |
| `--ease-in-out` | `cubic-bezier(.6, 0, .3, 1)` | State changes that move from one place to another |
| `--ease-swipe` | `cubic-bezier(.6, 0, .2, 1)` | Fills sweeping across a button |
| `--ease-spring` | `cubic-bezier(.3, 1.4, .4, 1)` | Small details with a bounce, presses |
| `--ease-slide` | `cubic-bezier(.25, 1.12, .4, 1)` | The sliding menu line |
| `--dur-fast` | `.2s` | Small things, presses, small exits |
| `--dur-base` | `.3s` | Medium things, panel exits |
| `--dur-slow` | `.4s` | Panel entrances |
| `--dur-slide` | `.55s` | Large movement |
| `--dur-stagger` | `.04s` | Gap between items coming in one after another |
| `--dur-beat` | `.5s` | A pause between steps in a sequence, so the eye can follow from one to the next |

## Where it's used so far

Everything in the theme follows these rules as of 2026-10-30, apart from the exceptions above.

- **Mobile menu** (decision 0003): opens with `--ease-out` over `--dur-slow`, closes with `--ease-in` over `--dur-base`. The dimming of the page behind follows the same rhythm. The links slide in staggered over `--dur-slow` and leave with the panel.
- **Hamburger ↔ X:** a state change of a button that stays on screen, so `--ease-spring` both ways over `--dur-base`.
- **Header becoming compact and glass, menu underline, light/dark switch:** state changes with `--ease-out` both ways.
- **Logo** (decision 0005): the brackets spring apart with `--ease-spring` over `--dur-slow`, the swash is drawn with `--ease-in-out`. After the first-visit dialog, which leaves with `--ease-in` over `--dur-base`, the strokes are written in one at a time with `--ease-in-out` (`--dur-write-*`). **Exception:** this first-visit intro (logo done after about 2.4 s, content shown after about 3.5 s, `--dur-intro-*`) is far longer than the durations above. It's shown once per visitor, as an entrance rather than a reaction to a click, and it ends within 5 seconds (WCAG 2.2.2).
- **Sliding menu line:** a small detail with `--ease-slide` over `--dur-slide`.
- **Progress bar counting down after a menu link:** large movement, `--ease-out` over `--dur-slide`.
- **Hover colors and presses:** `--dur-fast`, presses with `--ease-spring`.
- **Footer** (decision 0006):
  - **Email wave on hover:** each letter jumps with `--ease-in-out`, 22 ms after the one before; the @ spins. A state change that plays once.
  - **Social pills:** the fill and the colour wipe sweep in with `--ease-swipe` over `--dur-slow` on hover, the same curve both ways. On touch screens they fill on scroll, slower (`--dur-slide`) and one at a time with `--dur-beat` in between.
  - **Small logo:** the header's write-in (`--dur-write-*`), every time it's uncovered.
  - **Stars:** each layer twinkles in its own 3–6 s loop, only `opacity`. It loops for more than 5 seconds, which is allowed because "Pausa rörelse" stops it (WCAG 2.2.2).
  - **Bottom bar reveal:** not an animation. The bar is sticky and the page scrolls over it, so it follows the scroll and needs no motion setting.
- **Code editor** (decision 0009): the window rises in tied to the scroll (`animation-timeline: view()`, linear, since the scroll sets the pace). The code is typed out at 50 characters a second, about 7.5 s, and the caret blinks once a second. It moves for more than 5 seconds, which is allowed because "Pausa rörelse" stops it (WCAG 2.2.2). Hover colours with `--ease-in-out` over `--dur-fast`, both ways.

## Sources

- Material Design 3, Motion: Easing and duration – https://m3.material.io/styles/motion/easing-and-duration
- Apple Human Interface Guidelines, Motion – https://developer.apple.com/design/human-interface-guidelines/motion
- The duration ranges (50–200, 250–400, 450–600 ms) were checked on 2026-10-24 against secondary sources that list Material 3's duration tokens, e.g. https://github.com/google-labs-code/design.md/issues/47. The official page wasn't reachable in that search, so check it when changing the scale.
- WCAG 2.2: 2.2.2 Pause, Stop, Hide and 2.3.1 Three Flashes or Below Threshold
