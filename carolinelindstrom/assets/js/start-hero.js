/* --------------------------------------------------
 * ------------------- START HERO -------------------
 * -------------------------------------------------- */

/*
The sheet of paper in the start page hero tilts toward the mouse
(components/start-hero.css). This only tells the CSS where the pointer is:
--nx and --ny on .start-hero, from -1 (left, top) to 1 (right, bottom).
The CSS turns them into the tilt, the shadow and the glare.
Only with motion on and a pointer that can hover (a mouse or trackpad): touch
has no pointer to follow. Pausing motion in the header lays the paper flat.
On touch screens the portrait's hover effect plays on scroll instead (PHOTO).
Loaded on the start page only (inc/enqueue.php).
*/
(function () {
	'use strict';

	var root = document.documentElement;
	var hero = document.querySelector('.start-hero');
	var mouse = window.matchMedia('(hover: hover) and (pointer: fine)');

	if (!hero) {
		return;
	}

	var on = false;
	var frame = 0;
	var pointer = null;

	/* At most once per frame, however often the mouse reports */
	var draw = function () {
		frame = 0;
		var box = hero.getBoundingClientRect();
		var nx = ((pointer.clientX - box.left) / box.width) * 2 - 1;
		var ny = ((pointer.clientY - box.top) / box.height) * 2 - 1;
		hero.style.setProperty('--nx', Math.max(-1, Math.min(1, nx)).toFixed(3));
		hero.style.setProperty('--ny', Math.max(-1, Math.min(1, ny)).toFixed(3));
	};

	var onMove = function (event) {
		pointer = event;
		if (!frame) {
			frame = window.requestAnimationFrame(draw);
		}
	};

	/* Back to flat; the CSS transition settles it */
	var flatten = function () {
		window.cancelAnimationFrame(frame);
		frame = 0;
		hero.style.removeProperty('--nx');
		hero.style.removeProperty('--ny');
	};

	var update = function () {
		var should = root.getAttribute('data-motion') === 'on' && mouse.matches;
		if (should === on) {
			return;
		}
		on = should;
		hero.classList.toggle('start-hero--tilt', on);
		if (on) {
			hero.addEventListener('mousemove', onMove, { passive: true });
			hero.addEventListener('mouseleave', flatten);
		} else {
			hero.removeEventListener('mousemove', onMove);
			hero.removeEventListener('mouseleave', flatten);
			flatten();
		}
	};

	/* The visitor's choices change (clindPrefs.save()), or a mouse is plugged in or out */
	document.addEventListener('clind:prefs', update);
	mouse.addEventListener('change', update);
	update();

	/* --------------------- PHOTO ---------------------- */

	/*
	On touch screens, where hover never happens, the colour shapes behind the
	portrait follow the scroll. Browsers with scroll-driven animations do it in
	CSS (start-hero.css, SCROLL). Elsewhere: apart while the portrait is above the
	line a third of the way up the screen and still in view, together when it is
	below that line or has scrolled out at the top. Same line as footer.js.
	*/
	var photo = hero.querySelector('.start-hero__photo');
	var touch = window.matchMedia('(hover: none)');
	var TRIGGER = 0.35;
	var scrollDriven = window.CSS && CSS.supports('animation-timeline: view()');

	if (!photo || scrollDriven || !window.IntersectionObserver) {
		return;
	}

	new IntersectionObserver(function (entries) {
		var on = entries[0].isIntersecting && touch.matches && root.getAttribute('data-motion') === 'on';
		photo.classList.toggle('start-hero__photo--apart', on);
	}, { rootMargin: '0px 0px -' + TRIGGER * 100 + '% 0px' }).observe(photo);
})();
