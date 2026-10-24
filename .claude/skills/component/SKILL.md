---
name: component
description: Build one component or checklist item for the carolinelindstrom theme the agreed way — check in with a plan, build, lint, test accessibility in light and dark mode, check in the browser, iterate with small commits, tidy up, document, hand over a device checklist, then merge the commits into one (never push). Use whenever starting work on a CHECKLIST.md item or a part of the design.
---

# Build a component

The routine for every piece of the theme. CLAUDE.md has the rules; this is the order to apply them in. Don't skip steps, and say so when one can't be done.

## 1. Understand

- Find the item in `CHECKLIST.md`. If it says "Discuss", it needs Caroline's decision first: ask, don't build.
- Read the relevant board in the design canvas (see "The design" in CLAUDE.md) and the files the component touches.
- Check `docs/decisions/` for earlier decisions that affect it.

## 2. Check in (wait for a go-ahead)

Tell Caroline, briefly:
- what you'll build, and which files you'll create or change
- where the design conflicts with the quality bar (WCAG 2.2 AA, performance, privacy), and what you suggest instead
- anything that affects the block editor or content she has written
- questions only she can answer

Then wait. "Go", "ok, do it" or similar means go.

## 3. Build

- CSS: one file per component in `carolinelindstrom/assets/css/components/`, added to the list in `inc/enqueue.php`. Values from `tokens.css`; new values become tokens.
- Motion only under `html[data-motion="on"]`, never bypassing `utilities.css`. Every hover effect also on `:focus-visible`.
- Anything from another domain waits for consent (`thirdParty: "yes"`, see `docs/decisions/0002-preferences-dialog.md`).
- JavaScript: vanilla, `defer`, only on pages that need it, and the page must work without it.
- PHP: `clind_` prefix, escape all output, Swedish UI strings in `__()` with the `carolinelindstrom` text domain.
- Newer CSS features behind `@supports`, with a fallback.

## 4. Check

Run all of these (from the repo root; Local must be running):

```bash
npm run lint
npm run test:a11y:summary
```

- `lint` must pass. Fix the code; don't switch rules off. After any `--fix`, read `git diff`.
- `test:a11y:summary` must show no theme errors. Content errors are Caroline's drafts: mention them, don't fix them.
- In the browser (built-in browser pane): the component in dark and light mode, at phone width (375 px), by keyboard only (Tab, Enter, Space, Escape), with motion on and off. Reset the viewport afterwards.
- Read CLAUDE.md's "Gotchas" if a tool behaves strangely.

## 5. Iterate

Caroline will look and ask for changes. For each round:
- Commit small and often (Swedish messages). Run `npm run lint` before each commit, and `test:a11y:summary` when markup, colors or CSS changed.
- After each fix, check that nothing else changed: compare the other states (both modes, phone and desktop, menu open, scrolled) with how they looked before. A fix in one place often breaks another.
- Facts you state (WCAG criteria, guidelines, browser support) are looked up, not remembered.

## 6. Tidy up

When it works and Caroline is happy, before calling it done:
- Split CSS files that grew large (roughly over 250 lines) into one file per part.
- Gather logic that ended up in several places (e.g. several inline scripts) into one place.
- Rewrite the component's decision record: keep the real decisions short, and move "how it works" into `carolinelindstrom/README.md`. A long table of small tweaks is a sign it needs this.
- Run the checks again after tidying.

## 7. Document

- A trade-off against the design or the quality bar → a new `docs/decisions/NNNN-*.md` (with sources) and a line in its README.
- A new rule future work must follow → CLAUDE.md.
- A new trap → "Gotchas" in CLAUDE.md.
- How to use something new → `carolinelindstrom/README.md`.
- Tick the item in `CHECKLIST.md`; add follow-ups as new items.

## 8. Hand over to Caroline

Report back: what was built, what was tested and how, and what wasn't. Always end with this checklist for her, since an agent can't do these:

- [ ] iPhone Safari (and touch on a tablet if relevant)
- [ ] VoiceOver on macOS and iOS
- [ ] Text zoom 200 % and page zoom 400 %
- [ ] A look in the block editor, if the component affects content
- [ ] The copy reads well

## 9. Merge the commits (when Caroline says it's done)

Follow "Working agreements" in CLAUDE.md: tag the detailed history, merge the component's unpushed commits into one with a summary, check the tree is unchanged. Never push unless Caroline asks.
