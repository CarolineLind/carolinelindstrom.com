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

## 3. Fonts
- [ ] Download Bricolage Grotesque, DM Sans and JetBrains Mono as woff2, Latin subset only
- [ ] `@font-face` in its own file, `font-display: swap`, size-adjusted fallbacks to avoid layout shift
- [ ] Preload the one or two fonts the first screen needs
- [ ] Remove Google Fonts from `enqueue.php`, `setup.php` (editor) and the preconnect hints
- [ ] Check the total stays under 100 KB

## 4. Tools (same setup in VS Code)
- [ ] Install PHP and Composer with Homebrew; enable the `code` command in VS Code
- [ ] PHPCS with WordPress Coding Standards (`composer.json`)
- [ ] Stylelint (`package.json`)
- [ ] pa11y or axe for automated accessibility checks against the Local site
- [ ] `.vscode/extensions.json` and `.vscode/settings.json` so VS Code shows the same warnings
- [ ] `.editorconfig`
- [ ] Fix whatever the tools find in the existing code

## 5. Preferences banner (cookies, motion, light/dark)
Decided: one accessible banner on the first visit, **before anything moves**. Third-party content and analytics load only after consent. No "click to load" buttons.
- [ ] Discuss: exact texts and choices (motion on/off, light/dark/system, third-party content yes/no)
- [ ] Discuss: what replaces the Spotify player when third-party content is declined (a plain link to the album?)
- [ ] Discuss: can the dialog be closed without choosing (Escape key), and what do we assume then?
- [ ] Motion and third-party content stay off until a choice is made
- [ ] System settings detected and pre-selected (reduced motion, light/dark)
- [ ] Native `<dialog>`, real heading, labelled controls, focus moved in and returned, works with keyboard, VoiceOver and 400 % zoom
- [ ] "Accept" and "Decline" equally easy and equally prominent
- [ ] Choice saved in the browser; a link in the footer reopens the settings
- [ ] No effect on load time or layout: markup in the page, no library, no layout shift, LCP unaffected
- [ ] Header toggles for motion and light/dark stay available at all times
- [ ] Integritetspolicy updated to describe what's stored and why

## 6. Header components
- [ ] Mobile menu button (hamburger that becomes an X)
- [ ] Sliding menu indicator with the sparkles (anchor positioning, with a fallback)
- [ ] Scroll progress bar

## 7. Content model
- [ ] Discuss: how sections are built in the editor (theme-provided block patterns, locked to content-only editing, or something else)
- [ ] Decide which parts are editor content and which are built into templates
- [ ] Block styles for the editor: make the editor look like the site

## 8. Start page (Välkommen)
- [ ] Hero: the tilting paper, the portrait with the color shapes
- [ ] Background effects (dots, stars, spotlight)
- [ ] Keyword banner (endless scroll, paused on hover, hidden duplicate)
- [ ] "Läs vidare" cards with doodle icons
- [ ] Code editor section with the light-mode button
- [ ] Contact section: email, social links, Spotify player (after consent)
- [ ] Footer "Till toppen" with the progress ring

## 9. Other pages
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
