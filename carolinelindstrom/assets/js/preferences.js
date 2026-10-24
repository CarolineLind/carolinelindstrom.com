/* --------------------------------------------------
 * ------------------ PREFERENCES -------------------
 * -------------------------------------------------- */

/*
The preferences dialog: saves the visitor's choices, previews light/dark
while choosing, reopens from the "Inställningar" button and shows or hides
third-party embeds. Reading and applying the choices lives in the <head>
script (inc/preferences.php), shared through window.clindPrefs.
*/
(function () {
	'use strict';

	var prefsApi = window.clindPrefs;
	var dialog = document.getElementById('clind-prefs');

	if (!prefsApi || !dialog || typeof dialog.showModal !== 'function') {
		return;
	}

	var form = dialog.querySelector('form');
	var title = document.getElementById('clind-prefs-title');

	/* The pre-selected options in the dialog; also what Escape saves on the first visit */
	var defaults = prefsApi.config.defaults;
	var saved = prefsApi.read();

	function fill(prefs) {
		['theme', 'motion', 'thirdParty'].forEach(function (name) {
			var input = form.querySelector('input[name="' + name + '"][value="' + prefs[name] + '"]');
			if (input) {
				input.checked = true;
			}
		});
	}

	function fromForm() {
		return {
			theme: form.elements.theme.value,
			motion: form.elements.motion.value,
			thirdParty: form.elements.thirdParty.value
		};
	}

	/* --------------------- EMBEDS --------------------- */

	/* Swap held-back embeds (inc/embeds.php) in or out */
	function applyEmbeds(prefs) {
		var allowed = !!prefs && prefs.thirdParty === 'yes';

		document.querySelectorAll('.embed-consent').forEach(function (embed) {
			var fallback = embed.querySelector('.embed-consent__fallback');
			var loaded = embed.querySelector('.embed-consent__frame');

			if (allowed && !loaded) {
				var frame = document.createElement('div');
				frame.className = 'embed-consent__frame';
				frame.appendChild(embed.querySelector('template').content.cloneNode(true));
				embed.appendChild(frame);
				fallback.hidden = true;
			} else if (!allowed && loaded) {
				loaded.remove();
				fallback.hidden = false;
			}
		});
	}

	/* -------------------- SAVE, OPEN ------------------ */

	var save = prefsApi.save;

	/* Saved here or by the header toggles (header.js) */
	document.addEventListener('clind:prefs', function (event) {
		saved = event.detail;
		applyEmbeds(saved);
	});

	function open() {
		fill(saved || defaults);
		if (saved && title.dataset.titleAgain) {
			title.textContent = title.dataset.titleAgain;
		}
		dialog.classList.toggle('prefs--first', !saved);
		dialog.showModal();
	}

	/* -------------------- LEAVE ----------------------- */

	/*
	First visit, motion on: veils in the page background take over from the
	backdrop, the header first, and the logo writes itself in (intro.css,
	logo.css). The motion rule in utilities.css stops both if motion is turned
	off; the veils are then hidden.
	*/
	function openIntro() {
		var veils = ['header', 'content'].map(function (part) {
			var veil = document.createElement('div');
			veil.className = 'intro-veil intro-veil--' + part;
			veil.setAttribute('aria-hidden', 'true');
			veil.addEventListener('animationend', function () {
				veil.remove();
			});
			document.body.appendChild(veil);
			return veil;
		});
		/* In case animationend never comes */
		window.setTimeout(function () {
			veils.forEach(function (veil) {
				veil.remove();
			});
		}, 4000);

		var logo = document.querySelector('.logo');
		if (logo) {
			logo.classList.add('logo--write');
		}
	}

	/* With motion on, the dialog slides out before it closes (preferences.css) */
	function leave() {
		if (dialog.classList.contains('prefs--leaving')) {
			return;
		}

		if (document.documentElement.getAttribute('data-motion') !== 'on') {
			dialog.close();
			return;
		}

		var first = dialog.classList.contains('prefs--first');

		var timer;
		function finish(event) {
			if (event && (event.target !== dialog || event.propertyName !== 'transform')) {
				return;
			}
			window.clearTimeout(timer);
			dialog.removeEventListener('transitionend', finish);
			/* In the same task as close(), so the page never flickers into view */
			if (first) {
				openIntro();
			}
			dialog.close();
			dialog.classList.remove('prefs--leaving');
		}
		dialog.addEventListener('transitionend', finish);
		/* In case transitionend never comes (e.g. the tab is in the background) */
		timer = window.setTimeout(finish, 1000);
		dialog.classList.add('prefs--leaving');
	}

	/* Preview light/dark behind the dialog; motion stays as it was until saved */
	form.addEventListener('change', function (event) {
		if (event.target.name === 'theme') {
			prefsApi.apply({
				theme: event.target.value,
				motion: saved ? saved.motion : 'off'
			});
		}
	});

	form.addEventListener('submit', function (event) {
		event.preventDefault();
		save(fromForm());
		leave();
	});

	/* Escape: first visit saves the defaults, later visits leave the saved choices as they were */
	dialog.addEventListener('cancel', function (event) {
		event.preventDefault();
		if (saved) {
			prefsApi.apply(saved);
		} else {
			save(defaults);
		}
		leave();
	});

	document.querySelectorAll('[data-prefs-open]').forEach(function (button) {
		button.hidden = false;
		button.addEventListener('click', open);
	});

	/* "Som min enhet" follows the device when its settings change */
	['(prefers-color-scheme: light)', '(prefers-reduced-motion: reduce)'].forEach(function (query) {
		window.matchMedia(query).addEventListener('change', function () {
			if (saved) {
				prefsApi.apply(saved);
			}
		});
	});

	if (saved) {
		applyEmbeds(saved);
	} else if (!dialog.open) {
		open();
	}
})();
