<?php
/**
 * Admin helper Functions.
 *
 * This file contains functions need during the admin screens.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMath_Monitor\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath_Monitor\Admin;

use RankMath_Monitor\Helper as GlobalHelper;

defined( 'ABSPATH' ) || exit;

/**
 * Admin_Helper class.
 */
class Admin_Helper {

	/**
	 * Get tooltip html.
	 *
	 * @param  string $message Message to show in tooltip.
	 * @return string
	 */
	public static function get_tooltip( $message ) {
		return '<span class="rank-math-tooltip"><em class="dashicons-before dashicons-editor-help"></em><span>' . $message . '</span></span>';
	}

	/**
	 * Get admin view file.
	 *
	 * @param  string $view View filename.
	 * @return string Complete path to view
	 */
	public static function get_view( $view ) {
		return rank_math_monitor()->admin_dir() . "views/{$view}.php";
	}
}
