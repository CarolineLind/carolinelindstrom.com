# 0002 – Preferences dialog before entering the site

- **Date:** 2026-10-23
- **Status:** Decided, proof of concept built (design and final copy to come)
- **Decided by:** Caroline Lindström

## In short

On the first visit, a dialog asks for three things before the site starts moving: **appearance**, **motion** and **content from other sites**. Nothing moves and nothing from other sites loads until a choice is made. If you ever wonder why the site is completely still for a visitor, or why the Spotify player shows a card instead, this is why.

## Background

The site is a playground with a lot of motion: rolling text, hover effects and animated doodles. WCAG 2.2 doesn't require consent for motion, only that it can be paused and that reduced-motion settings are respected. Caroline wanted to go further: nobody should get dizzy before they've found the off switch. At the same time, the Spotify player sets cookies, so it needs consent anyway. Rather than two banners, there's one dialog for everything.

## Decision

- **One modal dialog on the first visit** (`<dialog>` with `showModal()`), centered.
- **Three choices:**

  | Choice | Options | Pre-selected |
  | --- | --- | --- |
  | Utseende | Ljust / Mörkt / Som min enhet | Som min enhet |
  | Rörelse | På / Av / Som min enhet | Som min enhet |
  | Innehåll från andra sidor | Visa / Visa inte | Visa inte |

- **One button,** "Spara och gå vidare". There's no "Accept all": all choices are visible, and declining third-party content takes no extra click.
- **Escape** on the first visit saves the pre-selected choices. Those are always the privacy-safe ones. On later visits Escape just closes the dialog.
- **Live preview:** picking light or dark changes the page behind the dialog straight away.
- **Reopen** with the "Inställningar" button in the footer.
- **Saved in the browser** (`localStorage`, key `clind-prefs`) with a date and a version number. Visitors are asked again after 12 months, or when the version changes, for example when statistics is added as a choice.
- **Third-party content is held back on the server.** Every iframe from another domain is replaced with a card ("Du har valt att inte visa Spotify-spelaren." plus a link) and the original is kept in an inert `<template>`. No request reaches Spotify until the visitor has said yes.

## Consequences

1. **Motion is off by default everywhere.** The CSS only allows animations and transitions when `<html>` has `data-motion="on"`. That includes visitors without JavaScript, and the moment before a choice is made.
2. **Visitors without JavaScript** see no dialog and get the safe defaults: device appearance, no motion, no third-party content.
3. **If the browser blocks storage** (some private modes), the dialog appears on every page, because the choice can't be remembered.
4. **Every new embed is held back automatically,** including YouTube and Vimeo, with a card and a link. Iframes without a `title` get one added, for screen readers.
5. **SEO:** the dialog is marked `data-nosnippet`, so its text never shows up in search results. Its markup is part of the page, so there's no delay and no layout shift.
6. **Testing:** axe-core doesn't understand that modal dialogs are drawn above everything (the browser's "top layer"). It reports the dialog's text as covered by the page behind. The dialog therefore has a `z-index` (which the browser ignores for modal dialogs). pa11y tests the dialog on its own and tests the pages with the dialog closed (`.pa11yci.json`).

## Where it lives

| What | File |
| --- | --- |
| Settings, head script that applies the choices | `carolinelindstrom/inc/preferences.php` |
| Dialog markup | `carolinelindstrom/template-parts/preferences/dialog.php` |
| Saving, Escape, preview, reopening, embeds | `carolinelindstrom/assets/js/preferences.js` |
| Holding back iframes | `carolinelindstrom/inc/embeds.php` |
| Styles (proof of concept) | `assets/css/components/preferences.css`, `embed.css` |
| Motion rule | `assets/css/base/utilities.css` |

## How to change it

- **Add a choice** (e.g. "Statistik"): add it to the dialog, `fromForm()` and `defaults` in `preferences.js`, then **raise `version`** in `clind_prefs_config()` so everyone is asked again.
- **Change how long choices last:** `maxAgeDays` in `clind_prefs_config()`.
- **A new embed service with its own name and link:** add it to `clind_embed_service()` in `inc/embeds.php`.
