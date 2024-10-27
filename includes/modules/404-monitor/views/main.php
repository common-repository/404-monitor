<?php
/**
 * Main template for 404 monitor
 *
 * @package    RankMath
 * @subpackage RankMath_Monitor\Monitor
 */

use RankMath_Monitor\Helper;

$monitor = rank_math_monitor()->monitor_admin;
?>
<div class="wrap rank-math-404-monitor-wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<style>
	#doaction, #doaction2 { margin: 0 }
	.column-times_accessed { width: 14% }
	.rank-math-clear-logs { color: #a00 !important; margin-bottom: 1px !important}
	.rank-math-clear-logs:hover { border-color: #a00 !important }
	</style>
	<p>
		<?php
		printf(
			/* Translators: 1: link to Monitor docs 2: link to Fix 404 docs */
			__( 'Find out where users are unable to find your content with the 404 monitor tool. You can also learn more about how to %1$s and %2$s with Rank Math.', '404-monitor' ),
			'<a href="https://rankmath.com/kb/wordpress-seo-plugin-rank-math/monitor-404-errors/" target="_blank">' . _x( 'monitor', 'in 404 monitor description', '404-monitor' ) . '</a>',
			'<a href="https://rankmath.com/kb/wordpress-seo-plugin-rank-math/fix-404-errors/" target="_blank">' . _x( 'fix 404s', 'in 404 monitor description', '404-monitor' ) . '</a>'
		);
		?>
	</p>
	<form method="post">
	<?php
		$monitor->table->prepare_items();
		$monitor->table->search_box( esc_html__( 'Search', '404-monitor' ), 's' );
		$monitor->table->display();
	?>
	</form>

</div>
