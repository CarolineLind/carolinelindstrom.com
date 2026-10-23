<?php
/* --------------------------------------------------
 * ------------------ PREFERENCES -------------------
 * -------------------------------------------------- */

/*
The visitor's choices for appearance, motion and third-party content.
Asked in a dialog on the first visit (template-parts/preferences/dialog.php),
saved in the browser, and applied before the page is drawn.
Nothing moves and nothing from other sites loads until a choice is made.
*/

/*
Shared settings for the head script and assets/js/preferences.js.
Raise the version when the choices change (e.g. statistics is added):
every visitor is then asked again. Choices also expire after max_age_days.
*/
function clind_prefs_config() {
	return array(
		'key'        => 'clind-prefs',
		'version'    => 1,
		'maxAgeDays' => 365,
	);
}

/*
Runs in <head> before anything is painted, so the page never flashes the wrong
colors or starts moving. Also used by preferences.js through window.clindPrefs.
	data-theme:  light | dark
	data-motion: on | off (off until the visitor has chosen)
*/
function clind_prefs_head_script() {
	?>
	<script>
	(function () {
		var config = <?php echo wp_json_encode( clind_prefs_config() ); ?>;
		var root = document.documentElement;

		function matches(query) {
			return !!(window.matchMedia && window.matchMedia(query).matches);
		}

		/* Saved choices, or null if none, outdated or unreadable */
		function read() {
			try {
				var prefs = JSON.parse(localStorage.getItem(config.key));
				var age = Date.now() - Date.parse(prefs.saved);
				if (prefs.version === config.version && age < config.maxAgeDays * 864e5) {
					return prefs;
				}
			} catch (e) {}
			return null;
		}

		/* "system" follows the device; no choice yet means motion off */
		function apply(prefs) {
			var theme = prefs && prefs.theme !== 'system' ? prefs.theme : (matches('(prefers-color-scheme: light)') ? 'light' : 'dark');
			var motion = 'off';
			if (prefs) {
				motion = prefs.motion !== 'system' ? prefs.motion : (matches('(prefers-reduced-motion: reduce)') ? 'off' : 'on');
			}
			root.setAttribute('data-theme', theme);
			root.setAttribute('data-motion', motion);
		}

		window.clindPrefs = { config: config, read: read, apply: apply };
		apply(read());
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'clind_prefs_head_script', 0 );

function clind_prefs_enqueue_script() {
	$path = 'assets/js/preferences.js';
	wp_enqueue_script(
		'clind-preferences',
		get_template_directory_uri() . '/' . $path,
		array(),
		clind_asset_version( $path ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'clind_prefs_enqueue_script' );
