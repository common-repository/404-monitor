<?php
/**
 * The Import Export Class
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
use MyThemeShop\Admin\Page;
use MyThemeShop\Helpers\WordPress;

defined( 'ABSPATH' ) || exit;

/**
 * Import_Export class.
 */
class Import_Export implements Runner {

	use Hooker;

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'init', 'register_page', 1 );
	}

	/**
	 * Register admin pages for plugin.
	 */
	public function register_page() {
		$uri = rank_math_monitor()->plugin_url() . 'assets/admin/';
		new Page( 'rank-math-import-export', esc_html__( 'Import &amp; Export', '404-monitor' ), array(
			'position' => 99,
			'parent'   => 'rank-math-monitor',
			'render'   => Admin_Helper::get_view( 'import-export/main' ),
			'onsave'   => array( $this, 'handler' ),
			'classes'  => array( 'rank-math-page' ),
			'assets'   => array(
				'styles'  => array(
					'cmb2-styles'      => '',
					'rank-math-common' => '',
					'rank-math-cmb2'   => '',
				),
				'scripts' => array( 'rank-math-import-export' => $uri . 'js/import-export.js' ),
			),
		));

		GlobalHelper::add_json( 'importConfirm', esc_html__( 'Are you sure you want to import settings into Rank Math? Don\'t worry, your current configuration will be saved as a backup.', '404-monitor' ) );
	}

	/**
	 * Handle import or export.
	 */
	public function handler() {

		if ( ! isset( $_POST['object_id'] ) ) {
			return;
		}

		if ( 'export-plz' === $_POST['object_id'] ) {
			$this->export();
		}

		if ( isset( $_FILES['import-me'] ) && 'import-plz' === $_POST['object_id'] ) {
			$this->import();
		}
	}

	/**
	 * Handle export.
	 */
	private function export() {
		$panels   = $_POST['panels'];
		$data     = $this->get_export_data( $panels );
		$filename = '404-monitor-settings-' . date( 'Y-m-d-H-i-s' ) . '.json';

		header( 'Content-Type: application/txt' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Cache-Control: no-cache, no-store, must-revalidate' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		echo wp_json_encode( $data );
		exit;
	}

	/**
	 * Handle import.
	 */
	private function import() {

		// Handle file upload.
		$file = wp_handle_upload( $_FILES['import-me'], array( 'mimes' => array( 'json' => 'application/json' ) ) );
		if ( is_wp_error( $file ) ) {
			GlobalHelper::add_notification( esc_html__( 'Settings could not be imported:', '404-monitor' ) . ' ' . $file->get_error_message(), [ 'type' => 'error' ] );
			return false;
		}

		if ( is_array( $file ) && isset( $file['error'] ) ) {
			GlobalHelper::add_notification( esc_html__( 'Settings could not be imported:', '404-monitor' ) . ' ' . $file['error'], [ 'type' => 'error' ] );
			return false;
		}

		if ( ! isset( $file['file'] ) ) {
			GlobalHelper::add_notification( esc_html__( 'Settings could not be imported:', '404-monitor' ) . ' ' . esc_html__( 'Upload failed.', '404-monitor' ), [ 'type' => 'error' ] );
			return false;
		}

		// Parse Options.
		$wp_filesystem = WordPress::get_filesystem();
		$settings      = $wp_filesystem->get_contents( $file['file'] );
		$settings      = json_decode( $settings, true );

		\unlink( $file['file'] );

		if ( $this->do_import_data( $settings ) ) {
			GlobalHelper::add_notification( esc_html__( 'Settings successfully imported.', '404-monitor' ), 'success' );
			return;
		}

		GlobalHelper::add_notification( esc_html__( 'No settings found to be imported.', '404-monitor' ), [ 'type' => 'info' ] );
	}

	/**
	 * Does import data.
	 *
	 * @param  array $data           Import data.
	 * @param  bool  $suppress_hooks Suppress hooks or not.
	 * @return bool
	 */
	private function do_import_data( array $data, $suppress_hooks = false ) {
		$down = false;
		$hash = array(
			'general' => 'rank-math-monitor-general',
		);

		$this->run_import_hooks( 'pre_import', $data, $suppress_hooks );

		foreach ( $hash as $key => $option_key ) {
			if ( isset( $data[ $key ] ) && ! empty( $data[ $key ] ) ) {
				$down = true;
				update_option( $option_key, $data[ $key ] );
			}
		}

		$this->run_import_hooks( 'after_import', $data, $suppress_hooks );

		return $down;
	}

	/**
	 * Run import hooks
	 *
	 * @param string $hook     Hook to fire.
	 * @param array  $data     Import data.
	 * @param bool   $suppress Suppress hooks or not.
	 */
	private function run_import_hooks( $hook, $data, $suppress ) {
		if ( ! $suppress ) {
			/**
			 * Fires while importing settings.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data Import data.
			 */
			$this->do_action( 'importers/settings/' . $hook, $data );
		}
	}

	/**
	 * Gets export data.
	 *
	 * @param array $panels Which panels do you want to export. It will export all panels if this param is empty.
	 * @return array
	 */
	private function get_export_data( array $panels = array() ) {
		if ( ! $panels ) {
			$panels = array( 'general' );
		}

		$settings = rank_math_monitor()->settings->all_raw();

		foreach ( $panels as $panel ) {
			if ( isset( $settings[ $panel ] ) ) {
				$data[ $panel ] = $settings[ $panel ];
			}
		}

		return $data;
	}
}
