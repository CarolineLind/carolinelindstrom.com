/* --------------------------------------------------
 * --------------------- FOOTER ---------------------
 * -------------------------------------------------- */

/*
The footer's reveal and its animations, which play with motion on:
	the bottom bar is uncovered by "Här hittar du mig" sliding up (layout/footer.css);
	the logo in it writes itself in once it is uncovered (components/logo.css)
	on touch screens, where hover never happens: the email waves and the social
	pills fill with colour one by one when they are a third of the way up the
	screen, and stay filled while in view (contact.css, contact-socials.css)
Leaving the view (or being covered again) resets a part, so it plays again next time.
Without JavaScript the bar isn't sticky, and with motion off nothing moves.
*/
(function () {
	'use strict';

	var root = document.documentElement;
	var footer = document.querySelector('.site-footer');
	var contact = footer && footer.querySelector('.contact');
	var bar = footer && footer.querySelector('.site-footer__bar');

	if (!footer || !contact || !bar || !window.IntersectionObserver) {
		return;
	}

	var playing = function () {
		return root.getAttribute('data-motion') === 'on';
	};
	var touch = window.matchMedia('(hover: none)');

	/* --------------------- REVEAL --------------------- */

	/*
	The bar is only sticky while the footer is on screen. Higher up it would sit
	pinned behind the contact block anyway, out of sight, and the accessibility
	tests would count its text as covered. The switch happens while the bar is
	hidden either way, so nothing visibly moves.
	*/
	var setReveal = function (on) {
		footer.classList.toggle('site-footer--reveal', on && startsBelowFold());
	};

	/*
	Only on pages where the footer starts below the first screen: the reveal is
	the end of a scroll. On a short page (the 404) the bar would start out
	covered, so it simply stays in place.
	*/
	var startsBelowFold = function () {
		return footer.getBoundingClientRect().top + window.scrollY >= window.innerHeight;
	};

	/* How far down the window the contact block still covers (its bottom edge) */
	var curtain = function () {
		return contact.getBoundingClientRect().bottom;
	};

	/*
	A link in the bar that gets focus while covered: the browser counts it as on
	screen and won't scroll, so scroll to the end, where the bar is uncovered.
	*/
	bar.addEventListener('focusin', function (event) {
		if (curtain() > event.target.getBoundingClientRect().top) {
			window.scrollTo(0, root.scrollHeight);
		}
	});

	/* ---------------------- LOGO ---------------------- */

	/*
	The browser's visibility check can't tell that the bar is covered, so the
	logo is checked against the curtain while the footer is on screen: written
	when fully uncovered, reset when covered again or out of view.
	*/
	var logo = bar.querySelector('.site-footer__logo');
	var ticking = false;

	var checkLogo = function () {
		ticking = false;
		var box = logo.getBoundingClientRect();
		var uncovered = curtain() <= box.top && box.bottom <= window.innerHeight;
		var covered = curtain() >= box.bottom || box.top >= window.innerHeight;
		if (uncovered && playing()) {
			logo.classList.add('logo--write');
		} else if (covered) {
			logo.classList.remove('logo--write');
		}
	};

	var onScroll = function () {
		if (!ticking) {
			ticking = true;
			window.requestAnimationFrame(checkLogo);
		}
	};

	new IntersectionObserver(function (entries) {
		var inView = entries[0].isIntersecting;
		setReveal(inView);
		if (!logo) {
			return;
		}
		if (inView) {
			window.addEventListener('scroll', onScroll, { passive: true });
			onScroll();
		} else {
			window.removeEventListener('scroll', onScroll);
			logo.classList.remove('logo--write');
		}
	}).observe(footer);

	if (logo) {
		/* Lets the CSS hide the logo until it's written, now that something will write it */
		footer.classList.add('site-footer--animated');
	}

	/* --------------------- CONTACT -------------------- */

	/*
	Where a part starts to play: when its top has passed this far up from the
	bottom of the screen. A third of the way up, so it plays soon after it
	appears, but not the moment it peeks in at the edge.
	*/
	var TRIGGER = 0.35;

	var parts = new Map([
		[contact.querySelector('.contact__email'), 'contact__email--wave'],
		[contact.querySelector('.contact__socials'), 'contact__socials--filled']
	]);
	parts.delete(null);

	var play = function (element, on) {
		element.classList.toggle(parts.get(element), on && playing() && touch.matches);
	};

	/* The screen above the trigger line: a part plays once its top has passed it */
	var trigger = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				play(entry.target, true);
			}
		});
	}, { rootMargin: '0px 0px -' + TRIGGER * 100 + '% 0px' });

	/* Can the page scroll far enough to bring the element's top up to the trigger line? */
	var reachesTrigger = function (element) {
		var top = element.getBoundingClientRect().top + window.scrollY;
		return top <= root.scrollHeight - window.innerHeight * TRIGGER;
	};

	/* The whole screen: reset when out of view, and play parts that can't reach the line once fully in view */
	var view = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (!entry.isIntersecting) {
				play(entry.target, false);
			} else if (entry.intersectionRatio > 0.99 && !reachesTrigger(entry.target)) {
				play(entry.target, true);
			}
		});
	}, { threshold: [0, 1] });

	parts.forEach(function (className, element) {
		trigger.observe(element);
		view.observe(element);
	});
})();
