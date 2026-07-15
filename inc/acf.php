<?php
/**
 * ACF integration: options pages, local JSON sync, Google Maps key,
 * and admin notices when required plugins are missing.
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

/**
 * Keep local JSON in the theme's acf-json/ (this is ACF's default, but we set
 * it explicitly so field groups always version-control cleanly).
 */
add_filter( 'acf/settings/save_json', function () {
	return STECH_DIR . '/acf-json';
} );
add_filter( 'acf/settings/load_json', function ( $paths ) {
	$paths[] = STECH_DIR . '/acf-json';
	return $paths;
} );

/**
 * Options pages drive global chrome (header, footer, utility bar, announcement)
 * and shared contact details. Requires ACF PRO.
 */
add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page( array(
		'page_title' => __( 'Theme Settings', 'stech' ),
		'menu_title' => __( 'Theme Settings', 'stech' ),
		'menu_slug'  => 'stech-settings',
		'capability' => 'edit_theme_options',
		'icon_url'   => 'dashicons-admin-customizer',
		'position'   => 59,
		'redirect'   => true,
	) );

	foreach ( array(
		'Global'     => __( 'Global (Header / Footer)', 'stech' ),
		'Announcement' => __( 'Announcement Bar', 'stech' ),
		'Contact'    => __( 'Contact & Locations', 'stech' ),
		'Scripts'    => __( 'Scripts & Analytics', 'stech' ),
	) as $title => $menu ) {
		acf_add_options_sub_page( array(
			'page_title'  => $title . ' ' . __( 'Settings', 'stech' ),
			'menu_title'  => $menu,
			'parent_slug' => 'stech-settings',
		) );
	}
} );

/**
 * Register the Google Maps API key for the locations block map field, if set
 * under Theme Settings › Scripts.
 */
add_action( 'acf/init', function () {
	if ( function_exists( 'acf_update_setting' ) && function_exists( 'get_field' ) ) {
		$key = get_field( 'google_maps_api_key', 'option' );
		if ( $key ) {
			acf_update_setting( 'google_api', array( 'key' => $key ) );
		}
	}
}, 20 );

/**
 * Nudge admins if the theme's hard dependencies aren't active.
 * The theme is designed to rely only on: ACF PRO, Gravity Forms, Yoast SEO.
 */
add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	$missing = array();
	if ( ! class_exists( 'ACF' ) ) {
		$missing[] = 'Advanced Custom Fields PRO';
	}
	if ( ! class_exists( 'GFForms' ) ) {
		$missing[] = 'Gravity Forms';
	}
	if ( ! $missing ) {
		return;
	}
	printf(
		'<div class="notice notice-error"><p><strong>%s</strong> %s <em>%s</em>.</p></div>',
		esc_html__( 'STECH theme:', 'stech' ),
		esc_html__( 'the following required plugins are not active —', 'stech' ),
		esc_html( implode( ', ', $missing ) )
	);
} );
