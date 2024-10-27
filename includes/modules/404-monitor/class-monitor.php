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
use RankMath_Monitor\Traits\Hooker;
use RankMath_Monitor\Traits\Ajax;
use MyThemeShop\Helpers\Str;

defined( 'ABSPATH' ) || exit;

/**
 * Monitor class.
 */
class Monitor {

	use Hooker, Ajax;

	/**
	 * The Constructor.
	 */
	public function __construct() {

		if ( is_admin() ) {
			rank_math_monitor()->monitor_admin = new Admin;
		}

		if ( $this->is_ajax() ) {
			$this->ajax( 'delete_log', 'delete_log' );
		}

		$this->action( 'get_header', 'capture_404' );
	}

	/**
	 * Delete log.
	 */
	public function delete_log() {

		check_ajax_referer( '404_delete_log', 'security' );

		$id = isset( $_REQUEST['log'] ) ? $_REQUEST['log'] : 0;
		if ( ! $id ) {
			$this->error( esc_html__( 'No valid id found.', '404-monitor' ) );
		}

		DB::delete_log( $id );
		$this->success( esc_html__( 'Log successfully deleted.', '404-monitor' ) );
	}

	/**
	 * This function logs the request details when is_404().
	 */
	public function capture_404() {
		if ( ! is_404() ) {
			return;
		}

		$uri = untrailingslashit( Helper::get_current_page_url( Helper::get_settings( 'general.404_monitor_ignore_query_parameters' ) ) );
		$uri = str_replace( home_url( '/' ), '', $uri );

		// Check if excluded.
		if ( $this->is_url_excluded( $uri ) ) {
			return;
		}

		// Mode = simple.
		if ( 'simple' === Helper::get_settings( 'general.404_monitor_mode' ) ) {
			DB::update( array( 'uri' => $uri ) );
			return;
		}

		// Mode = advance.
		DB::add(
			array(
				'uri'        => $uri,
				'ip'         => ! empty( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '',
				'referer'    => ! empty( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '',
				'user_agent' => $this->get_user_agent(),
			)
		);
	}

	/**
	 * Is current url excluded
	 *
	 * @param  string $uri Check this uri for exclusion.
	 * @return boolean
	 */
	private function is_url_excluded( $uri ) {
		$excludes = Helper::get_settings( 'general.404_monitor_exclude' );
		if ( ! is_array( $excludes ) || empty( $excludes ) ) {
			return false;
		}

		foreach ( $excludes as $rule ) {
			if ( ! empty( $rule['exclude'] ) && Str::comparison( $rule['exclude'], $uri, $rule['comparison'] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get user-agent.
	 *
	 * @return string
	 */
	private function get_user_agent() {

		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return '';
		}

		$u_agent = '';
		$parsed  = $this->parse_user_agent( $_SERVER['HTTP_USER_AGENT'] );

		if ( ! empty( $parsed['browser'] ) ) {
			$u_agent .= $parsed['browser'];
		}
		if ( ! empty( $parsed['version'] ) ) {
			$u_agent .= ' ' . $parsed['version'];
		}

		return $u_agent;
	}

	/**
	 * Parses a user agent string into its important parts.
	 *
	 * @link https://github.com/donatj/PhpUserAgent
	 *
	 * @param string $u_agent User agent string to parse or null. Uses $_SERVER['HTTP_USER_AGENT'] on NULL.
	 * @return string[] an array with browser, version and platform keys
	 */
	private function parse_user_agent( $u_agent ) {
		if ( ! $u_agent ) {
			return array(
				'platform' => null,
				'browser'  => null,
				'version'  => null,
			);
		}

		$response = wp_remote_get( 'https://api.redirect.li/v1/useragent/' . urlencode( $u_agent ) );
		$response = wp_remote_retrieve_body( $response );
		$response = json_decode( $response, true );

		return array(
			'platform' => $response['os']['name'] . ' ' . $response['os']['version'],
			'browser'  => $response['browser']['name'],
			'version'  => $response['browser']['version'],
		);
	}
}
