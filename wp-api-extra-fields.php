<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/georgepercic
 * @since             1.0.0
 * @package           Wp_Api_Extra_Fields
 *
 * @wordpress-plugin
 * Plugin Name:       WP API Extra Fields
 * Plugin URI:        https://github.com/georgepercic/wp-api-extra-fields
 * Description:       A simple plugin to modify the response of the REST API plugin.
 * Version:           1.0.0
 * Author:            George Percic
 * Author URI:        https://github.com/georgepercic
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-api-extra-fields
 * Domain Path:       /languages
 */

add_action( 'rest_api_init', 'wp_add_custom_rest_fields' );

/**
 * Function for registering custom fields
 */
function wp_add_custom_rest_fields() {
    // schema for the wp_author_name field
    $wp_author_name_schema = array(
        'description'   => 'Name of the post author',
        'type'          => 'string',
        'context'       =>   array( 'view' )
    );

    // registering the wp_author_name field
    register_rest_field(
        'post',
        'wp_author_name',
        array(
            'get_callback'      => 'wp_get_author_name',
            'update_callback'   => null,
            'schema'            => $wp_author_name_schema
        )
    );

    // schema for the wp_post_categories field
    $wp_category_name_schema = array(
        'description'   => 'Name of the post category',
        'type'          => 'array',
        'context'       =>   array( 'view' )
    );

    // registering the wp_post_categories field
    register_rest_field(
        'post',
        'wp_post_categories',
        array(
            'get_callback'      => 'wp_get_category_name',
            'update_callback'   => null,
            'schema'            => $wp_category_name_schema
        )
    );

    // schema for the wp_media_src field
    $wp_media_src_schema = array(
        'description'   => 'Name of the post category',
        'type'          => 'array',
        'context'       =>   array( 'view' )
    );

    // registering the wp_media_src field
    register_rest_field(
        'post',
        'wp_media_src',
        array(
            'get_callback'      => 'wp_get_media_src',
            'update_callback'   => null,
            'schema'            => $wp_media_src_schema
        )
    );
}

/**
 * Callback for retrieving author name
 * @param  array            $object         The current post object
 * @param  string           $field_name     The name of the field
 * @param  WP_REST_request  $request        The current request
 * @return string                           The name of the author
 */
function wp_get_author_name( $object, $field_name, $request ) {
    return get_the_author_meta( 'display_name', $object['author'] );
}

/**
 * Callback for retrieving category name
 * @param  array            $object         The current post object
 * @param  string           $field_name     The name of the field
 * @param  WP_REST_request  $request        The current request
 * @return string                           The name of the category
 */
function wp_get_category_name( $object, $field_name, $request ) {
    $category = get_the_category($object['id']);
    $response = [];
    foreach ($category as $cat ) {
        $response[$cat->cat_ID] = [
            'name' => $cat->name,
            'url'  => get_category_link( $cat->cat_ID )
        ];
    }

    return $response;
}

/**
 * Callback for retrieving media src
 * @param  array            $object         The current post object
 * @param  string           $field_name     The name of the field
 * @param  WP_REST_request  $request        The current request
 * @return string                           The attached media src
 */
function wp_get_media_src( $object, $field_name, $request ) {
    return wp_get_attachment_image_url($object['featured_media']);
}
