<?php
/**
 * Migration 301 safety net.
 *
 * Primary redirect handling should live in the Redirection plugin
 * (import migration/redirects.csv). This theme-level map is a fallback so no
 * legacy URL 404s even before that import — it fires only when nothing else
 * already redirected the request.
 *
 * Regenerate the map from the audit CSV with scratchpad/gen_redirects.py.
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

add_action( 'template_redirect', function () {
	// Let the Redirection plugin own redirects when it's active.
	if ( defined( 'REDIRECTION_VERSION' ) ) {
		return;
	}

	$map_file = STECH_DIR . '/migration/redirect-map.php';
	if ( ! is_readable( $map_file ) ) {
		return;
	}

	$req  = wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
	if ( ! $req ) {
		return;
	}
	$req  = trailingslashit( $req );
	$map  = require $map_file;

	foreach ( array( $req, untrailingslashit( $req ) ) as $key ) {
		if ( isset( $map[ $key ] ) ) {
			wp_safe_redirect( home_url( $map[ $key ] ), 301 );
			exit;
		}
	}
}, 1 );
