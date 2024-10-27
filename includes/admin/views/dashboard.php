<?php
/**
 * Dashboard page template.
 *
 * @package    RankMath
 * @subpackage RankMath_Monitor\Admin
 */

use RankMath_Monitor\Admin\Admin_Helper;
use RankMath_Monitor\Admin\System_Info;

$is_network_admin  = is_network_admin();
$is_network_active = RankMath_Monitor\Helper::is_plugin_active_for_network();
$current_tab       = $is_network_active && $is_network_admin ? 'help' : ( isset( $_GET['view'] ) ? $_GET['view'] : 'modules' );
?>
<div class="wrap rank-math-wrap limit-wrap">

	<span class="wp-header-end"></span>

	<h1><?php esc_html_e( 'Welcome to Rank Math!', '404-monitor' ); ?></h1>

	<div class="rank-math-text">
		<?php esc_html_e( 'The most complete WordPress SEO plugin to convert your website into a traffic generating machine.', '404-monitor' ); ?>
	</div>


	<?php
	if ( ! ( $is_network_active && $is_network_admin ) ) {
		rank_math_monitor()->admin->display_dashboard_nav();
	}
	?>

	<?php
	if ( $is_network_active && ! $is_network_admin && 'help' === $current_tab ) {
		return;
	}

	// phpcs:disable
	// Display modules activation and deactivation form.
	if ( 'modules' === $current_tab ) {
		rank_math_monitor()->manager->display_form();
	}
	// phpcs:enable
	?>
</div>
