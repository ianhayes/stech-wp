<?php
/**
 * STECH theme bootstrap.
 *
 * Single standalone theme (no parent). Concerns are split into inc/ modules,
 * each self-contained and hooked on include. Keep this file a thin loader.
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

define( 'STECH_VERSION', '0.1.0' );
define( 'STECH_DIR', get_template_directory() );
define( 'STECH_URI', get_template_directory_uri() );
define( 'STECH_IMG', STECH_URI . '/src/img' );

/**
 * Load theme modules in dependency order.
 * post-types + blocks register things other modules reference, so load first.
 */
$stech_modules = array(
	'inc/setup.php',        // theme supports, menus, image sizes
	'inc/enqueue.php',      // fonts, compiled CSS/JS (front-end + editor)
	'inc/post-types.php',   // CPTs + taxonomies (program, event, job, staff, news)
	'inc/blocks.php',       // block.json auto-loader, ACF block category, allowed types
	'inc/acf.php',          // ACF options pages, JSON save/load path, dependency notices
	'inc/helpers.php',      // template helpers (get_links, svg, breadcrumb…)
);

foreach ( $stech_modules as $stech_module ) {
	$path = STECH_DIR . '/' . $stech_module;
	if ( is_readable( $path ) ) {
		require_once $path;
	}
}
unset( $stech_module, $path );
