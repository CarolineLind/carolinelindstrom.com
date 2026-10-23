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
	var defaults = { theme: 'system', motion: 'system', thirdParty: 'no' };
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

	function save(prefs) {
		saved = {
			version: prefsApi.config.version,
			saved: new Date().toISOString(),
			theme: prefs.theme,
			motion: prefs.motion,
			thirdParty: prefs.thirdParty
		};
		try {
			localStorage.setItem(prefsApi.config.key, JSON.stringify(saved));
		} catch (e) {
			/* Storage blocked: the choices still apply on this page */
		}
		prefsApi.apply(saved);
		applyEmbeds(saved);
	}

	function open() {
		fill(saved || defaults);
		if (saved && title.dataset.titleAgain) {
			title.textContent = title.dataset.titleAgain;
		}
		dialog.showModal();
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
		dialog.close();
	});

	/* Escape: first visit saves the defaults, later visits leave the saved choices as they were */
	dialog.addEventListener('cancel', function (event) {
		event.preventDefault();
		if (saved) {
			prefsApi.apply(saved);
		} else {
			save(defaults);
		}
		dialog.close();
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
