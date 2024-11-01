<?php
/**
 * The 404 module database operations
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMath_Monitor\Monitor
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath_Monitor\Monitor;

use RankMath_Monitor\Helper;
use MyThemeShop\Database\Database;

defined( 'ABSPATH' ) || exit;

/**
 * DB class.
 */
class DB {

	/**
	 * Get query builder.
	 *
	 * @return Query_Builder
	 */
	private static function table() {
		return Database::table( 'rank_math_404_logs' );
	}

	/**
	 * Get error logs.
	 *
	 * @param array $args Array of arguments.
	 * @return array
	 */
	public static function get_logs( $args ) {
		$args = wp_parse_args( $args, array(
			'orderby' => 'id',
			'order'   => 'DESC',
			'limit'   => 10,
			'paged'   => 1,
			'search'  => '',
			'ids'     => array(),
		) );

		$table = self::table()->found_rows()->page( $args['paged'] - 1, $args['limit'] );

		if ( ! empty( $args['search'] ) ) {
			$table->whereLike( 'uri', $args['search'] );
		}

		if ( ! empty( $args['ids'] ) ) {
			$table->whereIn( 'id', (array) $args['ids'] );
		}

		if ( ! empty( $args['orderby'] ) && in_array( $args['orderby'], array( 'id', 'uri', 'accessed', 'times_accessed' ) ) ) {
			$table->orderBy( $args['orderby'], $args['order'] );
		}

		return array(
			'logs'  => $table->get( ARRAY_A ),
			'count' => $table->get_found_rows(),
		);
	}

	/**
	 * Add a record.
	 *
	 * @param array $args Values to insert.
	 */
	public static function add( $args ) {
		$args = wp_parse_args( $args, array(
			'uri'            => '',
			'accessed'       => current_time( 'mysql' ),
			'times_accessed' => '1',
			'ip'             => '',
			'referer'        => '',
			'user_agent'     => '',
		));

		// Maybe delete logs if record exceed defined limit.
		$limit = absint( Helper::get_settings( 'general.404_monitor_limit' ) );
		if ( $limit && self::get_count() >= $limit ) {
			self::clear_logs();
		}

		return self::table()->insert( $args, array( '%s', '%s', '%d', '%s', '%s', '%s' ) );
	}

	/**
	 * Update a record.
	 *
	 * @param array $args Values to update.
	 */
	public static function update( $args ) {
		$row = self::table()->where( 'uri', $args['uri'] )->one( ARRAY_A );

		if ( $row ) {
			return self::update_counter( $row );
		}

		return self::add( $args );
	}

	/**
	 * Delete a record.
	 *
	 * @param  array $ids Array of ids to delete.
	 * @return int Number of records deleted.
	 */
	public static function delete_log( $ids ) {
		return self::table()->whereIn( 'id', (array) $ids )->delete();
	}

	/**
	 * Get total logs count.
	 *
	 * @return int
	 */
	public static function get_count() {
		return self::table()->selectCount()->getVar();
	}

	/**
	 * Clear logs completely.
	 *
	 * @return int
	 */
	public static function clear_logs() {
		return self::table()->truncate();
	}

	/**
	 * Get stats for dashboard widget.
	 *
	 * @return array
	 */
	public static function get_stats() {
		return self::table()->selectCount( '*', 'total' )->selectSum( 'times_accessed', 'hits' )->one();
	}

	/**
	 * Update if url is a matched and hit.
	 *
	 * @param  object $row Record to update.
	 * @return int|false The number of rows updated, or false on error.
	 */
	private static function update_counter( $row ) {
		$id = absint( $row['id'] );
		if ( 0 === $id ) {
			return false;
		}

		$update_data = array(
			'accessed'       => current_time( 'mysql' ),
			'times_accessed' => absint( $row['times_accessed'] ) + 1,
		);
		return self::table()->set( $update_data )->where( 'id', $id )->update();
	}
}
