<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMath_Monitor\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath_Monitor\Admin;

use RankMath_Monitor\Runner;
use RankMath_Monitor\Helper;
use RankMath_Monitor\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 *
 * @codeCoverageIgnore
 */
class Admin implements Runner {

	use Hooker;

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'wp_dashboard_setup', 'add_dashboard_widgets' );
		$this->action( 'admin_footer', 'rank_math_modal' );
	}

	/**
	 * Register dashboard widget.
	 */
	public function add_dashboard_widgets() {
		wp_add_dashboard_widget( 'rank_math_dashboard_widget', esc_html__( 'Rank Math', '404-monitor' ), [ $this, 'render_dashboard_widget' ] );
	}

	/**
	 * Render dashboard widget.
	 */
	public function render_dashboard_widget() {
		?>
		<div id="published-posts" class="activity-block">
			<?php $this->do_action( 'dashboard/widget' ); ?>
		</div>
		<?php
	}

	/**
	 * Display dashabord tabs.
	 */
	public function display_dashboard_nav() {
		$current = isset( $_GET['view'] ) ? $_GET['view'] : 'modules';
		?>
		<h2 class="nav-tab-wrapper">
			<?php
			foreach ( $this->get_nav_links() as $id => $link ) :
				if ( isset( $link['cap'] ) && ! current_user_can( $link['cap'] ) ) {
					continue;
				}
				?>
			<a class="nav-tab<?php echo $id === $current ? ' nav-tab-active' : ''; ?>" href="<?php echo esc_url( Helper::get_admin_url( $link['url'], $link['args'] ) ); ?>" title="<?php echo $link['title']; ?>"><?php echo $link['title']; ?></a>
			<?php endforeach; ?>
		</h2>
		<?php
	}

	/**
	 * Get dashbaord navigation links
	 *
	 * @return array
	 */
	private function get_nav_links() {
		$links = [
			'modules'       => [
				'url'   => '',
				'args'  => 'view=modules',
				'cap'   => 'manage_options',
				'title' => esc_html__( 'Modules', '404-monitor' ),
			],
			'help'          => [
				'url'   => 'help',
				'args'  => '',
				'cap'   => 'manage_options',
				'title' => esc_html__( 'Help', '404-monitor' ),
			],
			'import-export' => [
				'url'   => 'import-export',
				'args'  => '',
				'cap'   => 'manage_options',
				'title' => esc_html__( 'Import &amp; Export', '404-monitor' ),
			],
		];

		if ( Helper::is_plugin_active_for_network() ) {
			unset( $links['help'] );
		}

		return $links;
	}

	/**
	 * Activate Rank Math Modal.
	 */
	public function rank_math_modal() {
		$screen = get_current_screen();

		// Early Bail!
		if ( ! in_array( $screen->id, array( 'toplevel_page_rank-math-monitor', 'rank-math_page_rank-math-404-monitor', 'rank-math_page_rank-math-options-general' ) ) ) {
			return;
		}

		if ( file_exists( WP_PLUGIN_DIR . '/seo-by-rank-math' ) ) {
			$text = __( 'Activate Now', '404-monitor' );
			$path = 'seo-by-rank-math/rank-math.php';
			$link = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $path ), 'activate-plugin_' . $path );
		} else {
			$text = __( 'Install for Free', '404-monitor' );
			$link = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=seo-by-rank-math' ), 'install-plugin_seo-by-rank-math' );
		}

		// Scripts.
		rank_math_monitor()->admin_assets->enqueue_style( 'plugin-modal' );
		rank_math_monitor()->admin_assets->enqueue_script( 'plugin-modal' );

		?>
		<div class="rank-math-feedback-modal rank-math-ui" id="rank-math-feedback-form">
			<div class="rank-math-feedback-content">

				<?php /*<header>
					<h2>
						<?php echo __( 'Rank Math SEO Suite', '404-monitor' ); ?>
					</h2>
				</header> */ ?>

				<div class="plugin-card plugin-card-seo-by-rank-math">
					<span class="button-close dashicons dashicons-no-alt alignright"></span>
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a href="https://rankmath.com/wordpress/plugin/seo-suite/" target="_blank">
								<?php esc_html_e( 'WordPress SEO Plugin – Rank Math', '404-monitor' ); ?>
								<img src="https://ps.w.org/seo-by-rank-math/assets/icon.svg" class="plugin-icon" alt="<?php esc_html_e( 'Rank Math SEO', '404-monitor' ); ?>">
								</a>
								<span class="vers column-rating">
									<a href="https://wordpress.org/support/plugin/seo-by-rank-math/reviews/" target="_blank">
										<div class="star-rating"><span class="screen-reader-text"><?php esc_html_e( '5.0 rating based on 162 ratings', '404-monitor' ); ?></span><div class="star star-full" aria-hidden="true"></div><div class="star star-full" aria-hidden="true"></div><div class="star star-full" aria-hidden="true"></div><div class="star star-full" aria-hidden="true"></div><div class="star star-full" aria-hidden="true"></div></div><span class="num-ratings" aria-hidden="true">(162)</span>
									</a>
								</span>
							</h3>
						</div>

						<div class="desc column-description">
							<p><?php esc_html_e( 'Rank Math is a revolutionary SEO plugin that combines the features of many SEO tools in a single package & helps you multiply your traffic.', '404-monitor' ); ?></p>
						</div>
					</div>

					<div class="plugin-card-bottom">
						<div class="column-compatibility">
							<span class="compatibility-compatible"><strong><?php esc_html_e( 'Compatible', '404-monitor' ); ?></strong> <?php esc_html_e( 'with your version of WordPress', '404-monitor' ); ?></span>
						</div>
						<a href="<?php echo $link; ?>" class="button button-primary install-button"><?php echo $text; ?></a>
					</div>
				</div>

			</div>

		</div>
		<?php
	}
}
