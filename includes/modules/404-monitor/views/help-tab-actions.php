<?php
/**
 * 404 Monitor inline help.
 *
 * @package    RankMath
 * @subpackage RankMath_Monitor\Monitor
 */

?>
<p>
	<?php esc_html_e( 'Hovering over a row in the list will display action links that allow you to manage the item. You can perform the following actions:', '404-monitor' ); ?>
</p>
<ul>
	<li><?php echo wp_kses_post( __( '<strong>View Details</strong> shows details about the 404 requests.', '404-monitor' ) ); ?></li>
	<li><?php echo wp_kses_post( __( '<strong>Delete</strong> permanently removes the item from the list.', '404-monitor' ) ); ?></li>
</ul>
