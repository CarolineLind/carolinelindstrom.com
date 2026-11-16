# 0001 – Self-hosted, trimmed fonts

- **Date:** 2026-10-23, heading font changed 2026-11-16
- **Status:** Decided and done
- **Decided by:** Caroline Lindström

## In short

The site's three fonts are hosted by the theme instead of Google Fonts, and they are trimmed to stay under 100 KB in total. The trimming changes a few things compared to the design. **If the fonts ever look a little off, the reason is almost certainly in the list under "What looks different".**

## Background

The design (Claude Design canvas "carolinelindstrom.com – dark mode") loads three fonts from Google Fonts:

| Font | Used for | In the design |
| --- | --- | --- |
| EB Garamond | Headings, banner words, the contact email, menu links on mobile | Weights 400–800 and italics loaded; every heading is set in 500 |
| DM Sans | Body text | Optical size 9–40, weights 400, 500, 700 |
| JetBrains Mono | Small labels, code | Weights 400, 600 |

Until 2026-11-16 the headings were Bricolage Grotesque (weights 400–800, optical size locked to 96, 35.8 KB). The design then switched to EB Garamond.

Two problems with loading from Google:

1. **Privacy.** Loading from Google sends every visitor's IP address to Google. A German court ruled in 2022 that doing this without consent breaks the GDPR.
2. **Performance.** As the design loads them, the files are well over the budget of 100 KB per page (see the quality goals in `carolinelindstrom/README.md`).

## Decision

- **Host the fonts in the theme** (`carolinelindstrom/assets/fonts/`). No requests to Google.
- **EB Garamond in one weight, 500.** The design sets every heading in 500, so the other weights would never be used. One weight is 20.5 KB; the full 400–800 range is 37.0 KB and would take the total to about 100.3 KB, over budget.
- **No italics** in any font (Caroline's choice, 2026-11-16). Nothing on the site uses them.
- **Keep only the weight ranges the design uses** for the others: DM Sans 400–700, JetBrains Mono 400–600.
- **Keep only the characters a Swedish site needs** (see the list in `tools/fonts/subset-fonts.sh`).
- **Preload** EB Garamond and DM Sans, since they're on the first screen.
- **Size-matched fallbacks:** Times New Roman (headings), Arial and Courier New are resized to take up the same space as each web font, so nothing jumps when the fonts arrive.

## Result

| File | Size |
| --- | --- |
| `eb-garamond.woff2` | 20.5 KB |
| `dm-sans.woff2` | 34.5 KB |
| `jetbrains-mono.woff2` | 28.9 KB |
| **Total** | **83.9 KB** |

The first screen preloads 55 KB. JetBrains Mono loads only when a page uses it.

## What looks different

These are the deliberate trade-offs. Each one is something you might notice.

1. **Headings can't be bolder than 500.** `--fw-display` (500) is used for everything in the heading font. Asking for a heavier weight would make the browser fake a bold by thickening the letters, which looks smudged. Use DM Sans for bold short headings.
2. **DM Sans has no optical sizing.** The design asked for the range 9–40. We use Google's default, which suits normal body text sizes. Very small or very large DM Sans text can look slightly different from the design.
3. **No italics.** None of the files contain italic letters. Italic text (`<em>`, code comments) is slanted by the browser instead. That "fake italic" looks more mechanical than a real italic, and is most noticeable in Garamond.
4. **Weights outside the ranges use the nearest one.** Bold DM Sans above 700 or JetBrains Mono above 600 render at the closest available weight.
5. **Characters outside the subset come from a system font.** That includes letters from other languages (for example Polish ł or Turkish ı), arrows (← →), the check mark ✓ in the code section's terminal, and emoji. They look noticeably different next to the surrounding text.
6. **A short flash of Times New Roman, Arial or Courier New on slow connections.** The fallbacks are sized to match, so the layout doesn't move, but the letter shapes differ for that moment (`font-display: swap`).
7. **The fonts don't update by themselves.** The files are Google's versions (EB Garamond v33 from 2026-11-16, DM Sans v17 and JetBrains Mono v24 from 2026-10-23). Later improvements to the fonts don't reach the site until the files are rebuilt.

## How to change it

The source files are in `tools/fonts/src/`. They were downloaded from these Google Fonts requests (Latin subset):

```
https://fonts.googleapis.com/css2?family=EB+Garamond:wght@500
https://fonts.googleapis.com/css2?family=DM+Sans:wght@400..700
https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400..600
```

- **Add characters:** edit `UNICODES` in `tools/fonts/subset-fonts.sh` and run it. If Google's Latin file doesn't have the character, download the matching subset as well.
- **Add heading weights:** download EB Garamond with `wght@400..800` instead (37 KB trimmed, over budget unless something else shrinks), update the script and the `font-weight` range in `fonts.css`.
- **Add italics:** download the italic files, add them to the script and add `@font-face` rules with `font-style: italic` in `carolinelindstrom/assets/css/base/fonts.css`.
- **After any change:** run `tools/fonts/.venv/bin/python3 tools/fonts/fallback-metrics.py` and paste the new values into `fonts.css`, so the fallbacks stay matched.

## Licenses

All three fonts are licensed under the SIL Open Font License 1.1, which allows self-hosting and subsetting. The license texts are in `carolinelindstrom/assets/fonts/OFL-*.txt`. EB Garamond's license is from the Google Fonts repository (`ofl/ebgaramond/OFL.txt`).
