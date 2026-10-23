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
ln -s /Users/caroline.lindstrom/GIT-privat/laragon/carolinelindstrom "/Users/caroline.lindstrom/Local Sites/carolinelindstromcom/app/public/wp-content/themes/carolinelindstrom"
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
│   └── template-tags.php     Small template helpers (logo, menu classes)
├── header.php · footer.php
├── front-page.php · page.php · single.php · index.php · 404.php
├── template-parts/
│   ├── header/               Site navigation
│   └── content/              Page content and post lists
└── assets/
    ├── css/
    │   ├── base/             tokens, reset, typography, utilities
    │   ├── layout/           header, footer, content
    │   └── components/       One file per component
    ├── js/
    └── fonts/
```

## CSS

- **All design values are CSS variables** in `assets/css/base/tokens.css`: colors, fonts, sizes, spacing, corners and motion. Use the variables; don't hard-code values in other files.
- **Dark mode is the default.** Light mode overrides the variables under `[data-theme="light"]`. A small script in `<head>` sets `data-theme` before the page draws: the visitor's saved choice first, otherwise their system setting.
- **Motion** stops when the visitor's system asks for reduced motion, or when `data-motion="paused"` is set on `<html>`.
- **Files load in a fixed order** from the list in `inc/enqueue.php`: tokens → base → layout → components. A new component gets its own file in `assets/css/components/` and a line in that list.

## Design

The design lives in a Claude Design canvas: "carolinelindstrom.com – dark mode". It has dark, light and mobile versions of every page, plus experiments for banners, buttons, icons and effects. The design isn't final; we build the basics first and add components one at a time.

## Branches and commits

- `main` matches the live site (the old `CarolineTheme`).
- The new theme is built on `new-theme`.
- One commit per component, with commit messages in Swedish.

## License

All rights reserved. The code is public to show my work, not for reuse.
