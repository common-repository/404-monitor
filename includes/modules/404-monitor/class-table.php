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
use MyThemeShop\Admin\List_Table;

defined( 'ABSPATH' ) || exit;

/**
 * Table class.
 */
class Table extends List_Table {

	/**
	 * The Constructor.
	 */
	public function __construct() {

		parent::__construct( array(
			'singular' => esc_html__( 'event', '404-monitor' ),
			'plural'   => esc_html__( 'events', '404-monitor' ),
			'no_items' => esc_html__( 'The 404 error log is empty.', '404-monitor' ),
		) );

	}

	/**
	 * Prepares the list of items for displaying.
	 */
	public function prepare_items() {
		global $per_page;

		$per_page = $this->get_items_per_page( 'rank_math_404_monitor_per_page' );
		$search   = $this->get_search();

		$data = DB::get_logs( array(
			'limit'   => $per_page,
			'order'   => $this->get_order(),
			'orderby' => $this->get_orderby( 'accessed' ),
			'paged'   => $this->get_pagenum(),
			'search'  => $search ? $search : '',
		) );

		$this->items = $data['logs'];

		foreach ( $this->items as $i => $item ) {
			$this->items[ $i ]['uri_decoded'] = urldecode( $item['uri'] );
		}

		$this->set_pagination_args( array(
			'total_items' => $data['count'],
			'per_page'    => $per_page,
		) );
	}

	/**
	 * Extra controls to be displayed between bulk actions and pagination.
	 *
	 * @param string $which Where to show nav.
	 */
	protected function extra_tablenav( $which ) {
		if ( empty( $this->items ) ) {
			return;
		}
		?>
		<div class="alignleft actions">
			<input type="button" class="button action rank-math-clear-logs" value="<?php esc_attr_e( 'Clear Log', '404-monitor' ); ?>">
		</div>
		<?php
	}

	/**
	 * Handles the checkbox column output.
	 *
	 * @param object $item The current item.
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="log[]" value="%s" />', $item['id']
		);
	}

	/**
	 * Handles the default column output.
	 *
	 * @param object $item        The current item.
	 * @param string $column_name The current column name.
	 */
	public function column_default( $item, $column_name ) {

		if ( 'uri' === $column_name ) {
			return $item['uri_decoded'] . $this->column_actions( $item );
		}

		if ( 'referer' === $column_name ) {
			return '<a href="' . $item['referer'] . '" target="_blank">' . $item['referer'] . '</a>';
		}

		if ( in_array( $column_name, array( 'times_accessed', 'accessed', 'user_agent' ) ) ) {
			return $item[ $column_name ];
		}

		return print_r( $item, true );
	}

	/**
	 * Generate row actions div.
	 *
	 * @param object $item The current item.
	 */
	public function column_actions( $item ) {
		$actions = array();

		$actions['redirect'] = sprintf(
			'<a href="#" class="rank-math-404-redirect-btn">%s</a>',
			esc_html__( 'Redirect', '404-monitor' )
		);

		$actions['delete'] = sprintf(
			'<a href="%s" class="rank-math-404-delete">' . esc_html__( 'Delete', '404-monitor' ) . '</a>',
			Helper::get_admin_url( '404-monitor', array(
				'action'   => 'delete',
				'log'      => $item['id'],
				'security' => wp_create_nonce( '404_delete_log' ),
			) )
		);

		return $this->row_actions( $actions );
	}

	/**
	 * Get a list of columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'             => '<input type="checkbox" />',
			'uri'            => esc_html__( 'URI', '404-monitor' ),
			'referer'        => esc_html__( 'Referer', '404-monitor' ),
			'user_agent'     => esc_html__( 'User-Agent', '404-monitor' ),
			'times_accessed' => esc_html__( 'Hits', '404-monitor' ),
			'accessed'       => esc_html__( 'Access Time', '404-monitor' ),
		);

		if ( 'simple' == Helper::get_settings( 'general.404_monitor_mode' ) ) {
			unset( $columns['referer'], $columns['user_agent'] );
			return $columns;
		}

		unset( $columns['times_accessed'] );
		return $columns;
	}

	/**
	 * Get a list of sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'uri'            => array( 'uri', false ),
			'times_accessed' => array( 'times_accessed', false ),
			'accessed'       => array( 'accessed', false ),
		);
	}

	/**
	 * Get an associative array ( option_name => option_title ) with the list
	 * of bulk actions available on this table.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'delete' => esc_html__( 'Delete', '404-monitor' ),
		);

		return $actions;
	}
}
