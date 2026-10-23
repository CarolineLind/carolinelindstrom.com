/* --------------------------------------------------
 * ----------------- A11Y SUMMARY -------------------
 * -------------------------------------------------- */

/*
Runs pa11y in light and dark mode and sorts every error into
"theme" (must be fixed in code) or "content" (inside the page content
written in WordPress). Exits with 1 if the theme has errors, 0 otherwise,
so content still being written never blocks a commit.

Usage: npm run test:a11y:summary   (Local must be running)
*/

import { spawnSync } from 'node:child_process';
import { createRequire } from 'node:module';
import { readFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const pa11yCi = path.join(root, 'node_modules', '.bin', 'pa11y-ci');

/* The same Puppeteer that pa11y uses */
const require = createRequire(import.meta.url);
const puppeteer = require(require.resolve('puppeteer', { paths: [require.resolve('pa11y', { paths: [root] })] }));

const modes = [
  { name: 'Light', config: '.pa11yci.json' },
  { name: 'Dark', config: '.pa11yci.dark.json' },
];

/*
Errors inside the page content have selectors that start at the post
(#post-123 …) or the content wrapper. Others (e.g. an inline SVG in the
content with its own id) are looked up in a browser to see where they are.
*/
const looksLikeContent = (selector = '') => /#post-\d+|\.entry-content/.test(selector);

async function findContentSelectors(url, selectors, launchArgs) {
  if (!selectors.length) {
    return new Set();
  }
  const browser = await puppeteer.launch({ args: launchArgs });
  try {
    const page = await browser.newPage();
    await page.goto(url, { waitUntil: 'load' });
    const inContent = await page.evaluate((list) => list.filter((selector) => {
      try {
        const element = document.querySelector(selector);
        return !!(element && element.closest('.entry-content'));
      } catch {
        return false;
      }
    }), selectors);
    return new Set(inContent);
  } finally {
    await browser.close();
  }
}

const shortUrl = (url) => (url.replace(/^https?:\/\/[^/]+/, '') || '/').replace('?a11y-test=preferences-dialog', ' (preferences dialog)');
const firstSentence = (message) => message.split(/\s\(https?:|\.\s/)[0];

let themeErrors = 0;

for (const mode of modes) {
  const run = spawnSync(pa11yCi, ['--config', mode.config, '--json'], { cwd: root, encoding: 'utf8' });
  const launchArgs = JSON.parse(readFileSync(path.join(root, mode.config), 'utf8')).defaults.chromeLaunchConfig.args;

  let report;
  try {
    report = JSON.parse(run.stdout);
  } catch {
    console.error(`${mode.name}: pa11y-ci didn't return results. Is Local running?\n${run.stderr}`);
    process.exit(2);
  }

  console.log(`\n=== ${mode.name} mode ===`);

  for (const [url, issues] of Object.entries(report.results)) {
    const errors = issues.filter((issue) => issue.message);
    const unsure = [...new Set(errors.filter((issue) => !looksLikeContent(issue.selector) && issue.selector).map((issue) => issue.selector))];
    const alsoContent = await findContentSelectors(url, unsure, launchArgs);
    const isContent = (issue) => looksLikeContent(issue.selector) || alsoContent.has(issue.selector);

    const theme = errors.filter((issue) => !isContent(issue));
    const content = errors.filter(isContent);

    const status = theme.length ? '✘' : '✔';
    console.log(`${status} ${shortUrl(url)}  theme: ${theme.length}, content: ${content.length}`);

    /* Theme errors in full: these must be fixed */
    for (const issue of theme) {
      console.log(`    THEME  ${firstSentence(issue.message)}\n           ${issue.selector || '(no selector)'}  [${issue.runner}]`);
    }

    /* Content errors grouped, so the list stays readable */
    const groups = new Map();
    for (const issue of content) {
      const key = firstSentence(issue.message);
      groups.set(key, (groups.get(key) || 0) + 1);
    }
    for (const [message, count] of groups) {
      console.log(`    content ${count}× ${message}`);
    }

    themeErrors += theme.length;
  }
}

console.log(themeErrors
  ? `\n✘ ${themeErrors} theme error(s) to fix.`
  : '\n✔ No theme errors. Any content errors are in the pages written in WordPress.');

process.exit(themeErrors ? 1 : 0);
