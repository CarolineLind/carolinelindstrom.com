/* --------------------------------------------------
 * ------------------ RENDER ICONS ------------------
 * -------------------------------------------------- */

/*
Renders the PNG versions of carolinelindstrom/assets/icons/favicon.svg,
for browsers and devices that can't use the SVG (docs/decisions/0005-logo-and-favicon.md).
Both use the dark version. The apple-touch-icon gets square corners: iOS rounds it itself.
Run again after changing favicon.svg.

Usage: node tools/icons/render-icons.mjs
*/

import { createRequire } from 'node:module';
import { readFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..', '..');
const icons = path.join(root, 'carolinelindstrom', 'assets', 'icons');

/* The same Puppeteer that pa11y uses */
const require = createRequire(import.meta.url);
const puppeteer = require(require.resolve('puppeteer', { paths: [require.resolve('pa11y', { paths: [root] })] }));

const svg = readFileSync(path.join(icons, 'favicon.svg'), 'utf8');

const outputs = [
  { file: 'favicon-32.png', size: 32, svg },
  { file: 'apple-touch-icon.png', size: 180, svg: svg.replace('rx="14"', 'rx="0"') },
];

const browser = await puppeteer.launch();
const page = await browser.newPage();
await page.emulateMediaFeatures([{ name: 'prefers-color-scheme', value: 'dark' }]);

for (const { file, size, svg: source } of outputs) {
  await page.setViewport({ width: size, height: size });
  await page.setContent(
    `<style>html,body{margin:0;background:transparent}svg{display:block;width:${size}px;height:${size}px}</style>${source}`
  );
  await page.screenshot({ path: path.join(icons, file), omitBackground: true });
  console.log(`${file} (${size} × ${size})`);
}

await browser.close();
