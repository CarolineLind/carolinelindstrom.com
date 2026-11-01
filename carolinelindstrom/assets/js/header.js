/* --------------------------------------------------
 * --------------------- HEADER ---------------------
 * -------------------------------------------------- */

/*
The header's height, its buttons (the motion and light/dark toggles) and the mobile menu.
The toggles save through window.clindPrefs (inc/preferences.php), the same
choices as the preferences dialog. Without JavaScript the buttons are hidden,
the menu stays open and the header isn't sticky (assets/css/layout/header.css).
*/
(function () {
	'use strict';

	var root = document.documentElement;
	var prefsApi = window.clindPrefs;

	/* --------------------- HEIGHT --------------------- */

	/* --header-height for scroll-padding and the menu panel; it changes with text zoom and wrapping */
	var header = document.querySelector('.site-header');

	if (header && window.ResizeObserver) {
		new ResizeObserver(function () {
			root.style.setProperty('--header-height', Math.round(header.getBoundingClientRect().height) + 'px');
		}).observe(header);
	}

	/* -------------------- COMPACT --------------------- */

	/*
	Scrolling down makes the header compact and see-through, scrolling up brings
	it back (layout/header.css). Wobbles under 8px are ignored.
	*/
	if (header) {
		var lastY = window.scrollY;
		var ticking = false;

		var updateCompact = function () {
			var y = window.scrollY;
			if (y < 16 || lastY - y > 8) {
				header.classList.remove('site-header--compact');
				lastY = y;
			} else if (y - lastY > 8) {
				header.classList.add('site-header--compact');
				lastY = y;
			}
			ticking = false;
		};

		window.addEventListener('scroll', function () {
			if (!ticking) {
				ticking = true;
				requestAnimationFrame(updateCompact);
			}
		}, { passive: true });

		/* A reload in the middle of a page starts compact */
		if (lastY >= 16 && !root.classList.contains('header-expanding')) {
			header.classList.add('site-header--compact');
		}
	}

	/* --------------------- TOGGLES -------------------- */

	/* Their icon and text follow <html> through CSS (components/toggles.css), so there's nothing to sync here */
	var motionToggle = document.querySelector('[data-toggle="motion"]');
	var themeToggle = document.querySelector('[data-toggle="theme"]');

	/* Change one choice and keep the others as saved */
	function change(name, value) {
		var prefs = Object.assign({}, prefsApi.config.defaults, prefsApi.read());
		prefs[name] = value;
		prefsApi.save(prefs);
	}

	if (prefsApi && motionToggle && themeToggle) {
		motionToggle.addEventListener('click', function () {
			change('motion', root.getAttribute('data-motion') === 'on' ? 'off' : 'on');
		});

		themeToggle.addEventListener('click', function () {
			change('theme', root.getAttribute('data-theme') === 'light' ? 'dark' : 'light');
		});
	}

	/* ------------------ PAGE HANDOFF ------------------ */

	/*
	Following a menu link hands three things to the next page through sessionStorage,
	read in <head> by clind_handoff_script() in inc/enqueue.php:
	  menu:     the mobile menu was open, so the next page draws it open and slides it shut
	  progress: how far down this page was, so the progress bar counts down from there
	  from:     which link was current here, so weight and color (on phones the line) move over from it
	  header:   the header was compact, so the next page draws it compact and lets it grow back
	Not for links opened in a new tab or window.
	*/
	var menuButton = document.querySelector('.menu-btn');
	var nav = document.getElementById('site-nav');
	var progressBar = document.querySelector('.scroll-progress');

	if (nav) {
		nav.addEventListener('click', function (event) {
			var opensElsewhere = event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey;
			var scrollable = root.scrollHeight - window.innerHeight;
			var links = Array.prototype.slice.call(nav.querySelectorAll('.navlink'));

			if (!event.target.closest('a') || opensElsewhere) {
				return;
			}
			try {
				sessionStorage.setItem('clind-handoff', JSON.stringify({
					menu: !!menuButton && menuButton.getAttribute('aria-expanded') === 'true',
					progress: scrollable > 0 ? Math.min(1, window.scrollY / scrollable) : 0,
					from: links.findIndex(function (link) {
						return link.hasAttribute('aria-current');
					}),
					header: !!header && header.classList.contains('site-header--compact')
				}));
			} catch (e) {
				/* Storage blocked: the next page just starts as usual */
			}
		});
	}

	/*
	Wait until the page has been painted as it was, then let it move on: the open
	menu slides shut, the last page's link hands over to the current one and the
	compact header grows back
	*/
	if (root.matches('.menu-closing, .nav-handoff, .header-expanding')) {
		requestAnimationFrame(function () {
			requestAnimationFrame(function () {
				root.classList.remove('menu-closing', 'nav-handoff', 'header-expanding');
				root.removeAttribute('data-nav-from');
			});
		});
	}

	/* Once the progress bar has counted down, it follows the scroll again */
	if (root.classList.contains('progress-returning')) {
		var stopReturning = function () {
			root.classList.remove('progress-returning');
		};
		if (progressBar) {
			progressBar.addEventListener('animationend', stopReturning, { once: true });
		}
		/* In case the animation never runs */
		setTimeout(stopReturning, 1000);
	}

	/* ------------------- MOBILE MENU ------------------ */

	if (!menuButton || !nav) {
		return;
	}

	function setOpen(open) {
		menuButton.setAttribute('aria-expanded', String(open));
	}

	menuButton.addEventListener('click', function () {
		setOpen(menuButton.getAttribute('aria-expanded') !== 'true');
	});

	/* Back/forward can restore a page from the browser's cache with the menu still open */
	window.addEventListener('pageshow', function (event) {
		if (event.persisted) {
			setOpen(false);
		}
	});

	/* A tap outside the open menu closes it; focus stays where the tap put it */
	document.addEventListener('click', function (event) {
		if (menuButton.getAttribute('aria-expanded') === 'true' && !nav.contains(event.target) && !menuButton.contains(event.target)) {
			setOpen(false);
		}
	});

	/* Escape in the open menu closes it and returns focus to the button */
	document.addEventListener('keydown', function (event) {
		var inMenu = document.activeElement === menuButton || nav.contains(document.activeElement);
		if (event.key === 'Escape' && inMenu && menuButton.getAttribute('aria-expanded') === 'true') {
			setOpen(false);
			menuButton.focus();
		}
	});
})();
