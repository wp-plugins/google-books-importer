<?php

global $reftracknotif;
$options = get_option('reftrack_options');

?>
<div class="reftrack-container">
	<div class="reftrack-settings">
		<h2><span class="dashicons dashicons-admin-generic"></span> Reftrack Settings</h2>
		<?php $reftracknotif->displaymsg(); ?>
		<form method="post">
		<table class="form-table">
			<tr>
				<th scope="row"><label for="tracking_key">Tracking Key</label></th>
				<td><input name="reftrackoptions[tracking_key]" type="text" id="tracking_key" value="<?php echo esc_attr($options['tracking_key']); ?>" class="regular-text"></td>
				</tr>
			<tr>
			<tr>
				<th scope="row"><label for="cookie_length">Cookie Length</label></th>
				<td><input name="reftrackoptions[cookie_length]" type="text" id="cookie_length" value="<?php echo esc_attr($options['cookie_length']); ?>"></td>
				</tr>
			<tr>
		</table>
		<p><input type="submit" class="button button-primary" value="Save Changes"></p>
		<input type="hidden" name="reftracksave" value="savechanges">
		</form>
		
	</div>
</div>
