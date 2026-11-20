/* --------------------------------------------------
 * ------------------- ABOUT HERO -------------------
 * -------------------------------------------------- */

/*
The polaroids and doodles in the "Om mig" hero (components/about-hero.css):
	Drag: with a mouse, pen or finger. A polaroid or doodle follows the pointer,
	comes to the top of the pile and stays where it is let go, at most half off
	the hero's sides (so it can always be grabbed back).
	Only they take the touch (touch-action in the CSS), so a finger anywhere
	else scrolls the page.
	"Blanda bilderna": scatters the pile, the alternative to dragging for the
	keyboard and for single taps (docs/decisions/0012-about-hero.md).
Where a polaroid has been moved to is --dx and --dy on it (the CSS translate).
Loaded on Om mig only (inc/enqueue.php).
*/
(function () {
	'use strict';

	var hero = document.querySelector('.about-hero');
	var pile = hero && hero.querySelector('[data-about-pile]');

	if (!pile) {
		return;
	}

	var photos = Array.prototype.slice.call(pile.querySelectorAll('[data-about-photo]'));

	/* Everything that can be dragged: the polaroids and the doodles, back to front */
	var pieces = Array.prototype.slice.call(pile.querySelectorAll('[data-about-photo], .about-hero__doodle'));
	var shuffle = pile.querySelector('[data-about-shuffle]');
	var order = pieces.slice();
	var drag = null;
	var DRAG_OVERHANG = .5;

	var offset = function (piece, name) {
		return parseFloat(piece.style.getPropertyValue(name)) || 0;
	};

	var moveTo = function (piece, dx, dy) {
		piece.style.setProperty('--dx', Math.round(dx) + 'px');
		piece.style.setProperty('--dy', Math.round(dy) + 'px');
	};

	/*
	The one moved last lies on top. z-index from 4, in the order they were
	stacked (the DOM order), under the shuffle button (10), so nothing dragged
	ever covers the button
	*/
	var raise = function (piece) {
		order.splice(order.indexOf(piece), 1);
		order.push(piece);
		order.forEach(function (item, i) {
			item.style.zIndex = 4 + i;
		});
	};

	var clamp = function (value, min, max) {
		return Math.max(min, Math.min(max, value));
	};

	/* ---------------------- DRAG ---------------------- */

	/*
	How far a polaroid or doodle may be moved (its --dx and --dy) and stay inside the
	hero, measured from where it lies now. The box includes the tilt.
	overhang (0 to 1): how much of its width may go past the hero's sides,
	where the hero clips it (overflow-x: clip), so the page never scrolls sideways
	*/
	var limits = function (piece, overhang) {
		var box = piece.getBoundingClientRect();
		var area = hero.getBoundingClientRect();
		var dx = offset(piece, '--dx');
		var dy = offset(piece, '--dy');
		var out = box.width * (overhang || 0);

		return {
			dx: dx,
			dy: dy,
			left: dx + area.left - box.left - out,
			right: dx + area.right - box.right + out,
			up: dy + area.top - box.top,
			down: dy + area.bottom - box.bottom
		};
	};

	var onDown = function (event) {
		if (event.button !== 0) {
			return;
		}

		var piece = event.currentTarget;

		/* Keep the text from being selected and an image from being dragged out */
		event.preventDefault();
		piece.setPointerCapture(event.pointerId);
		piece.setAttribute('data-dragging', '');
		raise(piece);

		drag = {
			piece: piece,
			x: event.clientX,
			y: event.clientY,
			/* Up to half off the screen's side, with half left to grab it back by */
			room: limits(piece, DRAG_OVERHANG)
		};
	};

	var onMove = function (event) {
		if (!drag || event.currentTarget !== drag.piece) {
			return;
		}
		var room = drag.room;
		moveTo(
			drag.piece,
			clamp(room.dx + event.clientX - drag.x, room.left, room.right),
			clamp(room.dy + event.clientY - drag.y, room.up, room.down)
		);
	};

	var onUp = function (event) {
		if (!drag || event.currentTarget !== drag.piece) {
			return;
		}
		drag.piece.removeAttribute('data-dragging');
		drag = null;
	};

	pieces.forEach(function (piece) {
		piece.addEventListener('pointerdown', onDown);
		piece.addEventListener('pointermove', onMove);
		piece.addEventListener('pointerup', onUp);
		piece.addEventListener('pointercancel', onUp);
	});

	/* --------------------- SHUFFLE -------------------- */

	/*
	Each polaroid to a random spot near the pile, in the design's range
	(-100 to 30 px sideways, -55 to 55 px up and down at full size),
	scaled with the pile and kept inside the hero, and to the top in a random order
	*/
	if (shuffle) {
		shuffle.addEventListener('click', function () {
			var scale = pile.offsetWidth / (parseFloat(getComputedStyle(pile).getPropertyValue('--pile-w')) || 500);

			photos
				.slice()
				.sort(function () {
					return Math.random() - .5;
				})
				.forEach(function (photo) {
					var room = limits(photo);
					moveTo(
						photo,
						clamp((Math.random() * 130 - 100) * scale, room.left, room.right),
						clamp((Math.random() * 110 - 55) * scale, room.up, room.down)
					);
					raise(photo);
				});
		});
	}
})();
