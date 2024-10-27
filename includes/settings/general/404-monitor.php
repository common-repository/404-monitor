<?php
/**
 * 404 Monitor general settings.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMath_Monitor\Monitor
 * @author     Rank Math <support@rankmath.com>
 */

use RankMath_Monitor\Helper;

$cmb->add_field( array(
	'id'      => '404_advanced_monitor',
	'type'    => 'notice',
	'what'    => 'error',
	'content' => esc_html__( 'If you have hundreds of 404 errors, your error log might increase quickly. Only choose this option if you have a very few 404s and are unable to replicate the 404 error on a particular URL from your end.', '404-monitor' ),
	'dep'     => array( array( '404_monitor_mode', 'advanced' ) ),
) );

$cmb->add_field( array(
	'id'      => '404_monitor_mode',
	'type'    => 'radio_inline',
	'name'    => esc_html__( 'Mode', '404-monitor' ),
	'desc'    => esc_html__( 'The Simple mode only logs URI and access time, while the Advanced mode creates detailed logs including additional information such as the Referer URL.', '404-monitor' ),
	'options' => array(
		'simple'   => esc_html__( 'Simple', '404-monitor' ),
		'advanced' => esc_html__( 'Advanced', '404-monitor' ),
	),
	'default' => 'simple',
) );

$cmb->add_field( array(
	'id'         => '404_monitor_limit',
	'type'       => 'text',
	'name'       => esc_html__( 'Log Limit', '404-monitor' ),
	'desc'       => esc_html__( 'Sets the max number of rows in a log. Set to 0 to disable the limit.', '404-monitor' ),
	'default'    => '100',
	'attributes' => array( 'type' => 'number' ),
) );

$monitor_exclude = $cmb->add_field( array(
	'id'      => '404_monitor_exclude',
	'type'    => 'group',
	'name'    => esc_html__( 'Exclude Paths', '404-monitor' ),
	'desc'    => esc_html__( 'Enter URIs or keywords you wish to prevent from getting logged by the 404 monitor.', '404-monitor' ),
	'options' => array(
		'add_button'    => esc_html__( 'Add another', '404-monitor' ),
		'remove_button' => esc_html__( 'Remove', '404-monitor' ),
	),
	'classes' => 'cmb-group-text-only',
) );

$cmb->add_group_field( $monitor_exclude, array(
	'id'   => 'exclude',
	'type' => 'text',
) );
$cmb->add_group_field( $monitor_exclude, array(
	'id'      => 'comparison',
	'type'    => 'select',
	'options' => Helper::choices_comparison_types(),
) );

$cmb->add_field( array(
	'id'      => '404_monitor_ignore_query_parameters',
	'type'    => 'switch',
	'name'    => esc_html__( 'Ignore Query Parameters', '404-monitor' ),
	'desc'    => esc_html__( 'Turn ON to ignore all query parameters (the part after a question mark in a URL) when logging 404 errors.', '404-monitor' ),
	'default' => 'off',
) );
