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
Below that, a fallback that lets the doodles follow the scroll (SCROLL).
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

	/* --------------------- SCROLL --------------------- */

	/*
	The doodles and the portrait follow the scroll (hero-shapes.css,
	start-hero-scroll.css) by three numbers on the hero: --p, --pp and --pb.
	Browsers with scroll-driven animations set them in CSS. Elsewhere this
	writes them, at most once per frame, with the same ranges, and only with
	motion on (with motion off the CSS ignores them).
	*/
	var scrollDriven = window.CSS && CSS.supports('animation-timeline: view()');
	var photo = hero.querySelector('.start-hero__photo');

	if (scrollDriven) {
		return;
	}

	var clamp = function (value) {
		return Math.max(0, Math.min(1, value)).toFixed(4);
	};

	var scrollFrame = 0;

	var measure = function () {
		scrollFrame = 0;
		var h = window.innerHeight;
		var box = hero.getBoundingClientRect();
		var span = Math.min(box.height, h);

		/* The page: 0 at the top, 1 after one screen height */
		hero.style.setProperty('--p', clamp(window.scrollY / h));

		/* The hero's bottom edge, like CSS's "exit" range */
		hero.style.setProperty('--pb', clamp((span - box.bottom) / span));

		/*
		The portrait, like CSS's "cover" range: 0 entering at the bottom, 1 leaving
		at the top. From its place in the layout (offsetTop), so its own movement
		doesn't feed back into the number
		*/
		if (photo) {
			var top = photo.offsetParent.getBoundingClientRect().top + photo.offsetTop;
			hero.style.setProperty('--pp', clamp((h - top) / (h + photo.offsetHeight)));
		}
	};

	var onScroll = function () {
		if (!scrollFrame) {
			scrollFrame = window.requestAnimationFrame(measure);
		}
	};

	var moving = false;

	var follow = function () {
		var should = root.getAttribute('data-motion') === 'on';
		if (should === moving) {
			return;
		}
		moving = should;
		if (moving) {
			window.addEventListener('scroll', onScroll, { passive: true });
			window.addEventListener('resize', onScroll);
			measure();
		} else {
			window.removeEventListener('scroll', onScroll);
			window.removeEventListener('resize', onScroll);
		}
	};

	document.addEventListener('clind:prefs', follow);
	follow();
})();
