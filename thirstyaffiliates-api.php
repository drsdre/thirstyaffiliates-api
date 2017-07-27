<?php
/**
 * Plugin Name:     ThirstyAffiliates API
 * Plugin URI:      https://github.com/drsdre
 * Description:     Enable ThirstyAffiliates data for use in WP Core Rest API v2
 * Author:          Andre Schuurman
 * Author URI:      https://github.com/drsdre
 * Text Domain:     thirstyaffiliates-api
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         thirstyaffiliates-api
 */

/**
 * Add REST API support to thirstylink post type.
 */
add_action( 'init', 'thirstylink_post_enable_rest_api', 25 );
function thirstylink_post_enable_rest_api() {
	global $wp_post_types;

	$post_type_name = 'thirstylink';
	if ( isset( $wp_post_types[ $post_type_name ] ) ) {
		$wp_post_types[ $post_type_name ]->show_in_rest = true;
		$wp_post_types[ $post_type_name ]->rest_base    = $post_type_name;
		$wp_post_types[ $post_type_name ]->supports['custom-fields'] = true;

	}
}

/**
 * Add REST API support to thirstylink-category taxonomy type.
 */
add_action( 'init', 'thirstylink_category_enable_rest_api', 25 );
function thirstylink_category_enable_rest_api() {
	global $wp_taxonomies;

	$taxonomy_name = 'thirstylink-category';
	if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
		$wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;
		$wp_taxonomies[ $taxonomy_name ]->rest_base    = $taxonomy_name;

	}
}

/**
 * Add REST API support to thirstylink title and thirstyData fields.
 */
add_action( 'rest_api_init', function () {
	register_rest_field( 'thirstylink', 'title', [
		'get_callback'    => function ( $thirstylink_data ) {
			$thirstylink_obj = get_post( $thirstylink_data['id'] );

			return $thirstylink_obj->post_title;
		},
		'update_callback' => function ( $title, $thirstylink_obj ) {
			$ret = wp_update_post( [
				'id'         => $thirstylink_obj->ID,
				'post_title' => $title,
			] );
			if ( false === $ret ) {
				return new WP_Error( 'rest_thirstylink_title_failed', __( 'Failed to update title.' ),
					[ 'status' => 500 ] );
			}

			return true;
		},
		'schema'          => [
			'description' => __( 'Thirstylink title.' ),
			'type'        => 'string',
		],
	] );

	register_rest_field( 'thirstylink', 'thirstyData', [
		'get_callback'    => function ( $thirstylink_data ) {
			$thirstylink_thirstyData = get_post_meta( $thirstylink_data['id'], 'thirstyData', true );

			// Convert to json to prevent illegal programminging injections
			return json_encode( unserialize( $thirstylink_thirstyData ) );
		},
		'update_callback' => function ( $data, $thirstylink_obj ) {
			// Convert json back to serialized data
			$thirstylink_thirstyData = serialize( json_decode( $data ) );

			// If data is the same, skip updating
			// Prevents: "NOTE: If the meta_value passed to this function is the same as the value that is already in the database, this function returns false."
			if ( get_post_meta( $thirstylink_obj->ID, 'thirstyData', true ) == $thirstylink_thirstyData ) {
				return true;
			}

			// Update data
			$ret = update_post_meta( $thirstylink_obj->ID, 'thirstyData', $thirstylink_thirstyData);

			if ( false === $ret ) {
				return new WP_Error( 'rest_thirstylink_thirstyData_failed', __( 'Failed to update data.' ),
					[ 'status' => 500 ] );
			}

			return true;
		},
		'schema'          => [
			'description' => __( 'Thirstylink data.' ),
			'type'        => 'string',
		],
	] );
} );
