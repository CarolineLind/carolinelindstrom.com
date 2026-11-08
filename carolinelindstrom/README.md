# carolinelindstrom – WordPress theme

Classic WordPress theme for [carolinelindstrom.com](https://carolinelindstrom.com/), the personal site of Caroline Lindström, frontend developer.

The site is two things at once:

- **A portfolio**: pages about me and my work.
- **A playground**: a place to try out ideas in HTML, CSS and JavaScript without limits.

The site is information only. Visitors read and follow links; there are no comments, forms, search or user accounts. The content is in Swedish.

## Pages

| Page | Slug |
| --- | --- |
| Välkommen (start page) | `valkommen` |
| Om mig | `om-mig` |
| Tillgänglighet | `tillganglighet` |
| Prestanda | `prestanda` |
| Frontend lead | `frontend-lead` |
| Integritetspolicy | `integritetspolicy` |

## Quality goals

Every change is measured against these. Accessibility is a hard requirement; the rest are targets we aim high for and adjust only if they turn out to be impossible.

| Area | Goal |
| --- | --- |
| Accessibility | **WCAG 2.2 level AA** (acceptance criterion). Keyboard, screen reader, zoom to 400 %, reduced motion. |
| Performance | Core Web Vitals "good" on mobile: LCP < 2.5 s, CLS < 0.1, INP < 200 ms. Lighthouse 95+ in all four categories. Under 30 KB JavaScript and under 100 KB fonts per page. |
| SEO | Built into the theme, no plugin: titles, meta description, canonical, Open Graph, `Person` structured data, sitemap. |
| Security | All output escaped, nothing unused enabled (comments, XML-RPC, search), self-hosted assets, security headers on the server. |
| Privacy | No third-party requests before consent. Fonts are self-hosted. |
| Responsiveness | Works from 320 px wide and up. Breakpoints at 600, 720 and 1000 px. |
| Browsers | Current Chrome, Firefox, Safari and Edge, and iOS Safari from the last two years. Newer CSS features are progressive enhancements with working fallbacks. |

## Requirements

- WordPress 6.4 or later
- PHP 8.2 or later (local and live run PHP 8.2.35)
- No build step: plain PHP, CSS and vanilla JavaScript

## Local development

The site runs locally in [Local](https://localwp.com/) at `http://carolinelindstromcom.local/`. The theme folder in this repository is symlinked into the Local site, so edits show up straight away:

```bash
ln -s /Users/caroline.lindstrom/GIT-privat/carolinelindstrom.com/carolinelindstrom "/Users/caroline.lindstrom/Local Sites/carolinelindstromcom/app/public/wp-content/themes/carolinelindstrom"
```

Then activate **Caroline Lindström** under Appearance → Themes and assign a menu to **Huvudmeny** under Appearance → Menus.

The theme only exists on the `new-theme` branch until it's merged. On `main` the folder disappears and WordPress falls back to a default theme.

## File structure

```
carolinelindstrom/
├── style.css                 Theme header only
├── functions.php             Loads the files in inc/
├── inc/
│   ├── setup.php             Theme supports, menus, editor styles
│   ├── enqueue.php           CSS/JS loading, fonts, light/dark script in <head>
│   ├── icons.php             Favicon, home screen icon and theme-color
│   ├── contact.php           Footer contact: Customizer fields, Spotify player, brand icons
│   ├── start-page.php        Start page: Customizer fields, hero content, banner words, Läs vidare cards
│   ├── doodles.php           Hand-drawn SVG doodles for the banner, the cards and the hero
│   ├── code-editor.php       The code editor's lines and file tree
│   └── template-tags.php     Small template helpers (logo, menu classes)
├── header.php · footer.php
├── front-page.php · page.php · single.php · index.php · 404.php
├── template-parts/
│   ├── header/               Menu, menu button and toggles
│   ├── footer/               Contact block and footer links
│   ├── front/                Start page sections (hero, keyword banner, Läs vidare, code editor)
│   ├── sky.php               Dots and twinkling stars behind a section
│   └── content/              Page content and post lists
└── assets/
    ├── css/
    │   ├── base/             tokens, reset, typography, utilities
    │   ├── layout/           header, footer, content
    │   └── components/       One file per component
    ├── js/                   preferences.js (dialog), header.js (toggles, mobile menu), footer.js (reveal, animations on scroll), start-hero.js, read-more.js and code-editor.js (start page only)
    ├── fonts/                Self-hosted woff2 files and their licenses
    └── icons/                favicon.svg (light/dark) and its PNG versions; social/ brand icons (MIT)
```

Outside the theme folder, at the repo root:

```
docs/decisions/               Why things are the way they are, one file per decision
tools/fonts/                  Source fonts and the scripts that trim them
tools/a11y-summary.mjs        Sorts pa11y results into theme and content errors
tools/icons/                  Renders the favicon PNGs from favicon.svg
.claude/skills/component/     The routine for building a component (/component in Claude Code)
```

## CSS

- **All design values are CSS variables** in `assets/css/base/tokens.css`: colors, fonts, sizes, spacing, corners and motion. Use the variables; don't hard-code values in other files.
- **Dark mode is the default.** Light mode overrides the variables under `[data-theme="light"]`. A small script in `<head>` sets `data-theme` before the page draws: the visitor's saved choice first, otherwise their system setting.
- **The block editor** uses the same fonts, sizes and spacing as the site, always in light mode. It loads `fonts.css`, `tokens.css` and `typography.css` (see `inc/setup.php`).
- **Motion only runs when allowed:** `data-motion="on"` on `<html>`, set from the visitor's choice in the preferences dialog. Before a choice, and without JavaScript, nothing moves.

## Preferences dialog

On the first visit a dialog asks for appearance (light, dark or follow the device), motion (on, off or follow the device) and content from other sites (show or don't show). Content from other sites, like the Spotify player, is held back on the server until allowed. The "Inställningar" button in the footer reopens the dialog. Why and how: [docs/decisions/0002-preferences-dialog.md](../docs/decisions/0002-preferences-dialog.md).
- **Files load in a fixed order** from the list in `inc/enqueue.php`: tokens → base → layout → components. A new component gets its own file in `assets/css/components/` and a line in that list.

## Footer

"Här hittar du mig" (email, social links and the latest favourite on Spotify) sits on top of a bottom bar with the logo, ©, Integritetspolicy, Inställningar and Till toppen. As you reach the end of a page, the contact block slides up and uncovers the bar, which stands still underneath. On touch screens the hover effects play on their own as the footer scrolls into view.

- **Change the email or the Spotify favourite** under **Appearance → Customize → "Kontakt i sidfoten"**. Paste the Spotify link from Share → Copy link (an album, playlist or track). An empty field hides that part.
- **The Integritetspolicy link** comes from Settings → Privacy, or the page with the slug `integritetspolicy`.
- **The social links** are in `template-parts/footer/contact.php`, with their icons in `assets/icons/social/`.

Why it's built this way, and where it differs from the design: [docs/decisions/0006-footer.md](../docs/decisions/0006-footer.md).

## Start page hero

A sheet of lined paper with the page's h1, a short text and a link, next to the portrait with a lilac block and a yellow dot behind it, over twinkling stars and twelve hand-drawn doodles. With motion on and a mouse, the paper tilts toward the pointer; with the link focused by keyboard it leans a little. As the page scrolls, the doodles rise and turn at different speeds and the portrait, its plate and its dot drift apart.

- **Change the heading, text or link** under **Appearance → Customize → "Startsidan"**. The link goes to "Om mig" until another page is chosen.
- **Change the portrait** by setting the start page's **featured image** in the editor. It's cropped to 4:5.
- **How the tilt works:** `assets/js/start-hero.js` only writes where the pointer is, as `--nx` and `--ny` (−1 to 1) on `.start-hero`. `start-hero.css` turns them into the tilt, the shadow (its own layer, moved with `transform`) and the glare. Without the script, or with motion off, the paper lies flat.

- **How the scroll works:** `start-hero-scroll.css` animates three numbers on `.start-hero` with scroll-driven animations: `--p` (0 to 1 over the first screen of scrolling), `--pp` (the portrait passing through the window) and `--pb` (the hero's bottom edge leaving). The doodles (`hero-shapes.css`) and the portrait multiply their moves by these and by `--hero-shapes-amount`. Browsers without scroll-driven animations get the same numbers from `start-hero.js`. With motion off nothing reads them.
- **The doodles:** one row each in `template-parts/front/hero-shapes.php`: start point and size in % of the hero (with `-m` and `-t` versions for phones and tablets), how far it rises, drifts and turns. The SVG paths are in `inc/doodles.php`.

Why it's built in the theme and not in the editor, and where it differs from the design: [docs/decisions/0007-start-hero.md](../docs/decisions/0007-start-hero.md). The scrolling doodles: [0010](../docs/decisions/0010-hero-shapes.md).

## Start page banner and "Läs vidare"

Under the hero, a band of keywords glides past with a hand-drawn doodle after each word; it pauses while the pointer is over it. Then "Läs vidare": a carousel of 3D notebooks, one per page. On hover or keyboard focus a notebook opens and a doodle is drawn on its first page; on touch screens the current one opens by itself.

- **Change the keywords** under **Appearance → Customize → "Startsidan"**, one per line.
- **Change a notebook's text** by writing the page's **excerpt** (Utdrag) in the editor, and the **words on its cover** in the box **"Ord på bokomslaget"** in the editor's sidebar (up to three, with commas).
- **Choose and order the notebooks** with a menu at the location **"Läs vidare på startsidan"** (Appearance → Menus). The notebook's title is the menu label. Without that menu: Om mig, Tillgänglighet, Prestanda and Frontend lead.
- **How it's built:** the carousel is a row that scrolls and snaps by itself (`read-more.css`); `read-more.js` only keeps the dots, counter and arrows in step and opens the book on touch screens. The notebook is spans in 3D (`notebook.css`). The doodles are in `inc/doodles.php`, shared with the banner.
- **On desktop the carousel is part of the page's scroll:** with motion on, and when it fits in the window, the section sticks under the header and scrolling down moves the notebooks sideways, with a soft start and stop at each end. The movement is CSS scroll-driven animations (`read-more-pin.css`); `read-more.js` measures and writes where each stretch starts and ends, and moves things itself in browsers without scroll-driven animations. Tune the feel with `CATCH` and `RAMP` in `read-more.js`. Why it's built this way: [0011](../docs/decisions/0011-read-more-pinned-scroll.md).
- **A click on a faded notebook** moves the carousel to it; the pointer shows which way.

Where it differs from the design: [docs/decisions/0008-banner-and-read-more.md](../docs/decisions/0008-banner-and-read-more.md).

## Start page code editor

Before the footer, an editor window introduces Caroline as a JavaScript object (`caroline.js`), with a file tree and a terminal. Pressing `'light'` in the code switches to light mode, and the terminal answers. With motion on, the code types itself out when it scrolls into view.

- **Change the code** in `clind_code_editor_lines()` in `inc/code-editor.php`: one string per line, `{k:…}` keyword, `{s:…}` string, `{p:…}` property, `{c:…}` comment, `{light}` the button. Spaces at the start are the indentation.
- **How it's built:** `template-parts/front/code-editor.php` prints the code as real text in a `<pre>`. `code-editor.js` lays an `aria-hidden` copy on top and types it, closing quotes and brackets ahead of the caret like a real editor, so screen readers and visitors without JavaScript always get the whole code. The button and the plain `'light'` are both in the page; CSS shows one of them from `<html>`'s `data-theme`.

Where it differs from the design: [docs/decisions/0009-code-editor.md](../docs/decisions/0009-code-editor.md).

## Fonts

Bricolage Grotesque (headings), DM Sans (body text) and JetBrains Mono (labels and code) are self-hosted in `assets/fonts/` and trimmed to 99 KB in total. The trimming means they differ slightly from the design in a few places (fixed optical size, no italics, a limited set of characters). The reasons, the full list of differences and how to rebuild the files are in [docs/decisions/0001-self-hosted-fonts.md](../docs/decisions/0001-self-hosted-fonts.md). The build tools are in `tools/fonts/` at the repo root.

## Design

The design lives in a Claude Design canvas: "carolinelindstrom.com – dark mode". It has dark, light and mobile versions of every page, plus experiments for banners, buttons, icons and effects. The design isn't final; we build the basics first and add components one at a time.

## Quality checks

The tools live at the repo root (`package.json`, `composer.json`). They check code; nothing is compiled.

**First time on a new computer:**

```bash
brew install php composer
composer install
npm install
```

**Commands** (run from the repo root):

| Command | What it does |
| --- | --- |
| `npm run lint` | PHP and CSS checks together. Run before every commit. |
| `npm run lint:php` | PHP_CodeSniffer with WordPress Coding Standards (`phpcs.xml.dist`) |
| `npm run lint:css` | Stylelint (`.stylelintrc.json`) |
| `npm run fix:php` / `npm run fix:css` | Fix what can be fixed automatically |
| `npm run test:a11y:summary` | pa11y in light and dark mode, with every error sorted into **theme** (must be fixed) or **content** (written in WordPress). Fails only on theme errors. Local must be running. |
| `npm run test:a11y` | The same tests with pa11y's full output, for digging into a specific error |

**VS Code** shows the same warnings as you type. Open the repo root folder and accept the recommended extensions (`.vscode/extensions.json`). The settings in `.vscode/settings.json` point them at the same rule files.

**Rules worth knowing:**

- PHP functions, hooks and globals are prefixed `clind_`. WordPress Coding Standards require prefixes of at least three characters.
- PHP must stay compatible with PHP 8.2 and WordPress 6.4.
- Stylelint uses its standard rules, all switched on. The only addition is that class names must follow `block__element--modifier`.
- The old `CarolineTheme/` is excluded from every check (`phpcs.xml.dist`, `.stylelintignore`), also in VS Code. It is being replaced, not maintained.

**Limits of the accessibility test:**

- Automated tests find roughly a third of WCAG problems. Keyboard, VoiceOver, zoom and reduced motion still need testing by hand (checklist section 13).
- The test pages are listed in `.pa11yci.json` (light) and `.pa11yci.dark.json` (dark). The first entry tests the preferences dialog on its own. For every page, pa11y first clicks "Spara och gå vidare", so the page is tested as a returning visitor sees it.
- The configs map `carolinelindstromcom.local` to `127.0.0.1`, because the test browser otherwise tries IPv6 first and Local doesn't answer there.

## Branches and commits

- `main` matches the live site (the old `CarolineTheme`).
- The new theme is built on `new-theme`.
- One commit per component, with commit messages in Swedish.

## License

All rights reserved. The code is public to show my work, not for reuse. See [LICENSE](../LICENSE) for the details and the third-party licenses.
