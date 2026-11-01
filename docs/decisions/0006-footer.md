# 0006 – Footer: contact, revealed bottom bar and animations on scroll

- **Date:** 2026-10-30
- **Status:** Decided, built
- **Decided by:** Caroline Lindström

## In short

The footer has two parts. On top, **"Här hittar du mig"**: email, LinkedIn, Instagram, GitHub and the latest favourite on Spotify, over a pattern of dots and twinkling stars. Below it, a **bottom bar** with the logo, ©, Integritetspolicy, Inställningar and Till toppen. The contact block lies over the bar like a curtain and uncovers it as you scroll to the end of the page (inspired by mobbin.com). The footer's hover effects also play on their own on touch screens, where hover never happens.

## Decision

| Design | Theme | Why |
| --- | --- | --- |
| Contact section (`#kontakt`) at the end of the start page | In the footer, on every page | Caroline's call: the way to reach her belongs everywhere |
| Email `contact@…` written into the page | The email address and the Spotify link are set in **Appearance → Customize → "Kontakt i sidfoten"** | The repo is public: a private address must not end up in its history. The favourite can also change without code |
| Spotify player (placeholder) | The real player, held back until the visitor allows content from other sites; until then a card with a link to Spotify | Decision 0002. The player loads lazily |
| Dots and twinkling stars, looping forever | Only twinkle with motion on; with motion off they stand still at full strength. The twinkle only animates `opacity` | A loop longer than 5 seconds must be pausable (WCAG 2.2.2): "Pausa rörelse" in the header stops it |
| Text straight on top of the stars | A ring of background colour around the letters. On the email it's one `drop-shadow` around the whole address, not a `text-shadow` per letter | A yellow star under a white letter is only about 1.1:1. A ring per letter painted over the letter before it and looked broken |
| Social links as pills with name and arrow | Same on wide screens. **From 720 px down:** 48 px round brand icons on one line, with the names kept for screen readers | Caroline's call: a clear hierarchy on phones (big heading, then the email, then small links). Icons from Bootstrap Icons (MIT, listed in `LICENSE`) |
| Fill sweeps in on hover; the text changes colour at once | A dark copy of the label is uncovered at the same pace as the fill, so icon and text turn dark exactly where the fill passes | When the colour flipped at once, the icon went dark before the fill reached it: dark on dark |
| Footer links' rings `#35324A`, about 1.5:1 | `--color-ring-quiet`, 3:1 or more against the footer and the bar, 1 px stroke | The rings are decoration (the text names the link), but they mark a 44 px target and should read as a shape (WCAG 1.4.11 as a target, not a requirement here) |
| "Till toppen" with a ring that fills as you scroll | A plain ring. The link goes to `#top` (`<body>`), not `#main` | Caroline's call on the ring. `#main` stopped short of the top because of the sticky header's `scroll-padding`; from `<body>` the next Tab also starts at the skip link |
| Footer in the normal flow | **The bottom bar is revealed:** it's `position: sticky; bottom: 0` behind the contact block, which has a solid background. The bar is a step deeper in colour so the edge shows. No rounded corners and no shadow | Caroline's call, after mobbin.com. Only the bar, not the whole footer: the footer is taller than a phone screen, and a sticky element taller than the screen can't be fully revealed |
| (Not in the design) | The reveal is **off** in windows under 600 px tall, on pages where the footer is already on the first screen (the 404), before the footer is on screen, and without JavaScript | Short windows and high zoom: the same rule as the sticky header. On a short page the bar would start out covered. Off before the footer is on screen, so the accessibility tests don't count the bar's text as covered |
| (Not in the design) | A link in the bar that gets keyboard focus while covered makes the page scroll to the end | WCAG 2.4.11 Focus Not Obscured: the browser counts the link as on screen and wouldn't scroll |
| Small logo next to © | Writes itself in (the header's write-in, decision 0005) every time it's uncovered, on all screen sizes | Caroline's call. The browser can't tell that the bar is covered, so `footer.js` checks the logo against the contact block's edge |
| Hover effects only | **On touch screens** (`hover: none`) the email waves and the pills fill one after another when they're a third of the way up the screen. The pills stay filled while in view; everything resets when scrolled away and plays again next time | Caroline's call: on phones the effects would otherwise never be seen. Pills: 0.55 s each with a 0.5 s pause (`--dur-beat`) so the eye can follow |
| Integritetspolicy link | From WordPress's privacy page setting (Settings → Privacy), otherwise the published page with the slug `integritetspolicy`, otherwise left out. The "Sidfotsmeny" menu location is gone | One place to set it, and the link can't point to a missing page |

## Consequences

1. **The live site needs the two Customizer fields filled in** (email and Spotify link). Empty fields hide that part.
2. **The social links are in the code** (`template-parts/footer/contact.php`). Adding a profile means adding a line and an icon (with its license).
3. **The bar must stay short** (it's about 340 px on a phone) for the reveal to work on phones.
4. **If the footer's layout changes, check `footer.js`:** the logo's write-in and the focus scroll depend on the contact block lying directly above the bar.
5. **axe can't measure text over the dot pattern,** so the patterns are painted on `::before` (see Gotchas in `CLAUDE.md`), and the text against the dots was checked by hand: 7.8:1 or more, the stars are covered by the ring of background colour.

## Where it lives

| What | File |
| --- | --- |
| Footer markup | `carolinelindstrom/footer.php`, `template-parts/footer/contact.php`, `template-parts/footer/footer-nav.php` |
| Customizer fields, Spotify player, brand icons | `carolinelindstrom/inc/contact.php` |
| Logo mark, privacy link | `carolinelindstrom/inc/template-tags.php` (`clind_logo_mark()`, `clind_privacy_url()`) |
| Layout and reveal | `carolinelindstrom/assets/css/layout/footer.css` |
| Contact block, email | `carolinelindstrom/assets/css/components/contact.css` |
| Dots and stars | `carolinelindstrom/assets/css/components/sky.css` |
| Social links | `carolinelindstrom/assets/css/components/contact-socials.css` |
| Footer links | `carolinelindstrom/assets/css/components/footer-nav.css` |
| Reveal, write-in, touch animations | `carolinelindstrom/assets/js/footer.js` |
| Brand icons and their license | `carolinelindstrom/assets/icons/social/` |

## Sources

- Design: `Main.dc.html` in the design canvas (the `#kontakt` section and `.foot`)
- Reveal: inspired by the footer on [mobbin.com](https://mobbin.com/), looked at on 2026-10-25 (`position: sticky; bottom: 0` behind the page)
- [Understanding SC 2.2.2 Pause, Stop, Hide](https://www.w3.org/WAI/WCAG22/Understanding/pause-stop-hide.html)
- [Understanding SC 2.4.11 Focus Not Obscured (Minimum)](https://www.w3.org/WAI/WCAG22/Understanding/focus-not-obscured-minimum.html)
- [Understanding SC 1.4.11 Non-text Contrast](https://www.w3.org/WAI/WCAG22/Understanding/non-text-contrast.html)
- [Understanding SC 1.4.3 Contrast (Minimum)](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html)
- [Bootstrap Icons](https://icons.getbootstrap.com/) 1.11.3, MIT License
