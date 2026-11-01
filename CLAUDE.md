# CLAUDE.md

Guidance for AI agents working in this repository.

## What this is

A classic WordPress theme for [carolinelindstrom.com](https://carolinelindstrom.com/), Caroline Lindström's personal site. It is both a **portfolio** and a **playground** for frontend ideas. Caroline is the owner, the developer and the content editor, and she is happy to answer questions: **ask her rather than guess** when something affects content, design or scope.

Read `carolinelindstrom/README.md` for the project overview, quality goals, file structure and local setup.

## Repository layout

- `carolinelindstrom/` – **the new theme. All new work happens here.**
- `docs/decisions/` – decision records: why something is built the way it is. **Write one whenever a choice trades away something from the design or the quality bar**, and link it from the code it affects.
- `tools/` – build helpers that are not deployed (e.g. `tools/fonts/` trims the fonts). The theme itself has no build step.
- `CarolineTheme/` – the old theme that runs the live site today. Don't change it unless asked.
- `CarolineTheme.zip`, `CarolineTheme.rar` – old archives. Leave them alone.

## Working agreements

- **Check in before starting each component.** Describe what you will build, then wait for a go-ahead.
- **Work on the `new-theme` branch. No other branches** (Caroline's choice).
- **Commit as often as you like while building a component.** Small commits make each round easy to undo. Commit messages in Swedish, short, like the existing history.
- **When Caroline says the component is done, merge its commits into one** before anything is pushed:
  1. Set a local tag on the last commit as a backup of the detailed history: `git tag <component>-detaljerad-historik`. Tags aren't pushed by `git push`.
  2. `git reset --soft <last commit before the component>` and commit everything as one commit: a Swedish title, a short list of what the component does, and a line naming the tag.
  3. Check that nothing changed: `git rev-parse HEAD^{tree}` must equal the tag's tree.
  Only merge commits that haven't been pushed. Never rewrite pushed history.
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
- **Motion follows Material Design 3 and Apple's guidelines,** see `docs/decisions/0004-motion.md`: entering slows down (`--ease-out`), leaving speeds up and is shorter (`--ease-in`), state changes use one curve both ways. Curves and durations come from the motion tokens.
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
- Prefix all PHP functions, hooks and globals with `clind_` (WordPress Coding Standards require at least 3 characters). Text domain: `carolinelindstrom`.
- Don't enable anything unused (comments, XML-RPC, REST endpoints the site doesn't need).
- No third-party scripts without consent.

### Responsiveness and browsers
- Mobile first. Works from 320 px. Breakpoints: 600, 720 and 1000 px.
- Supports current Chrome, Firefox, Safari and Edge, and iOS Safari from the last two years.
- Newer CSS (anchor positioning, scroll-driven animations, `corner-shape`, `@starting-style`) only behind `@supports`, with a working fallback.

## Checks before committing

- **`npm run lint` must pass before every commit** (PHPCS with WordPress Coding Standards, plus Stylelint). Fix the code rather than switching rules off. If a rule really has to go, explain why in the config or a `phpcs:ignore` / `stylelint-disable` comment.
- **After any change to markup, colors or CSS that affects the page, run `npm run test:a11y:summary`** (Local must be running). It tests light and dark mode and sorts errors into theme and content. It fails only on theme errors, which must be fixed.
- Content errors come from Caroline's draft content in WordPress. Report them; don't try to fix content from code.
- **Building a component? Use the `/component` skill** (`.claude/skills/component/SKILL.md`): it's the whole routine, from check-in to commit.
- Running pa11y from a sandboxed shell works; the `--host-resolver-rules` flag in the configs is what makes the `.local` domain reachable.
- **Look facts up; don't write them from memory.** Guidelines, WCAG criteria, browser support and design-system values (Material, Apple) are checked with a web search or the source, and the source is linked in the decision record. If you can't verify something, say so in the text.
- **Content changes while you work.** Caroline edits pages and the menu in WordPress at the same time. If something "disappears" between two test runs, check the menu and the page in WordPress before suspecting the code.

## Code conventions

- **No build step.** Plain PHP, CSS and vanilla JS. Linting tools are fine, compiling is not.
- **CSS variables:** every design value comes from `assets/css/base/tokens.css`. Add new tokens there instead of hard-coding values. Light mode overrides go under `[data-theme="light"]`.
- **One CSS file per component** in `assets/css/components/`, registered in the ordered list in `inc/enqueue.php`.
- **Class names:** block-element style (`.site-footer__inner`, `.logo__bracket--l`), plus the shared helpers in `typography.css` and `utilities.css`.
- **Comments in English.** User-facing strings in Swedish, wrapped in `__()` / `esc_html_e()`.
- **Comments:** a file starts with a three-line banner in one comment (`/* ---…` / ` * --- NAME ---` / ` * ---… */`); sections inside a file use one line (`/* --- SECTION --- */`). There's always a space inside `/*` and `*/`, and a blank line before every comment. In `tokens.css`, each group of variables starts with a short comment. Stylelint enforces this.
- **Templates:** keep them thin. Markup that repeats goes in `template-parts/`.

## The design

The design is a Claude Design canvas: https://claude.ai/code/artifact/336f61ad-6f3b-42f1-8d72-1d7744ad5516

- Read it with the Artifact tool's `read` action. List its files first; each artboard is `project/<Name>.dc.html`.
- `Main.dc.html` is the start page (dark mode by default; `MainLight`, `Mobile` and `MobileMenu` reuse it). The other pages are `OmMig`, `Tillganglighet`, `Prestanda` and `FrontendLead`. The `*Lab`, `Banner*` and `Read*` boards are experiments, not decisions.
- The design is a guide, not a contract. When the design conflicts with the quality bar, the quality bar wins: tell Caroline about the conflict.
- The design uses `.cl` / `.cl.light` classes. In the theme these become `:root` and `[data-theme="light"]`.

## Local environment

- WordPress runs in Local at `http://carolinelindstromcom.local/`. The theme folder is symlinked into `~/Local Sites/carolinelindstromcom/app/public/wp-content/themes/`.
- There is no `php` on the PATH. Use Local's PHP for syntax checks (the site runs 8.2.35; if the folder has changed, list `lightning-services/` and use the newest `php-*`):
  `"/Users/caroline.lindstrom/Library/Application Support/Local/lightning-services/php-8.2.35+0/bin/darwin-arm64/bin/php" -l <file>`
- Hosting is SiteGround (nginx with SiteGround's own caching). SiteGround updates PHP automatically; the live site ran PHP 8.2.35 as of 2026-10-23.

## Public repository

The repo is public on GitHub (`CarolineLind/carolinelindstrom.com`) and part of Caroline's portfolio: recruiters and other developers read it. So:

- **Before every commit, read the diff for things that mustn't be public:** passwords, keys, tokens, private email addresses or phone numbers, database exports, logs, local paths that reveal more than needed, and other people's content. Anything that slipped into a pushed commit stays in history: tell Caroline straight away instead of just deleting it.
- **Commits use Caroline's GitHub noreply address** (`33750059+CarolineLind@users.noreply.github.com`, set in this repo's git config). Never commit with another address. The history was rewritten on 2026-10-24 to remove her private address.
- **Never commit Claude Code transcripts, logs or `.claude/settings.local.json`.** Only shared things like `.claude/skills/` belong in the repo.
- **Third-party material** (images, icons, fonts, code snippets) needs a license that allows publishing it here. Check it before adding, keep the license file next to it, and list it in `LICENSE`.
- **The code is "All rights reserved"** (`LICENSE`). Don't add files under another license without saying so in `LICENSE`.
- **Write for a reader who doesn't know the project:** clear names, short comments that explain why, decision records with sources, and honest docs. Never claim tests that weren't run.
- **The README says openly how the site is built with Claude Code.** Keep that true: Caroline decides, reviews and tests; agents explain what they built (see the `/component` skill's hand-over).

## Gotchas

Traps that have cost time before. Add new ones when you hit them.

- **Headless Chrome can't reach `carolinelindstromcom.local` on its own.** It tries IPv6 (`::1`) first, which Local doesn't answer, and hangs. Pass `--host-resolver-rules=MAP carolinelindstromcom.local 127.0.0.1` (already in the pa11y configs). `curl` works without it.
- **axe-core doesn't know about the browser's top layer.** Text in a modal `<dialog>` is reported as covered by the page behind it ("partially obscured", contrast 0). Keep the `z-index` on `.prefs`, and test dialogs on their own (`rootElement`), as `.pa11yci.json` does.
- **pa11y-ci keys results by URL.** Two entries with the same URL overwrite each other, which is why the dialog test has `?a11y-test=preferences-dialog`.
- **pa11y errors are not all the theme's.** Draft content in WordPress has many. Use `npm run test:a11y:summary`, which sorts errors into theme and content and only fails on theme errors.
- **`stylelint --fix` removes `-webkit-text-size-adjust`,** which iOS Safari still needs. It's kept with a `stylelint-disable-next-line` comment in `reset.css`. Check `git diff` after any `--fix`.
- **The VS Code PHPCS extension (phpsab) ignores `<exclude-pattern>`** in `phpcs.xml.dist` for open files. Exclusions also go in `phpsab.excludeGlobs` in `.vscode/settings.json`.
- **The block editor prefixes theme CSS with `.editor-styles-wrapper`,** except selectors that already name it. That's how the editor is kept in light mode (`tokens.css`).
- **`composer init` copies the git email into `composer.json`.** The repo is public: remove it.
- **`wp_nav_menu()` takes `container_aria_label`,** not an attribute array. Unknown arguments are ignored silently.
- **WordPress Coding Standards require prefixes of 3+ characters,** which is why the prefix is `clind_` and not `cl_`. CSS handles (`cl-…`) and `localStorage` keys aren't checked.
- **No `php` in Local's shell PATH.** Homebrew PHP (8.5) runs the linters; Local's own PHP (8.2.35, path under Local environment) matches the site.
- **The Bash sandbox can block headless Chrome's network.** If pa11y or Puppeteer time out on every page, check the host mapping above first; it was the real cause last time.
- **Moving or renaming the repo folder** (it was `GIT-privat/laragon` until 2026-10-23) breaks three things: the two theme symlinks in Local (recreate them with `ln -sfn`), `tools/fonts/.venv` (Python environments can't move: delete it and `subset-fonts.sh` recreates it), and VS Code's workspace-only extensions (enable them again for the new folder). For about two minutes afterwards the site returns 500 errors, because PHP has cached the old file paths; wait, or restart the site in Local.
- **axe reports "overlapped by another element" (contrast 0)** when an empty decorative layer, such as the menu sparkles, lies on top of text. Hide the layer with `visibility: hidden` while it isn't showing.
- **Sliding something open with `grid-template-rows: 0fr` → `1fr`:** between the two values the grid container is a little taller than its row. Put backgrounds and borders on the item inside, not on the container, or a band of background shows below the edge mid-slide (the mobile menu, `header.css`).
- **axe can't measure contrast through a `::before`/`::after` with a background** (for example a fill that slides in on hover). It marks the text "needs review", and pa11y counts that as an error. Put the fill in a real `aria-hidden` span instead (the social pills, `contact.css`).
- **axe can't judge text with a CSS gradient behind it** (the dot patterns are `radial-gradient`s) and marks it "needs review", which pa11y counts as an error. pa11y's `hideElements` doesn't help: it only drops issues *inside* the hidden element. Paint decorative patterns on a `::before` of the decorative layer (axe skips pseudo-elements that aren't the text's own), check the text against them by hand, and use `--text-halo` where a dot can sit under a letter (`contact.css`).
- **Scroll-driven animations follow the nearest scroll container, and `overflow: hidden` makes one.** An `animation-timeline: view()` inside an `overflow: hidden` parent measures that parent, which never scrolls, so the animation stands still. Use `overflow: clip` to clip without creating a scroll container (`.start-hero`).
- **A sideways scroller (`overflow-x: auto`) clips upwards and downwards too.** Anything that pokes out of its slides, such as an opened notebook cover, is cut off. Give the scroller padding for it (the "Läs vidare" carousel, `read-more.css`).
- **Decorative graphics with text in them, and slides that are faded until they're current,** are skipped by the contrast tests with `hideElements` in both `.pa11yci*.json` (the "Läs vidare" notebooks, decision 0008). Add to that list only for the same reasons, and never for text the visitor reads.
- **The old `CarolineTheme/` uses Windows line endings** and fails every check. It's excluded on purpose; don't fix it.

## Checklist and open decisions

`CHECKLIST.md` at the repo root lists everything left to do, grouped by area. Items marked "Discuss" need Caroline's decision before you build them. Tick items off in the same commit as the work, and add new ones as they come up.

Decisions made so far:

- **Preferences dialog:** built as a proof of concept, see `docs/decisions/0002-preferences-dialog.md`. Rules every component must follow:
  - **Motion only runs when `<html>` has `data-motion="on"`.** Never add animations that bypass the rule in `utilities.css`.
  - **Anything from another domain** (iframes, scripts, images, fonts) **must wait for consent** (`thirdParty: "yes"`). Iframes in content are held back automatically by `inc/embeds.php`; anything else needs the same treatment.
  - **Save choices only through `window.clindPrefs.save()`** (head script in `inc/preferences.php`). It fires a `clind:prefs` event on `document`; listen to that, or watch `data-theme`/`data-motion` on `<html>`, instead of keeping your own copy.
  - **Changing the choices means raising `version`** in `clind_prefs_config()`.
- **Buttons that need JavaScript** are hidden with `html:not(.js)` (the head script adds `.js` before paint), so they don't shift the layout and never show without working. Style states that depend on the visitor's choices from `<html>`'s attributes, so they're right on first paint. A button whose text changes with its state (the header toggles) gets no `aria-pressed`: the text already says it.
- **Header:** where it differs from the design, see `docs/decisions/0003-header.md`. It's sticky, and `scroll-padding-top` on `<html>` uses `--header-height` so nothing scrolls in under it. **Anything else that sticks to the top must be added to that offset,** and nothing may cover focused elements (WCAG 2.4.11).
- **Fonts:** self-hosted and trimmed, see `docs/decisions/0001-self-hosted-fonts.md`. Don't add font weights, italics or characters without updating that record and staying under the 100 KB budget.
- **Block editor styling:** the editor loads `fonts.css`, `tokens.css` and `typography.css` (`inc/setup.php`), so writing looks like the site, but **always in light mode** (Caroline's choice, easier for long writing). Tell Caroline whenever a change affects how the editor looks.
- **Content on the draft pages** in WordPress is experimental. Caroline knows about its structural issues; they get fixed when each page is built.
