/* --------------------------------------------------
 * ------------------- READ MORE --------------------
 * -------------------------------------------------- */

/*
The "Läs vidare" carousel (template-parts/front/read-more.php, components/read-more.css).
The slides are a row that scrolls and snaps on its own (swipe, trackpad, Tab);
this keeps the rest in step with it:
	which slide is current: its notebook isn't dimmed, its dot is marked,
	the counter shows it, and "Föregående"/"Nästa" are marked disabled at the ends
	the dots and arrows scroll to a slide (smoothly only with motion on, via CSS);
	a screen reader then hears "Bild 2 av 4"
	Tab into a slide that isn't current scrolls to it and makes it current
	on touch screens, where hover never happens, the current slide's notebook
	opens once the carousel is on screen and the scrolling has stopped
Loaded on the start page only (inc/enqueue.php). Without it the slides are a list.
*/
(function () {
	'use strict';

	var root = document.documentElement;
	var section = document.querySelector('.read-more');
	var track = section && section.querySelector('.read-more__slides');

	if (!track) {
		return;
	}

	var slides = Array.prototype.slice.call(track.querySelectorAll('.read-more__slide'));
	var dots = Array.prototype.slice.call(section.querySelectorAll('.read-more__dot'));
	var prev = section.querySelector('.read-more__step--prev');
	var next = section.querySelector('.read-more__step--next');
	var count = section.querySelector('.read-more__count');
	var status = section.querySelector('.read-more__status');
	var touch = window.matchMedia('(hover: none)');
	var current = 0;
	var inView = false;
	var frame = 0;
	var settle = 0;

	/*
	The slide a button click is scrolling to. While the row glides there, the
	scroll listener doesn't update the current slide: it would show each slide
	it passes, and the counter would jump back and forth (1, 2, 1, 2).
	*/
	var heading = -1;

	/* How far the row must scroll for a slide to sit at the start (after the gutter) */
	var offsetOf = function (slide) {
		var padding = parseFloat(getComputedStyle(track).scrollPaddingLeft) || 0;
		return slide.getBoundingClientRect().left - track.getBoundingClientRect().left + track.scrollLeft - padding;
	};

	/* The slide nearest the start; at the very end, the last one (it may never reach the start) */
	var nearest = function () {
		if (track.scrollLeft >= track.scrollWidth - track.clientWidth - 2) {
			return slides.length - 1;
		}
		var best = 0;
		slides.forEach(function (slide, i) {
			if (Math.abs(offsetOf(slide) - track.scrollLeft) < Math.abs(offsetOf(slides[best]) - track.scrollLeft)) {
				best = i;
			}
		});
		return best;
	};

	var show = function (index) {
		current = index;
		slides.forEach(function (slide, i) {
			slide.classList.toggle('read-more__slide--current', i === index);
		});
		/*
	Keyboard focus moving into a slide that isn't current makes it current. The
	browser only scrolls to a focused element that's out of view, and the next
	slide peeks in at the edge, so on its own it would stay faded.
	*/
	track.addEventListener('focusin', function (event) {
		var index = slides.findIndex(function (slide) {
			return slide.contains(event.target);
		});
		if (index >= 0 && index !== current) {
			go(index, false);
		}
	});

	dots.forEach(function (dot, i) {
			if (i === index) {
				dot.setAttribute('aria-current', 'true');
			} else {
				dot.removeAttribute('aria-current');
			}
		});
		if (count) {
			count.textContent = (index + 1) + ' / ' + slides.length;
		}
		if (prev && next) {
			prev.setAttribute('aria-disabled', String(index === 0));
			next.setAttribute('aria-disabled', String(index === slides.length - 1));
		}
	};

	/* Touch screens: open the current slide's notebook, close the others */
	var open = function () {
		var on = touch.matches && inView && root.getAttribute('data-motion') === 'on';
		slides.forEach(function (slide, i) {
			slide.classList.toggle('read-more__slide--open', on && i === current);
		});
	};

	/* Scroll to a slide. announce: say "Bild 2 av 4" (not when focus moved there, which is announced anyway) */
	var go = function (index, announce) {
		var target = Math.max(0, Math.min(slides.length - 1, index));
		heading = target;
		track.scrollTo({ left: offsetOf(slides[target]) });
		show(target);
		if (status && announce !== false) {
			/* The words come from the template (data-text, translatable in PHP): "Bild %1$d av %2$d" */
			status.textContent = status.getAttribute('data-text').replace('%1$d', target + 1).replace('%2$d', slides.length);
		}
	};

	/* While scrolling: keep the current slide in step, once per frame; open its book when it stops */
	track.addEventListener('scroll', function () {
		if (!frame) {
			frame = window.requestAnimationFrame(function () {
				frame = 0;
				var index = nearest();
				if (heading >= 0) {
					/* Gliding to a clicked slide: wait until it's there */
					if (index !== heading) {
						return;
					}
					heading = -1;
				}
				if (index !== current) {
					show(index);
					slides.forEach(function (slide) {
						slide.classList.remove('read-more__slide--open');
					});
				}
			});
		}
		window.clearTimeout(settle);
		settle = window.setTimeout(function () {
			/* Stopped: if a swipe interrupted the glide, follow where the row actually is */
			if (heading >= 0) {
				heading = -1;
				show(nearest());
			}
			open();
		}, 150);
	}, { passive: true });

	/*
	Keyboard focus moving into a slide that isn't current makes it current. The
	browser only scrolls to a focused element that's out of view, and the next
	slide peeks in at the edge, so on its own it would stay faded.
	*/
	track.addEventListener('focusin', function (event) {
		var index = slides.findIndex(function (slide) {
			return slide.contains(event.target);
		});
		if (index >= 0 && index !== current) {
			go(index, false);
		}
	});

	dots.forEach(function (dot, i) {
		dot.addEventListener('click', function () {
			go(i);
		});
	});

	if (prev && next) {
		prev.addEventListener('click', function () {
			go(current - 1);
		});
		next.addEventListener('click', function () {
			go(current + 1);
		});
	}

	/* The book opens once half the carousel is on screen, and closes when it leaves (plays again next time) */
	if (window.IntersectionObserver) {
		new IntersectionObserver(function (entries) {
			inView = entries[0].isIntersecting;
			open();
		}, { threshold: 0.5 }).observe(track);
	}

	document.addEventListener('clind:prefs', open);
	touch.addEventListener('change', open);
	show(0);
})();
