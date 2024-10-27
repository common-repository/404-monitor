<?php
/**
 * The WordPress helpers.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMath_Monitor\Helpers
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath_Monitor\Helpers;

use RankMath_Monitor\Post;
use RankMath_Monitor\Term;
use RankMath_Monitor\User;
use RankMath_Monitor\Helper;
use MyThemeShop\Helpers\WordPress as WP_Helper;

defined( 'ABSPATH' ) || exit;

/**
 * WordPress class.
 */
trait WordPress {

	/**
	 * Whether the current user has a specific capability.
	 *
	 * @codeCoverageIgnore
	 * @see current_user_can()
	 *
	 * @param  string $capability Capability name.
	 * @return boolean Whether the current user has the given capability.
	 */
	public static function has_cap( $capability ) {
		return current_user_can( 'rank_math_' . str_replace( '-', '_', $capability ) );
	}

	/**
	 * Get admin url.
	 *
	 * @param  string $page Page id.
	 * @param  array  $args Pass arguments to query string.
	 * @return string
	 */
	public static function get_admin_url( $page = '', $args = array() ) {
		$page = $page ? 'rank-math-' . $page : 'rank-math-monitor';
		$args = wp_parse_args( $args, array( 'page' => $page ) );

		return add_query_arg( $args, admin_url( 'admin.php' ) );
	}

	/**
	 * Check if plugin is network active
	 *
	 * @codeCoverageIgnore
	 *
	 * @return boolean
	 */
	public static function is_plugin_active_for_network() {
		if ( ! is_multisite() ) {
			return false;
		}

		// Makes sure the plugin is defined before trying to use it.
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		if ( ! is_plugin_active_for_network( plugin_basename( RANK_MATH_MONITOR_FILE ) ) ) {
			return false;
		}

		return true;
	}
}
