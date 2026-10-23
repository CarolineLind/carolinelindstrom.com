# 0001 – Self-hosted, trimmed fonts

- **Date:** 2026-10-23
- **Status:** Decided and done
- **Decided by:** Caroline Lindström

## In short

The site's three fonts are hosted by the theme instead of Google Fonts, and they are trimmed to stay under 100 KB in total. The trimming changes a few things compared to the design. **If the fonts ever look a little off, the reason is almost certainly in the list under "What looks different".**

## Background

The design (Claude Design canvas "carolinelindstrom.com – dark mode") loads three fonts from Google Fonts:

| Font | Used for | In the design |
| --- | --- | --- |
| Bricolage Grotesque | Headings, logo-sized display text | Optical size 12–96, weights 400, 600, 800 |
| DM Sans | Body text | Optical size 9–40, weights 400, 500, 700 |
| JetBrains Mono | Small labels, code | Weights 400, 600 |

Two problems with that:

1. **Privacy.** Loading from Google sends every visitor's IP address to Google. A German court ruled in 2022 that doing this without consent breaks the GDPR.
2. **Performance.** As the design loads them, the Latin files alone are 180 KB. The budget is 100 KB per page (see the quality goals in `carolinelindstrom/README.md`).

## Decision

- **Host the fonts in the theme** (`carolinelindstrom/assets/fonts/`). No requests to Google.
- **Lock Bricolage Grotesque's optical size to 96.** Keeping the full optical size range made the file 77 KB instead of 41 KB.
- **Keep only the weight ranges the design uses:** Bricolage 400–800, DM Sans 400–700, JetBrains Mono 400–600.
- **Keep only the characters a Swedish site needs** (see the list in `tools/fonts/subset-fonts.sh`).
- **Preload** Bricolage Grotesque and DM Sans, since they're on the first screen.
- **Size-matched fallbacks:** Arial and Courier New are resized to take up the same space as each web font, so nothing jumps when the fonts arrive.

## Result

| File | Size |
| --- | --- |
| `bricolage-grotesque.woff2` | 35.8 KB |
| `dm-sans.woff2` | 34.5 KB |
| `jetbrains-mono.woff2` | 28.9 KB |
| **Total** | **99.1 KB** (was 180 KB) |

The first screen preloads 70 KB. JetBrains Mono loads only when a page uses it.

## What looks different

These are the deliberate trade-offs. Each one is something you might notice.

1. **Bricolage Grotesque always has its "large text" shape.** The font normally adjusts its letter shapes to the text size. Ours is locked to the shape made for 96 px. Big headings look exactly as designed. Smaller Bricolage text (the banner words at 22–30 px, card titles at 28–36 px, the mobile menu at 24 px) looks slightly tighter and finer than the design.
2. **DM Sans has no optical sizing either.** The design asked for the range 9–40. We use Google's default, which suits normal body text sizes. Very small or very large DM Sans text can look slightly different from the design.
3. **No italics.** None of the files contain italic letters. Italic text (`<em>`, code comments) is slanted by the browser instead. That "fake italic" looks more mechanical than a real italic.
4. **Weights outside the ranges use the nearest one.** Bold DM Sans above 700, Bricolage above 800 or JetBrains Mono above 600 render at the closest available weight.
5. **Characters outside the subset come from a system font.** That includes letters from other languages (for example Polish ł or Turkish ı), arrows (← →), the check mark ✓ in the code section's terminal, and emoji. They look noticeably different next to the surrounding text.
6. **A short flash of Arial or Courier New on slow connections.** The fallbacks are sized to match, so the layout doesn't move, but the letter shapes differ for that moment (`font-display: swap`).
7. **The fonts don't update by themselves.** The files are Google's versions from 2026-10-23 (Bricolage v9, DM Sans v17, JetBrains Mono v24). Later improvements to the fonts don't reach the site until the files are rebuilt.

## How to change it

The source files are in `tools/fonts/src/`. They were downloaded from these Google Fonts requests (Latin subset):

```
https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@96,400..800
https://fonts.googleapis.com/css2?family=DM+Sans:wght@400..700
https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400..600
```

- **Add characters:** edit `UNICODES` in `tools/fonts/subset-fonts.sh` and run it. If Google's Latin file doesn't have the character, download the matching subset as well.
- **Bring back optical sizing:** download Bricolage with `opsz,wght@12..96,400..800` instead (about 77 KB, over budget) and run the script again.
- **Add italics or other weights:** download the extra files, add them to the script and add `@font-face` rules in `carolinelindstrom/assets/css/base/fonts.css`.
- **After any change:** run `tools/fonts/.venv/bin/python3 tools/fonts/fallback-metrics.py` and paste the new values into `fonts.css`, so the fallbacks stay matched.

## Licenses

All three fonts are licensed under the SIL Open Font License 1.1, which allows self-hosting and subsetting. The license texts are in `carolinelindstrom/assets/fonts/OFL-*.txt`.
