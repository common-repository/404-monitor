<?php
/**
 * The 404 Monitor Module
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMath_Monitor\Monitor
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath_Monitor\Monitor;

use RankMath_Monitor\Helper;
use RankMath_Monitor\Module;
use MyThemeShop\Admin\Page;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\Arr;
use MyThemeShop\Helpers\WordPress;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 */
class Admin extends Module {

	/**
	 * The Constructor.
	 */
	public function __construct() {

		$directory = dirname( __FILE__ );
		$this->config(array(
			'id'             => '404-monitor',
			'directory'      => $directory,
			'table'          => 'RankMath_Monitor\Monitor\Table',
			'help'           => array(
				'title' => esc_html__( '404 Monitor', '404-monitor' ),
				'view'  => $directory . '/views/help.php',
			),
			'screen_options' => array(
				'id'      => 'rank_math_404_monitor_per_page',
				'default' => 100,
			),
		));
		parent::__construct();

		if ( $this->page->is_current_page() ) {
			$this->action( 'init', 'init' );
		}

		$this->action( 'rank_math_monitor/dashboard/widget', 'dashboard_widget', 11 );
	}

	/**
	 * Initialize.
	 */
	public function init() {
		$action = WordPress::get_request_action();
		if ( false === $action ) {
			return;
		}

		if ( ! check_admin_referer( 'bulk-events' ) ) {
			check_admin_referer( '404_delete_log', 'security' );
		}

		if ( 'delete' === $action && ! empty( $_REQUEST['log'] ) ) {
			$count = DB::delete_log( $_REQUEST['log'] );
			if ( $count > 0 ) {
				/* translators: delete counter */
				Helper::add_notification( sprintf( esc_html__( '%d log(s) deleted.', '404-monitor' ), $count ), [ 'type' => 'success' ] );
			}
			return;
		}

		if ( 'clear_log' === $action ) {

			$count = DB::get_count();
			DB::clear_logs();

			/* translators: delete counter */
			Helper::add_notification( sprintf( esc_html__( 'Log cleared - %d items deleted.', '404-monitor' ), $count ), [ 'type' => 'success' ] );
			return;
		}
	}

	/**
	 * Register admin page.
	 */
	public function register_admin_page() {

		$dir = $this->directory . '/views/';
		$uri = untrailingslashit( plugin_dir_url( __FILE__ ) );

		$this->page = new Page( 'rank-math-404-monitor', esc_html__( '404 Monitor', '404-monitor' ), array(
			'position' => 12,
			'parent'   => 'rank-math-monitor',
			'render'   => $dir . 'main.php',
			'classes'  => array( 'rank-math-page' ),
			'help'     => array(
				'404-overview'       => array(
					'title' => esc_html__( 'Overview', '404-monitor' ),
					'view'  => $dir . 'help-tab-overview.php',
				),
				'404-screen-content' => array(
					'title' => esc_html__( 'Screen Content', '404-monitor' ),
					'view'  => $dir . 'help-tab-screen-content.php',
				),
				'404-actions'        => array(
					'title' => esc_html__( 'Available Actions', '404-monitor' ),
					'view'  => $dir . 'help-tab-actions.php',
				),
				'404-bulk'           => array(
					'title' => esc_html__( 'Bulk Actions', '404-monitor' ),
					'view'  => $dir . 'help-tab-bulk.php',
				),
			),
			'assets'   => array(
				'styles'  => array( 'rank-math-common' => '' ),
				'scripts' => array( 'rank-math-404-monitor' => $uri . '/assets/404-monitor.js' ),
			),
		));

		if ( $this->page->is_current_page() ) {
			Helper::add_json( 'logConfirmClear', esc_html__( 'Are you sure you wish to delete all 404 error logs?', '404-monitor' ) );
			Helper::add_json( 'redirectionsUri', Helper::get_admin_url( 'redirections' ) );
		}
	}

	/**
	 * Add stats into admin dashboard
	 */
	public function dashboard_widget() {
		$data = DB::get_stats();
		?>
		<br />
		<h3><?php esc_html_e( '404 Monitor Stats', '404-monitor' ); ?></h3>
		<ul>
			<li><span><?php esc_html_e( '404 Monitor Log Count', '404-monitor' ); ?></span><?php echo Str::human_number( $data->total ); ?></li>
			<li><span><?php esc_html_e( '404 URI Hits', '404-monitor' ); ?></span><?php echo Str::human_number( $data->hits ); ?></li>
		</ul>
		<?php
	}
}
