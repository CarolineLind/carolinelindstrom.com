# Checklist – new theme

Everything that needs doing before the new theme replaces `CarolineTheme` on the live site. Grouped by area so one area can be taken per work session.

- **Ticked** = done and committed.
- **"Discuss"** = needs a decision from Caroline before anything is built.
- Add new items as they come up. Move decisions into `CLAUDE.md` once they're made.

## 1. Foundation
- [x] Theme skeleton, `inc/` setup, enqueue list
- [x] CSS variables for dark and light mode (`tokens.css`)
- [x] Reset, typography, utilities
- [x] Header and footer shells, page/single/index/front-page/404 templates
- [x] Branch `new-theme`, symlink into Local, pushed to GitHub
- [x] README.md and CLAUDE.md
- [x] Raise `Requires PHP` in `style.css` from 7.4 to 8.2

## 2. Turn off what the site doesn't use
All of it lives in `inc/cleanup.php`.
- [x] Comments and pingbacks (all post types, admin menus, comment feeds)
- [x] Posts hidden in the admin; single posts, the blog page and archives return 404
- [x] Search: no database query; `/?s=` returns 404
- [x] Remove `comment-form`, `comment-list` and `search-form` from the `html5` support list
- [x] Feeds return 404, links removed from `<head>`
- [x] XML-RPC methods, emoji, oEmbed discovery and `wp-embed`, generator tag, shortlink, REST discovery link
- [x] User endpoints, author pages and the users sitemap closed to visitors (they revealed the login name)
- [x] Attachment pages redirect to the file
- [x] Block library CSS: WordPress 7.1 already loads only what the content uses. `global-styles` and `classic-theme-styles` are decided in 7. Content model
- [x] Check the admin while logged in: no Posts or Comments menus, Pages and the editor work as before

## 3. Fonts
Why the fonts look the way they do: `docs/decisions/0001-self-hosted-fonts.md`.
- [x] Download Bricolage Grotesque, DM Sans and JetBrains Mono as woff2, Latin subset only
- [x] Subset further to the characters the site uses (`tools/fonts/subset-fonts.sh`)
- [x] `@font-face` in its own file, `font-display: swap`, size-adjusted fallbacks to avoid layout shift
- [x] Preload the one or two fonts the first screen needs
- [x] Remove Google Fonts from `enqueue.php`, `setup.php` (editor) and the preconnect hints
- [x] Check the total stays under 100 KB (99.1 KB)
- [x] Block editor shows the site's fonts and sizes in light mode
- [x] Look at the site and the editor: headings, body text, åäö, italics, light background in the editor

## 4. Tools (same setup in VS Code)
How to use them: "Quality checks" in `carolinelindstrom/README.md`.
- [x] Install PHP 8.5 and Composer with Homebrew
- [x] PHPCS with WordPress Coding Standards and PHP 8.2 compatibility (`composer.json`, `phpcs.xml.dist`)
- [x] Stylelint (`package.json`, `.stylelintrc.json`)
- [x] pa11y with axe and HTML_CodeSniffer, light and dark mode (`.pa11yci.json`, `.pa11yci.dark.json`)
- [x] `.vscode/extensions.json` and `.vscode/settings.json`; the four extensions installed in VS Code
- [x] `.editorconfig`, `.gitignore`
- [x] Fix whatever the tools find in the existing code (prefix renamed `cl_` → `clind_`, modern media queries, `clip` → `clip-path`)
- [x] Open the repo in VS Code and check that warnings show up in PHP and CSS files
- [x] All Stylelint rules switched back on; comment banners and token groups adjusted to follow them
- [x] Old `CarolineTheme/` excluded from all checks, also in VS Code

