<?php
/**
 * This class registers all the necessary styles and scripts.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMath_Monitor\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath_Monitor\Admin;

use RankMath_Monitor\Runner;
use RankMath_Monitor\Traits\Hooker;
use RankMath_Monitor\Helper as GlobalHelper;

defined( 'ABSPATH' ) || exit;

/**
 * Assets class.
 *
 * @codeCoverageIgnore
 */
class Assets implements Runner {

	use Hooker;

	/**
	 *  Prefix for naming the assets.
	 */
	const PREFIX = 'rank-math-';

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'admin_enqueue_scripts', 'register' );
		$this->action( 'admin_enqueue_scripts', 'enqueue' );
	}

	/**
	 * Register styles and scripts required by plugin.
	 */
	public function register() {

		$js     = rank_math_monitor()->plugin_url() . 'assets/admin/js/';
		$css    = rank_math_monitor()->plugin_url() . 'assets/admin/css/';

		// Styles.
		wp_register_style( self::PREFIX . 'common', $css . 'common.css', null, rank_math_monitor()->version );
		wp_register_style( self::PREFIX . 'cmb2', $css . 'cmb2.css', null, rank_math_monitor()->version );
		wp_register_style( self::PREFIX . 'dashboard', $css . 'dashboard.css', array( 'rank-math-common' ), rank_math_monitor()->version );
		wp_register_style( self::PREFIX . 'plugin-modal', $css . 'modal.css', array( 'rank-math-common' ), rank_math_monitor()->version );

		// Scripts.
		wp_register_script( self::PREFIX . 'common', $js . 'common.js', array( 'jquery' ), rank_math_monitor()->version, true );
		wp_register_script( self::PREFIX . 'dashboard', $js . 'dashboard.js', array( 'jquery' ), rank_math_monitor()->version, true );
		wp_register_script( self::PREFIX . 'plugin-modal', $js . 'modal.js', array( 'jquery' ), rank_math_monitor()->version, true );

		/**
		 * Allow other plugins to register styles or scripts into admin after plugin assets.
		 */
		$this->do_action( 'admin/register_scripts' );
	}

	/**
	 * Enqueue Styles and Scripts required by plugin.
	 */
	public function enqueue() {
		$screen = get_current_screen();

		// Our screens only.
		if ( ! in_array( $screen->id, $this->get_admin_screen_ids() ) ) {
			return;
		}

		// Add thank you.
		$this->filter( 'admin_footer_text', 'admin_footer_text' );

		/**
		 * Allow other plugins to enqueue styles or scripts into admin after plugin assets.
		 */
		$this->do_action( 'admin/enqueue_scripts' );
	}

	/**
	 * Add footer credit on admin pages.
	 *
	 * @param string $text Default text for admin footer.
	 * @return string
	 */
	public function admin_footer_text( $text ) {
		/* translators: plugin url */
		return GlobalHelper::is_whitelabel() ? $text : '<em>' . sprintf( wp_kses_post( __( 'Thank you for using <a href="%s" target="_blank">Rank Math</a>', '404-monitor' ) ), 'https://rankmath.com/wordpress/plugin/seo-suite/' ) . '</em>';
	}

	/**
	 * Enqueues styles.
	 *
	 * @param string $style The name of the style to enqueue.
	 */
	public function enqueue_style( $style ) {
		wp_enqueue_style( self::PREFIX . $style );
	}

	/**
	 * Enqueues scripts.
	 *
	 * @param string $script The name of the script to enqueue.
	 */
	public function enqueue_script( $script ) {
		wp_enqueue_script( self::PREFIX . $script );
	}

	/**
	 * Get admin screen ids.
	 *
	 * @return array
	 */
	private function get_admin_screen_ids() {
		return array(
			'toplevel_page_rank-math',
			'rank-math_page_rank-math-404-monitor',
			'rank-math_page_rank-math-import-export',
			'rank-math_page_rank-math-help',
		);
	}
}
