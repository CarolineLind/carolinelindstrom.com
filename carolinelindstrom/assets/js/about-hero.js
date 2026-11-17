/* --------------------------------------------------
 * ------------------- ABOUT HERO -------------------
 * -------------------------------------------------- */

/*
The polaroids in the "Om mig" hero (components/about-hero.css):
	Drag: with a mouse, pen or finger. The polaroid follows the pointer, comes
	to the top of the pile and stays where it is let go, kept inside the hero.
	Only the polaroids take the touch (touch-action in the CSS), so a finger
	anywhere else scrolls the page.
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
	var shuffle = pile.querySelector('[data-about-shuffle]');
	var order = photos.slice();
	var drag = null;

	var offset = function (photo, name) {
		return parseFloat(photo.style.getPropertyValue(name)) || 0;
	};

	var moveTo = function (photo, dx, dy) {
		photo.style.setProperty('--dx', Math.round(dx) + 'px');
		photo.style.setProperty('--dy', Math.round(dy) + 'px');
	};

	/*
	The one moved last lies on top. z-index 4 to 6: over the face doodle (3),
	under the shuffle button (7), so a polaroid never covers the button
	*/
	var raise = function (photo) {
		order.splice(order.indexOf(photo), 1);
		order.push(photo);
		order.forEach(function (item, i) {
			item.style.zIndex = 4 + i;
		});
	};

	var clamp = function (value, min, max) {
		return Math.max(min, Math.min(max, value));
	};

	/* ---------------------- DRAG ---------------------- */

	/*
	How far the polaroid may be moved (its --dx and --dy) and stay inside the
	hero, measured from where it lies now. The box includes the tilt
	*/
	var limits = function (photo) {
		var box = photo.getBoundingClientRect();
		var area = hero.getBoundingClientRect();
		var dx = offset(photo, '--dx');
		var dy = offset(photo, '--dy');

		return {
			dx: dx,
			dy: dy,
			left: dx + area.left - box.left,
			right: dx + area.right - box.right,
			up: dy + area.top - box.top,
			down: dy + area.bottom - box.bottom
		};
	};

	var onDown = function (event) {
		if (event.button !== 0) {
			return;
		}

		var photo = event.currentTarget;

		/* Keep the text from being selected and an image from being dragged out */
		event.preventDefault();
		photo.setPointerCapture(event.pointerId);
		photo.setAttribute('data-dragging', '');
		raise(photo);

		drag = {
			photo: photo,
			x: event.clientX,
			y: event.clientY,
			room: limits(photo)
		};
	};

	var onMove = function (event) {
		if (!drag || event.currentTarget !== drag.photo) {
			return;
		}
		var room = drag.room;
		moveTo(
			drag.photo,
			clamp(room.dx + event.clientX - drag.x, room.left, room.right),
			clamp(room.dy + event.clientY - drag.y, room.up, room.down)
		);
	};

	var onUp = function (event) {
		if (!drag || event.currentTarget !== drag.photo) {
			return;
		}
		drag.photo.removeAttribute('data-dragging');
		drag = null;
	};

	photos.forEach(function (photo) {
		photo.addEventListener('pointerdown', onDown);
		photo.addEventListener('pointermove', onMove);
		photo.addEventListener('pointerup', onUp);
		photo.addEventListener('pointercancel', onUp);
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
