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

	/* How far the row has come: its scroll, or (pinned) where the page's scroll puts it */
	var rowX = function () {
		return pinned ? rowAt(window.scrollY - pinStart()) : track.scrollLeft;
	};

	/* The slide nearest the start; at the very end, the last one (it may never reach the start) */
	var nearest = function () {
		var x = rowX();
		var end = pinned ? travel : track.scrollWidth - track.clientWidth;
		if (x >= end - 2) {
			return slides.length - 1;
		}
		var best = 0;
		slides.forEach(function (slide, i) {
			if (Math.abs(offsetOf(slide) - x) < Math.abs(offsetOf(slides[best]) - x)) {
				best = i;
			}
		});
		return best;
	};

	var show = function (index) {
		current = index;
		slides.forEach(function (slide, i) {
			slide.classList.toggle('read-more__slide--current', i === index);
			/* Which way a click on it moves the row: sets the arrow pointer (read-more.css) */
			slide.classList.toggle('read-more__slide--before', i < index);
			slide.classList.toggle('read-more__slide--after', i > index);
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

	/* --- PINNED (prototype, read-more-pin.css) --- */

	/*
	Wide screens with motion on: if the carousel fits in the window, the section
	sticks and the page's scroll moves the row sideways. pinned: that mode is on;
	travel: how far the row can scroll sideways, which is also how much taller
	the section gets.
	*/
	var inner = section.querySelector('.read-more__inner');
	var wide = window.matchMedia('(width > 1000px)');
	var pinned = false;
	var travel = 0;
	var pinFrame = 0;

	/*
	The row doesn't start or stop at full speed: over the first and last stretch
	of the page's scroll (RAMP × the window's height) it speeds up from still and
	slows down to still, its speed following a smoothstep curve. Between the
	ramps it moves 1 px per px. The ramps cover half their length sideways, so
	the section is ramp px longer than the row's travel.
	*/
	var RAMP = 1;
	var ramp = 0;
	var length = 0;

	/*
	The page doesn't stop dead either. sticky alone would halt the section the
	instant it reaches its place; instead it sticks half a catch (CATCH × the
	window's height) lower, and over one catch of scrolling it glides the rest
	of the way up, slowing to still (translateY). Only then does the row start.
	At the end the same backwards, but sticky lets go as soon as the row stops
	and the translateY holds the inside back instead, less and less, so it
	starts slowly and speeds up to the page's speed. Letting go early means no
	gap is left under the inside: the section is one catch longer, not two.
	*/
	var CATCH = 0.5;
	var catchLength = 0;
	var shift = null;

	/* How far something has come after rising from still over y of a stretch `span` long (the integral of smoothstep) */
	var rise = function (y, span) {
		var t = y / span;
		return span * (t * t * t - t * t * t * t / 2);
	};

	/* Page scroll into the row's stretch → the row's scrollLeft */
	var rowAt = function (y) {
		y = Math.max(0, Math.min(length, y));
		if (y < ramp) {
			return rise(y, ramp);
		}
		if (y > length - ramp) {
			return travel - rise(length - y, ramp);
		}
		return ramp / 2 + y - ramp;
	};

	/*
	Page scroll since sticky caught the section → how far up to move its
	inside: speed 1 slowing to 0 over the first catch, still through the row's
	stretch. Then sticky has let go and the page moves it up at full speed, so
	the shift shrinks back to 0 to make it start from still instead.
	*/
	var shiftAt = function (u) {
		if (u <= 0) {
			return 0;
		}
		if (u < catchLength) {
			return u - rise(u, catchLength);
		}
		if (u < catchLength + length) {
			return catchLength / 2;
		}
		if (u < 2 * catchLength + length) {
			var t = u - catchLength - length;
			return catchLength / 2 - t + rise(t, catchLength);
		}
		return 0;
	};

	/* The other way round, for the buttons: which page scroll shows the row at x (it only ever grows, so halve) */
	var pageAt = function (x) {
		var low = 0;
		var high = length;
		for (var i = 0; i < 30; i++) {
			var mid = (low + high) / 2;
			if (rowAt(mid) < x) {
				low = mid;
			} else {
				high = mid;
			}
		}
		return high;
	};

	var headerHeight = function () {
		return parseFloat(getComputedStyle(root).getPropertyValue('--header-height')) || 0;
	};

	/* Where the inside sticks: centred under the header, plus half a catch */
	var stickAt = 0;

	/* The section's own top and bottom padding while pinned (read-more-pin.css) */
	var padTop = 0;
	var padBottom = 0;

	/* The page's scroll position where sticky catches the section (the inside's top, below the padding, reaches stickAt) */
	var catchStart = function () {
		return section.getBoundingClientRect().top + window.scrollY + padTop - stickAt;
	};

	/* The page's scroll position where the row starts moving: after the first catch */
	var pinStart = function () {
		return catchStart() + catchLength;
	};

	/*
	Browsers with scroll-driven animations move the inside and the row in CSS
	(read-more-pin.css), in step with the scroll: here they only get where each
	stretch starts and ends. JS that moves them lags a frame behind the scroll,
	which showed as a jolt where sticky lets go. Others (Firefox for now) get
	the JS below.
	*/
	var timeline = !!(window.CSS && CSS.supports('animation-timeline: view()'));

	/* The stretches as CSS custom properties, from where the section starts entering the window (the view timeline's "cover 0") */
	var setRanges = function () {
		var a = window.innerHeight + padTop - stickAt;
		var b = a + catchLength;
		var e = b + length;
		var stops = {
			'--rm-a': a,
			'--rm-b': b,
			'--rm-c': b + ramp,
			'--rm-d': e - ramp,
			'--rm-e': e,
			'--rm-f': e + catchLength,
			'--rm-catch': catchLength,
			'--rm-ramp': ramp,
			'--rm-travel': travel
		};
		Object.keys(stops).forEach(function (name) {
			section.style.setProperty(name, stops[name] + 'px');
		});
	};

	/* Inside and row follow the page */
	var sync = function () {
		if (!pinned) {
			return;
		}
		if (timeline) {
			follow();
			return;
		}
		var u = window.scrollY - catchStart();
		var next = -shiftAt(u);
		if (next !== shift) {
			shift = next;
			inner.style.transform = 'translateY(' + next + 'px)';
		}
		track.scrollLeft = rowAt(u - catchLength);
	};

	/* Try the pinned mode and keep it only if the inside (with its pinned padding) fits under the header */
	var layout = function () {
		var want = wide.matches && root.getAttribute('data-motion') === 'on';
		var room = window.innerHeight - headerHeight();
		/* Measure without the CSS motion: the row must be its own scroller for its travel */
		section.classList.remove('read-more--timeline');
		section.classList.toggle('read-more--pinned', want);
		if (want && inner.offsetHeight > room) {
			want = false;
			section.classList.remove('read-more--pinned');
		}
		pinned = want;
		if (pinned) {
			travel = track.scrollWidth - track.clientWidth;
			ramp = Math.min(travel, window.innerHeight * RAMP);
			length = travel + ramp;
			catchLength = window.innerHeight * CATCH;
			stickAt = headerHeight() + (room - inner.offsetHeight) / 2 + catchLength / 2;
			padTop = parseFloat(getComputedStyle(section).paddingTop) || 0;
			padBottom = parseFloat(getComputedStyle(section).paddingBottom) || 0;
			/* sticky holds the inside for one catch and the row's stretch, then lets go (the height includes the padding) */
			section.style.setProperty('--read-more-length', (padTop + inner.offsetHeight + catchLength + length + padBottom) + 'px');
			section.style.setProperty('--read-more-top', stickAt + 'px');
			shift = null;
			if (timeline) {
				inner.style.removeProperty('transform');
				track.scrollLeft = 0;
				setRanges();
				section.classList.add('read-more--timeline');
			}
			sync();
		} else {
			section.style.removeProperty('--read-more-length');
			section.style.removeProperty('--read-more-top');
			inner.style.removeProperty('transform');
			shift = null;
		}
	};

	window.addEventListener('scroll', function () {
		if (pinned && !pinFrame) {
			pinFrame = window.requestAnimationFrame(function () {
				pinFrame = 0;
				sync();
			});
		}
	}, { passive: true });

	/* Scroll to a slide. announce: say "Bild 2 av 4" (not when focus moved there, which is announced anyway) */
	var go = function (index, announce) {
		var target = Math.max(0, Math.min(slides.length - 1, index));
		heading = target;
		if (pinned) {
			/* Pinned: scroll the page to where the row shows that slide */
			window.scrollTo({ top: pinStart() + pageAt(Math.min(travel, offsetOf(slides[target]))), behavior: 'smooth' });
		} else {
			track.scrollTo({ left: offsetOf(slides[target]) });
		}
		show(target);
		if (status && announce !== false) {
			/* The words come from the template (data-text, translatable in PHP): "Bild %1$d av %2$d" */
			status.textContent = status.getAttribute('data-text').replace('%1$d', target + 1).replace('%2$d', slides.length);
		}
	};

	/* Keep the current slide in step with the row */
	function follow() {
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
	}

	/* While scrolling: keep the current slide in step, once per frame; open its book when it stops */
	track.addEventListener('scroll', function () {
		if (!frame) {
			frame = window.requestAnimationFrame(function () {
				frame = 0;
				follow();
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

	/*
	A click (or tap) on a slide that isn't current moves the row to it; its link
	ignores the pointer meanwhile (read-more.css). A click handler on a div,
	which CLAUDE.md otherwise rules out: it's only a pointer shortcut for
	"Föregående"/"Nästa", which are real buttons, and the keyboard and screen
	readers already reach every slide (decision 0008).
	*/
	track.addEventListener('click', function (event) {
		var index = slides.findIndex(function (slide) {
			return slide.contains(event.target);
		});
		if (index < 0) {
			return;
		}
		if (index !== current) {
			go(index);
			holdHover();
		} else if (section.classList.contains('read-more--no-hover')) {
			/* A click on the current slide while its hover is held back still follows its link */
			slides[index].querySelector('.read-more__link').click();
		}
	});

	/*
	After a click on a slide, the pointer is still over it when it becomes
	current, and its notebook would open while the row is still gliding (a jolt
	in the book). So the links ignore the pointer (read-more.css) until the row
	has stopped and the pointer moves again. Keyboard focus isn't affected.
	*/
	var lastMotion = 0;

	var noteMotion = function () {
		lastMotion = Date.now();
	};

	var holdHover = function () {
		noteMotion();
		section.classList.add('read-more--no-hover');
	};

	track.addEventListener('scroll', noteMotion, { passive: true });
	window.addEventListener('scroll', noteMotion, { passive: true });

	section.addEventListener('pointermove', function () {
		if (section.classList.contains('read-more--no-hover') && Date.now() - lastMotion > 200) {
			section.classList.remove('read-more--no-hover');
		}
	});

	section.addEventListener('pointerleave', function () {
		section.classList.remove('read-more--no-hover');
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

	document.addEventListener('clind:prefs', function () {
		open();
		layout();
	});
	touch.addEventListener('change', open);

	/* Sizes change with the window and once the fonts are in */
	var resizeFrame = 0;
	window.addEventListener('resize', function () {
		window.cancelAnimationFrame(resizeFrame);
		resizeFrame = window.requestAnimationFrame(layout);
	});
	if (document.fonts) {
		document.fonts.ready.then(layout);
	}

	show(0);
	layout();
})();
