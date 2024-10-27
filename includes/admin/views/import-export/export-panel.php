<?php
/**
 * Export panel template.
 *
 * @package    RankMath
 * @subpackage RankMath_Monitor\Admin
 */

?>
<form class="rank-math-export-form cmb2-form" action="" method="post">

	<h3><?php esc_html_e( 'Export Settings', '404-monitor' ); ?></h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="status"><?php esc_html_e( 'Panels', '404-monitor' ); ?></label></th>
				<td>
					<ul class="cmb2-checkbox-list no-select-all cmb2-list">
						<li><input type="checkbox" class="cmb2-option" name="panels[]" id="status1" value="general" checked="checked"> <label for="status1"><?php esc_html_e( 'General Settings', '404-monitor' ); ?></label></li>
					</ul>
					<p class="description"><?php esc_html_e( 'Choose the panels to export.', '404-monitor' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>

	<footer>
		<input type="hidden" name="object_id" value="export-plz">
		<button type="submit" class="button button-primary button-xlarge"><?php esc_html_e( 'Export', '404-monitor' ); ?></button>
	</footer>

</form>
