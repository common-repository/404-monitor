<?php
/**
 * Plugin updates template.
 *
 * @package    RankMath
 * @subpackage RankMath_Monitor\Admin
 */

use RankMath_Monitor\Helper;

$current_version = rank_math_monitor()->version;
$latest_version  = '0.0.8';
$is_updateable   = version_compare( $current_version, $latest_version, '<' );
$class           = $is_updateable ? 'status-red' : 'status-green';
?>
<div class="rank-math-box <?php echo $class; ?>">

	<div class="rank-math-box--title">

		<h4><?php esc_html_e( 'Plugin Updates', '404-monitor' ); ?></h4>

		<span class="rank-math-box--title-button <?php echo $class; ?>"><?php echo $is_updateable ? esc_html__( 'Update Available', '404-monitor' ) : esc_html__( 'Plugin up to date', '404-monitor' ); ?></span>

	</div>

	<div class="rank-math-box--content">

		<strong><?php esc_html_e( 'Installed Version', '404-monitor' ); ?></strong><br /><?php echo $current_version; ?>
		<br /><br />
		<strong><?php esc_html_e( 'Latest Available Version', '404-monitor' ); ?></strong><br /><?php echo $latest_version; ?>
		<br /><br /><br />
		<a class="button" href="<?php echo esc_url( Helper::get_admin_url( '', 'checkforupdates=true' ) ); ?>"><?php esc_html_e( 'Check for Updates', '404-monitor' ); ?></a>
		<p>&nbsp;</p><p>&nbsp;</p>
	</div>

</div>
