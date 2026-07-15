<?php
/**
 * Asset loading: webfonts + compiled CSS/JS, front-end and editor.
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

/**
 * mtime cache-buster for a theme-relative built file.
 */
function stech_asset_ver( $rel ) {
	$path = STECH_DIR . '/' . ltrim( $rel, '/' );
	return file_exists( $path ) ? (string) filemtime( $path ) : STECH_VERSION;
}

/**
 * Webfonts. The approved design uses Bebas Neue (500), Roboto (300/400/500/700)
 * and Roboto Condensed (400/500/700) with display=swap.
 *
 * Loaded from Google Fonts to match the design 1:1. To self-host for
 * privacy/perf, drop woff2 files in src/fonts, filter 'stech_use_google_fonts'
 * to false, and enqueue a local @font-face sheet (see src/sass — TODO).
 */
function stech_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@300;400;500;700&family=Roboto+Condensed:wght@400;500;700&display=swap';
}

add_action( 'wp_enqueue_scripts', function () {

	if ( apply_filters( 'stech_use_google_fonts', true ) ) {
		add_action( 'wp_head', 'stech_font_preconnect', 1 );
		wp_enqueue_style( 'stech-fonts', stech_fonts_url(), array(), null );
	}

	wp_enqueue_style(
		'stech-app',
		STECH_URI . '/public/css/app.css',
		array( 'stech-fonts' ),
		stech_asset_ver( 'public/css/app.css' )
	);

	// blocks.js is vanilla + self-initialising; no jQuery dependency.
	wp_enqueue_script(
		'stech-app',
		STECH_URI . '/public/js/app.min.js',
		array(),
		stech_asset_ver( 'public/js/app.min.js' ),
		true
	);

	// Admin-provided custom CSS (Theme Settings › Scripts & Analytics).
	$custom_css = function_exists( 'get_field' ) ? get_field( 'custom_css', 'option' ) : '';
	if ( $custom_css ) {
		wp_add_inline_style( 'stech-app', wp_strip_all_tags( $custom_css ) );
	}
}, 20 );

/**
 * Preconnect hints so the font CSS + files resolve fast (CLS/LCP).
 */
function stech_font_preconnect() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}

/**
 * Editor: load the same fonts + compiled app.css so ACF blocks render 1:1.
 * (add_editor_style handles editor-style.css; fonts need explicit enqueue.)
 */
add_action( 'enqueue_block_editor_assets', function () {
	if ( apply_filters( 'stech_use_google_fonts', true ) ) {
		wp_enqueue_style( 'stech-fonts', stech_fonts_url(), array(), null );
	}
} );

/**
 * Ship the SVG favicon (brand mark) when the site has no custom Site Icon set.
 */
add_action( 'wp_head', function () {
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( STECH_IMG . '/shared/favicon.svg' ) );
}, 2 );
