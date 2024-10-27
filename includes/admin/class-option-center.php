<?php
/**
 * The option center of the plugin.
 *
 * @since      1.0.9
 * @package    RankMath
 * @subpackage RankMath_Monitor\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath_Monitor\Admin;

use RankMath_Monitor\CMB2;
use RankMath_Monitor\Helper;
use RankMath_Monitor\Runner;
use RankMath_Monitor\Traits\Hooker;
use MyThemeShop\Helpers\Arr;
use MyThemeShop\Helpers\WordPress;

defined( 'ABSPATH' ) || exit;

/**
 * Option_Center class.
 */
class Option_Center implements Runner {

	use Hooker;

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'init', 'register_general_settings', 125 );
	}

	/**
	 * General Settings.
	 */
	public function register_general_settings() {
		$tabs = [
			'404-monitor' => [
				'icon'  => 'dashicons dashicons-no',
				//'title' => esc_html__( '404 Monitor', '404-monitor' ), */
				/* translators: 1. Link to kb article 2. Link to redirection setting scree */
				'desc'  => sprintf( esc_html__( 'The 404 monitor lets you see the URLs where visitors and search engine crawlers run into 404 not found errors on your site. %1$s. Turn on %2$s too to redirect the faulty URLs easily.', '404-monitor' ), '<a href="https://rankmath.com/kb/rank-math-seo-plugin/general-settings/#404-monitor" target="_blank">' . esc_html__( 'Learn more', '404-monitor' ) . '</a>', '<a href="#" class="rank-math-404-redirect-btn" target="_blank">' . esc_html__( 'Redirections', '404-monitor' ) . '</a>' ),
			],
		];

		/**
		 * Allow developers to add new section into general setting option panel.
		 *
		 * @param array $tabs
		 */
		$tabs = $this->do_filter( 'settings/general', $tabs );

		new Options([
			'key'        => 'rank-math-options-general',
			'title'      => esc_html__( '404 Monitor', '404-monitor' ),
			'menu_title' => esc_html__( 'General Settings', '404-monitor' ),
			'folder'     => 'general',
			'tabs'       => $tabs,
		]);
	}
}
