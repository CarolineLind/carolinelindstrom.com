/* --------------------------------------------------
 * ------------------ CODE EDITOR -------------------
 * -------------------------------------------------- */

/*
The code editor window on the start page (template-parts/front/code-editor.php).
  The 'light' token: switches to light mode through window.clindPrefs, like the
  header's toggle, and the terminal answers (its lines already hold their
  space, so nothing shifts). The button then disappears (CSS), so focus moves
  to the answer, which is read out.
  Typing: with motion on, the code is typed out when it scrolls into view. A
  copy is laid over the real code and typed; the real code stays readable for
  screen readers underneath (decision 0009). It takes about seven seconds:
  more than five, which is allowed because "Pausa rörelse" stops it (WCAG
  2.2.2). It also stops at once if the code gets focus or a press.
*/
(function () {
	'use strict';

	var root = document.documentElement;
	var editor = document.querySelector('.code-editor');

	if (!editor) {
		return;
	}

	/* ------------------ LIGHT BUTTON ------------------ */

	var lightButton = editor.querySelector('[data-code-editor-light]');
	var answer = editor.querySelector('.code-editor__terminal-answer');
	var prefsApi = window.clindPrefs;

	if (lightButton && answer && prefsApi) {
		lightButton.addEventListener('click', function () {
			var prefs = Object.assign({}, prefsApi.config.defaults, prefsApi.read());
			prefs.theme = 'light';
			prefsApi.save(prefs);
			answer.classList.remove('code-editor__terminal-answer--waiting');
			answer.focus();
		});
	}

	/* --------------------- TYPING --------------------- */

	var code = editor.querySelector('.code-editor__code');
	var source = editor.querySelector('.code-editor__source');

	/* Characters per millisecond (50 a second) */
	var SPEED = .05;
	var START_DELAY = 250;
	var CARET_LINGER = 1200;

	if (!code || !source || root.getAttribute('data-motion') !== 'on' || !window.IntersectionObserver) {
		return;
	}

	var copy = source.cloneNode(true);
	copy.className = 'code-editor__copy';
	copy.setAttribute('aria-hidden', 'true');
	copy.inert = true;
	copy.querySelectorAll('.sr-only').forEach(function (hint) {
		hint.remove();
	});

	/* Every text node, emptied. Line numbers and the light token appear whole, not letter by letter */
	var items = [];
	var walker = document.createTreeWalker(copy, NodeFilter.SHOW_TEXT);
	var node;

	while ((node = walker.nextNode())) {
		items.push({
			node: node,
			text: node.nodeValue,
			instant: !!node.parentNode.closest('.code-editor__ln, .code-editor__light, .code-editor__light-text'),
			token: node.parentNode.closest('.code-editor__light, .code-editor__light-text')
		});
		node.nodeValue = '';
	}

	var total = items.reduce(function (sum, item) {
		return sum + (item.instant ? 0 : item.text.length);
	}, 0);

	var caret = document.createElement('span');
	caret.className = 'code-editor__caret';

	/* ------------------ AUTO-CLOSING ------------------ */

	/*
	Like a real editor: typing ' { or [ puts its partner after the caret at
	once, and the typing later "types over" it. A { or [ whose partner is on a
	later line shows {} until the line break is typed; then the } drops to a
	line of its own below the caret, with its indent and a line number, and is
	pushed down as lines are typed. The pairs are found once, in the code as
	one string (flat), skipping strings and comments; positions count only the
	typed characters.
	*/
	var flat = items.filter(function (item) {
		return !item.instant;
	}).map(function (item) {
		return item.text;
	}).join('');
	var pairs = [];
	var stack = [];
	var stringStart = -1;
	var inComment = false;

	for (var i = 0; i < flat.length; i++) {
		var ch = flat[i];

		if (inComment) {
			inComment = ch !== '\n';
		} else if (stringStart >= 0) {
			if (ch === '\'') {
				pairs.push({ open: stringStart, close: i, ch: ch, quote: true });
				stringStart = -1;
			}
		} else if (ch === '/' && flat[i + 1] === '/') {
			inComment = true;
		} else if (ch === '\'') {
			stringStart = i;
		} else if (ch === '{' || ch === '[') {
			stack.push({ open: i, ch: ch === '{' ? '}' : ']' });
		} else if ((ch === '}' || ch === ']') && stack.length) {
			var opener = stack.pop();
			pairs.push({ open: opener.open, close: i, ch: opener.ch });
		}
	}

	var ghost = document.createElement('span');
	ghost.className = 'code-editor__ghost';
	var ghostAt = -1;

	function ghostPart(className, text) {
		var span = document.createElement('span');
		span.className = className;
		span.textContent = text;
		return span;
	}

	/* The partners still waiting after typed position p, innermost first */
	function updateGhost(p) {
		if (p === ghostAt) {
			return;
		}
		ghostAt = p;
		ghost.textContent = '';

		var line = flat.slice(0, p).split('\n').length;
		var nextBreak = flat.indexOf('\n', p);
		var below = 0;

		pairs.filter(function (pair) {
			return pair.open < p && pair.close >= p;
		}).sort(function (a, b) {
			return b.open - a.open;
		}).forEach(function (pair) {
			var breakAfterOpen = flat.indexOf('\n', pair.open);
			var sameLine = nextBreak === -1 || nextBreak > pair.close || breakAfterOpen >= p;

			if (sameLine) {
				ghost.appendChild(pair.quote ? ghostPart('code-editor__tok--string', pair.ch) : document.createTextNode(pair.ch));
				return;
			}
			below++;
			var lineStart = flat.lastIndexOf('\n', pair.close - 1) + 1;
			var indent = flat.slice(lineStart, pair.close).match(/^ */)[0];
			ghost.appendChild(document.createTextNode('\n'));
			ghost.appendChild(ghostPart('code-editor__ln', String(line + below)));
			ghost.appendChild(document.createTextNode(indent + pair.ch));
		});
	}

	code.classList.add('code-editor__code--typing');
	code.appendChild(copy);

	var done = false;
	var observer;

	function finish() {
		if (done) {
			return;
		}
		done = true;
		observer.disconnect();
		code.classList.remove('code-editor__code--typing');
		copy.remove();
	}

	/* Type up to where the clock says we should be */
	function type(started) {
		return function step(now) {
			if (done) {
				return;
			}

			var budget = Math.round((now - started) * SPEED);
			var typed = Math.min(budget, total);

			items.forEach(function (item) {
				/* Whole at once, as soon as the typing reaches it */
				if (item.instant) {
					item.node.nodeValue = budget > 0 ? item.text : '';

					/* The light token has padding: until reached, an empty one would ride down the lines (CLS) */
					if (item.token) {
						item.token.style.display = budget > 0 ? '' : 'none';
					}
					return;
				}
				var take = Math.max(0, Math.min(budget, item.text.length));
				item.node.nodeValue = item.text.slice(0, take);
				budget -= take;
				if (take > 0) {
					item.node.parentNode.insertBefore(caret, item.node.nextSibling);
				}
			});

			updateGhost(typed);
			if (caret.parentNode) {
				caret.parentNode.insertBefore(ghost, caret.nextSibling);
			}

			if ((now - started) * SPEED >= total) {
				setTimeout(finish, CARET_LINGER);
				return;
			}
			requestAnimationFrame(step);
		};
	}

	observer = new IntersectionObserver(function (entries) {
		if (!entries.some(function (entry) {
			return entry.isIntersecting;
		})) {
			return;
		}
		observer.disconnect();
		setTimeout(function () {
			requestAnimationFrame(function (now) {
				type(now)(now);
			});
		}, START_DELAY);
	}, { threshold: .35 });

	observer.observe(code);

	/* Show the real code straight away when the visitor needs it */
	code.addEventListener('focusin', finish);
	code.addEventListener('pointerdown', finish);
	document.addEventListener('clind:prefs', function () {
		if (root.getAttribute('data-motion') !== 'on') {
			finish();
		}
	});
})();
