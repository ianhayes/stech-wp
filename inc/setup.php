<?php
/**
 * Theme setup: supports, nav menus, image sizes.
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {

	load_theme_textdomain( 'stech', STECH_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'script', 'style', 'navigation-widgets' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );

	// The compiled editor stylesheet mirrors the front end so ACF blocks match.
	add_editor_style( 'public/css/editor-style.css' );

	// SVG-friendly logo (brand identity is vector).
	add_theme_support( 'custom-logo', array(
		'height'      => 48,
		'width'       => 180,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// Navigation locations map to the IA (primary + utility + footer columns).
	register_nav_menus( array(
		'primary'      => __( 'Primary Navigation', 'stech' ),
		'utility'      => __( 'Utility Bar (right side)', 'stech' ),
		'footer_1'     => __( 'Footer Column 1', 'stech' ),
		'footer_2'     => __( 'Footer Column 2', 'stech' ),
		'footer_3'     => __( 'Footer Column 3', 'stech' ),
		'legal'        => __( 'Footer Legal (bottom)', 'stech' ),
	) );
} );

/**
 * Named image sizes tuned to the block library (cards, heroes, avatars).
 * All cropped hard to keep grids tidy; heroes uncropped for art direction.
 */
add_action( 'after_setup_theme', function () {
	add_image_size( 'stech-hero', 2000, 1200, true );     // full-bleed heroes
	add_image_size( 'stech-card', 720, 480, true );        // program/news/video cards
	add_image_size( 'stech-card-tall', 720, 900, true );   // audience / bento tall
	add_image_size( 'stech-avatar', 320, 320, true );      // people / testimonial
	add_image_size( 'stech-logo-tile', 400, 240, false );  // partner / OAC logos
} );

add_filter( 'image_size_names_choose', function ( $sizes ) {
	return $sizes + array(
		'stech-hero'   => __( 'STECH Hero', 'stech' ),
		'stech-card'   => __( 'STECH Card', 'stech' ),
		'stech-avatar' => __( 'STECH Avatar', 'stech' ),
	);
} );

// Allow SVG uploads (brand assets) for admins/editors only, sanitised on the way in.
add_filter( 'upload_mimes', function ( $mimes ) {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
	}
	return $mimes;
} );
