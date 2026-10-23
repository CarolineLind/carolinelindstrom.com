<?php
/*
The preferences dialog: appearance, motion and third-party content.
Behavior in assets/js/preferences.js, settings in inc/preferences.php.
The pre-selected options are the defaults that Escape also saves:
follow the device for appearance and motion, no third-party content.
data-nosnippet keeps its text out of search results.
*/

$clind_privacy_url = get_privacy_policy_url();

$clind_groups = array(
	'theme'      => array(
		'legend'  => __( 'Utseende', 'carolinelindstrom' ),
		'help'    => '',
		'default' => 'system',
		'options' => array(
			'light'  => __( 'Ljust', 'carolinelindstrom' ),
			'dark'   => __( 'Mörkt', 'carolinelindstrom' ),
			'system' => __( 'Som min enhet', 'carolinelindstrom' ),
		),
	),
	'motion'     => array(
		'legend'  => __( 'Rörelse', 'carolinelindstrom' ),
		'help'    => __( 'Sidan har en del rörelse, som rullande text och effekter när du hovrar. Stäng av om rörelse gör dig yr eller stör dig.', 'carolinelindstrom' ),
		'default' => 'system',
		'options' => array(
			'on'     => __( 'På', 'carolinelindstrom' ),
			'off'    => __( 'Av', 'carolinelindstrom' ),
			'system' => __( 'Som min enhet', 'carolinelindstrom' ),
		),
	),
	'thirdParty' => array(
		'legend'  => __( 'Innehåll från andra sidor', 'carolinelindstrom' ),
		'help'    => __( 'Till exempel Spotify-spelaren. De sätter egna kakor och får din IP-adress.', 'carolinelindstrom' ),
		'default' => 'no',
		'options' => array(
			'yes' => __( 'Visa', 'carolinelindstrom' ),
			'no'  => __( 'Visa inte', 'carolinelindstrom' ),
		),
	),
);
?>
<dialog id="clind-prefs" class="prefs" aria-labelledby="clind-prefs-title" aria-describedby="clind-prefs-intro" data-nosnippet>
	<form class="prefs__form" method="dialog">
		<h2 id="clind-prefs-title" class="prefs__title" data-title-again="<?php esc_attr_e( 'Inställningar', 'carolinelindstrom' ); ?>"><?php esc_html_e( 'Innan du kliver in', 'carolinelindstrom' ); ?></h2>
		<p id="clind-prefs-intro" class="prefs__intro"><?php esc_html_e( 'Välj hur du vill uppleva sidan. Du kan ändra dig när som helst under Inställningar längst ner.', 'carolinelindstrom' ); ?></p>

		<?php foreach ( $clind_groups as $clind_name => $clind_group ) : ?>
			<?php $clind_help_id = 'clind-prefs-help-' . $clind_name; ?>
			<fieldset class="prefs__group"<?php echo $clind_group['help'] ? ' aria-describedby="' . esc_attr( $clind_help_id ) . '"' : ''; ?>>
				<legend class="prefs__legend"><?php echo esc_html( $clind_group['legend'] ); ?></legend>
				<div class="prefs__options">
					<?php foreach ( $clind_group['options'] as $clind_value => $clind_label ) : ?>
						<label class="prefs__option">
							<input type="radio" name="<?php echo esc_attr( $clind_name ); ?>" value="<?php echo esc_attr( $clind_value ); ?>"<?php checked( $clind_group['default'], $clind_value ); ?>>
							<span><?php echo esc_html( $clind_label ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
				<?php if ( $clind_group['help'] ) : ?>
					<p id="<?php echo esc_attr( $clind_help_id ); ?>" class="prefs__help">
						<?php echo esc_html( $clind_group['help'] ); ?>
						<?php if ( 'thirdParty' === $clind_name && $clind_privacy_url ) : ?>
							<a href="<?php echo esc_url( $clind_privacy_url ); ?>"><?php esc_html_e( 'Läs mer i integritetspolicyn', 'carolinelindstrom' ); ?></a>.
						<?php endif; ?>
					</p>
				<?php endif; ?>
			</fieldset>
		<?php endforeach; ?>

		<button type="submit" class="prefs__submit"><?php esc_html_e( 'Spara och gå vidare', 'carolinelindstrom' ); ?></button>
	</form>
</dialog>
<script>
	/* Open before the page is drawn on the first visit; preferences.js handles the rest */
	if (window.clindPrefs && !window.clindPrefs.read()) {
		var clindPrefsDialog = document.getElementById('clind-prefs');
		if (clindPrefsDialog.showModal) {
			clindPrefsDialog.showModal();
		}
	}
</script>
