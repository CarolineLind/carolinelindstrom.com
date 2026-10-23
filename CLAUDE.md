# CLAUDE.md

Guidance for AI agents working in this repository.

## What this is

A classic WordPress theme for [carolinelindstrom.com](https://carolinelindstrom.com/), Caroline Lindström's personal site. It is both a **portfolio** and a **playground** for frontend ideas. Caroline is the owner, the developer and the content editor, and she is happy to answer questions: **ask her rather than guess** when something affects content, design or scope.

Read `carolinelindstrom/README.md` for the project overview, quality goals, file structure and local setup.

## Repository layout

- `carolinelindstrom/` – **the new theme. All new work happens here.**
- `CarolineTheme/` – the old theme that runs the live site today. Don't change it unless asked.
- `CarolineTheme.zip`, `CarolineTheme.rar` – old archives. Leave them alone.

## Working agreements

- **Check in before starting each component.** Describe what you will build, then wait for a go-ahead.
- **Work on the `new-theme` branch.** One commit per component. Commit messages in Swedish, short, like the existing history ("Nytt tema: grund (tokens, bas-CSS, header/footer, mallar)").
- **Never push** unless Caroline asks in that conversation.
- Caroline often runs several sessions at once. Uncommitted changes you didn't make may be her own work in progress: don't revert, stash or commit them.
- Caroline creates content in WordPress while you code. Content lives in the database, not in the repo.

## Scope

The site is **information only**: pages about Caroline and her work, links and hover effects.

- **Pages:** Välkommen (start), Om mig, Tillgänglighet, Prestanda, Frontend lead, Integritetspolicy.
- **Not part of the site:** comments, pingbacks, forms, search, user accounts, the old `projekt` post type and the blog categories. Turn these off rather than style them.
- **Language:** Swedish only (`lang="sv"`). No multilingual setup.

## Quality bar

Consider all of these in every change. **WCAG 2.2 AA is the acceptance criterion**; the rest are targets we aim high for.

### Accessibility (WCAG 2.2 AA, required)
- Real elements: `<a href>` for navigation, `<button>` for actions. Never click handlers on `div` or `span`.
- Every hover effect also works on `:focus-visible`. Visible focus everywhere, never hidden behind sticky elements.
- Touch targets at least 44 × 44 px (use `--tap-min`).
- Text contrast at least 4.5:1, large text and UI parts at least 3:1, **checked in both dark and light mode**.
- All motion stops for `prefers-reduced-motion: reduce` and for `[data-motion="paused"]`. Anything that moves for more than 5 seconds must be pausable.
- Decorative SVG gets `aria-hidden="true"`. Meaningful images get real alt text.
- One `<h1>` per page and a logical heading order. Landmarks: header, nav, main, footer. The skip link must work.
- Must work with keyboard only, VoiceOver, 200 % text zoom and 400 % page zoom (320 px wide).

### Performance
- Targets: LCP < 2.5 s, CLS < 0.1, INP < 200 ms on mobile. Lighthouse 95+ in all four categories.
- Budgets per page: under 30 KB JavaScript, under 100 KB fonts.
- No jQuery and no frameworks. Vanilla JS, loaded with `defer`, only on the pages that need it.
- Images: correct `width`/`height`, `srcset`. Never lazy-load the LCP image; give it `fetchpriority="high"`.
- Don't load WordPress assets the site doesn't use (emoji, embeds, unused block CSS).
- Animate only `transform` and `opacity` where possible.

### SEO (built into the theme, no plugin)
- The theme outputs titles, meta description, canonical URL, Open Graph tags and `Person` JSON-LD.
- Semantic HTML and a clean heading structure. Content is readable without JavaScript.

### Security
- Escape all output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`) and sanitize all input.
- Prefix all PHP functions with `cl_`. Text domain: `carolinelindstrom`.
- Don't enable anything unused (comments, XML-RPC, REST endpoints the site doesn't need).
- No third-party scripts without consent.

### Responsiveness and browsers
- Mobile first. Works from 320 px. Breakpoints: 600, 720 and 1000 px.
- Supports current Chrome, Firefox, Safari and Edge, and iOS Safari from the last two years.
- Newer CSS (anchor positioning, scroll-driven animations, `corner-shape`, `@starting-style`) only behind `@supports`, with a working fallback.

## Code conventions

- **No build step.** Plain PHP, CSS and vanilla JS. Linting tools are fine, compiling is not.
- **CSS variables:** every design value comes from `assets/css/base/tokens.css`. Add new tokens there instead of hard-coding values. Light mode overrides go under `[data-theme="light"]`.
- **One CSS file per component** in `assets/css/components/`, registered in the ordered list in `inc/enqueue.php`.
- **Class names:** block-element style (`.site-footer__inner`, `.logo__bracket--l`), plus the shared helpers in `typography.css` and `utilities.css`.
- **Comments in English.** User-facing strings in Swedish, wrapped in `__()` / `esc_html_e()`.
- **File headers:** match the banner style used in the existing files (`/*--- SECTION ---*/`).
- **Templates:** keep them thin. Markup that repeats goes in `template-parts/`.

## The design

The design is a Claude Design canvas: https://claude.ai/code/artifact/336f61ad-6f3b-42f1-8d72-1d7744ad5516

- Read it with the Artifact tool's `read` action. List its files first; each artboard is `project/<Name>.dc.html`.
- `Main.dc.html` is the start page (dark mode by default; `MainLight`, `Mobile` and `MobileMenu` reuse it). The other pages are `OmMig`, `Tillganglighet`, `Prestanda` and `FrontendLead`. The `*Lab`, `Banner*` and `Read*` boards are experiments, not decisions.
- The design is a guide, not a contract. When the design conflicts with the quality bar, the quality bar wins: tell Caroline about the conflict.
- The design uses `.cl` / `.cl.light` classes. In the theme these become `:root` and `[data-theme="light"]`.

## Local environment

- WordPress runs in Local at `http://carolinelindstromcom.local/`. The theme folder is symlinked into `~/Local Sites/carolinelindstromcom/app/public/wp-content/themes/`.
- There is no `php` on the PATH. Use Local's PHP for syntax checks:
  `"/Users/caroline.lindstrom/Library/Application Support/Local/lightning-services/php-8.2.29+0/bin/darwin-arm64/bin/php" -l <file>`
- Hosting is SiteGround (nginx with SiteGround's own caching).

## Open decisions

These are still being discussed. Don't build them until Caroline has decided:

- **Content model:** how content is split between the WordPress editor and templates (block patterns, locked groups, etc.).
- **Preferences banner:** consent for cookies and third-party embeds, motion and light/dark mode, possibly combined into one.
- **Analytics:** the live site uses Jetpack Stats. Keep it, replace it or remove it?
- **Jetpack:** it loads carousel, likes and stats assets. Decide what to keep.
- **Linting setup:** PHPCS (WordPress Coding Standards), Stylelint, axe or pa11y, with matching VS Code settings.
- **Self-hosted fonts:** decided, not yet done. Fonts are still loaded from Google Fonts.