## 5. Preferences banner (cookies, motion, light/dark)
Proof of concept built and working. Decisions: `docs/decisions/0002-preferences-dialog.md`.
- [x] Discuss: choices (appearance and motion: on/off/"Som min enhet"; third-party content: show/don't show)
- [x] Discuss: Spotify when declined: a card "Du har valt att inte visa Spotify-spelaren." plus a link to Spotify
- [x] Discuss: Escape on the first visit saves the pre-selected defaults; later it just closes
- [x] Motion and third-party content stay off until a choice is made (also without JavaScript)
- [x] System settings pre-selected ("Som min enhet") and followed live when they change
- [x] Native `<dialog>`, real heading, labelled controls, focus returned to the "Inställningar" button, 44 px targets, works at phone width
- [x] Declining is never harder than accepting (third-party defaults to "Visa inte", one button)
- [x] Choices saved in the browser with a date; asked again after 12 months or when the version changes
- [x] "Inställningar" button in the footer reopens the dialog
- [x] No effect on load time or layout: markup in the page, opened before first paint, about 3 KB JavaScript, no library
- [x] Third-party iframes held back on the server; missing iframe titles added automatically
- [x] pa11y: dialog tested on its own, pages tested with the dialog closed (0 errors in the theme, both modes)
- [ ] Discuss: final copy (Caroline will revisit)
- [ ] Design the dialog and the embed card
- [ ] Test with VoiceOver (macOS and iOS) and at 400 % zoom by hand
- [ ] Header toggles for motion and light/dark (section 6) use the same saved choices
- [ ] Integritetspolicy updated to describe what's stored and why
- [ ] Add "Statistik" as a fourth choice if analytics stays (section 11); raise the version so everyone is asked again

## 6. Header components
- [ ] Mobile menu button (hamburger that becomes an X)
- [ ] Sliding menu indicator with the sparkles (anchor positioning, with a fallback)
- [ ] Scroll progress bar

## 7. Content model
- [ ] Discuss: how sections are built in the editor (theme-provided block patterns, locked to content-only editing, or something else)
- [ ] Decide which parts are editor content and which are built into templates
- [ ] Block styles for the editor: make the editor look like the site
- [ ] Discuss: which colors the editor offers. Old content uses the editor's "black" text color, which is unreadable in dark mode (found by pa11y on Om mig, Prestanda, Tillgänglighet)

## 8. Start page (Välkommen)
- [ ] Hero: the tilting paper, the portrait with the color shapes
- [ ] Background effects (dots, stars, spotlight)
- [ ] Keyword banner (endless scroll, paused on hover, hidden duplicate)
- [ ] "Läs vidare" cards with doodle icons
- [ ] Code editor section with the light-mode button
- [ ] Contact section: email, social links, Spotify player (after consent)
- [ ] Footer "Till toppen" with the progress ring

## 9. Other pages
pa11y findings in today's draft content (2026-10-23), all in the content, none in the theme: text colors with too little contrast, iframes without `title`, image links without alt text, an inline SVG with its own colors. Run `npm run test:a11y` again as each page is rebuilt.
- [ ] Om mig (two design versions: timeline and story; choose one)
- [ ] Tillgänglighet
- [ ] Prestanda
- [ ] Frontend lead
- [ ] Integritetspolicy
- [ ] 404

## 10. SEO
- [ ] Meta description per page (a field in the editor, with a fallback)
- [ ] Canonical URLs
- [ ] Open Graph and share image
- [ ] `Person` structured data (JSON-LD), plus `WebSite`
- [ ] Core sitemap: only the pages that should be in it
- [ ] `robots.txt`, favicon and app icons, `theme-color` for both modes
- [ ] Redirects from old URLs (e.g. `valkommen-v3`, `om-mig-v3`, `prestanda-v1`)

## 11. Analytics and Jetpack
- [ ] Discuss: what Jetpack is used for today
- [ ] Discuss: keep Jetpack Stats, switch to something cookieless and EU-hosted, or drop analytics
- [ ] Remove Jetpack's carousel, likes and slider assets if they aren't used
- [ ] If Jetpack stays: it talks to WordPress.com over XML-RPC, which `inc/cleanup.php` now empties. Check that Jetpack still connects, or allow its methods
- [ ] Discuss: Akismet (only filters comment and form spam) and Jetpack Boost (overlaps with SiteGround's Speed Optimizer)

## 12. Security and SiteGround
- [x] Live site on a PHP version patched for CVE-2026-109618/109619 (8.2.35; SiteGround updates PHP automatically)
- [x] Local's PHP updated to 8.2.35, matching live
- [ ] Force HTTPS
- [ ] Block `xmlrpc.php` on the server (the theme empties its methods, but the file still answers), unless Jetpack needs it
- [ ] Security headers: HSTS, Content-Security-Policy, X-Content-Type-Options, Referrer-Policy, Permissions-Policy
- [ ] Review Speed Optimizer settings so they don't clash with the theme
- [ ] Check whether the plan has SSH and Git, for deploying from GitHub
- [ ] Remove the old theme's Font Awesome kit and any unused plugins after launch

## 13. Quality check before merge
- [ ] Manual WCAG 2.2 AA review: keyboard, VoiceOver (macOS and iOS), zoom, contrast in both modes, reduced motion
- [ ] Lighthouse and Core Web Vitals on mobile for every page
- [ ] Every browser in the support list, plus 320 px wide
- [ ] HTML validation
- [ ] Linting clean

## 14. Launch
- [ ] Pull request from `new-theme` to `main`, with a summary
- [ ] Deploy to SiteGround and activate the theme
- [ ] Menus, front page setting and redirects in place on live
- [ ] Clear SiteGround's cache, check the live site, run Lighthouse again
