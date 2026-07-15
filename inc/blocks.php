<?php
/**
 * Block system: a block.json auto-loader for ACF (v2) blocks.
 *
 * Convention (one folder per block):
 *   blocks/{slug}/block.json   → registration ("name": "stech/{slug}", acf.renderTemplate)
 *   blocks/{slug}/render.php    → markup; reads fields with get_field()
 *   acf-json/group_{slug}.json  → ACF field group, location block == acf/stech-{slug}
 *   src/sass/blocks/_{slug}.scss → styles (compiled into public/css/app.css)
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

/**
 * Custom editor category so all STECH blocks group together in the inserter.
 */
add_filter( 'block_categories_all', function ( $categories ) {
	array_unshift( $categories, array(
		'slug'  => 'stech',
		'title' => __( 'STECH Blocks', 'stech' ),
		'icon'  => null,
	) );
	return $categories;
} );

/**
 * Auto-register every block that has a blocks/{slug}/block.json.
 * ACF wires up rendering via the "acf" key inside each block.json.
 */
add_action( 'init', function () {
	$dir = STECH_DIR . '/blocks';
	if ( ! is_dir( $dir ) ) {
		return;
	}
	foreach ( glob( $dir . '/*/block.json' ) as $block_json ) {
		register_block_type( dirname( $block_json ) );
	}
} );

/**
 * Shared render helper used by every block's render.php.
 * Prints the standard ACF block wrapper attributes (anchor, className,
 * alignment) so blocks stay consistent and support the editor's controls.
 *
 * @param array  $block   The ACF block array.
 * @param string $default Extra classes to always apply (e.g. the block base class).
 */
function stech_block_attrs( $block, $default = '' ) {
	$classes = array();
	if ( $default ) {
		$classes[] = $default;
	}
	if ( ! empty( $block['className'] ) ) {
		$classes[] = $block['className'];
	}
	if ( ! empty( $block['align'] ) ) {
		$classes[] = 'align' . $block['align'];
	}

	$id = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
	$cls = $classes ? ' class="' . esc_attr( implode( ' ', $classes ) ) . '"' : '';

	// phpcs:ignore WordPress.Security.EscapeOutput — attrs escaped above.
	echo $id . $cls;
}

/**
 * Lock the inserter to STECH blocks + a curated set of core blocks
 * (so editors can still add rich text inside prose-style layouts without
 * exposing the full, off-brand core library).
 */
add_filter( 'allowed_block_types_all', function ( $allowed, $context ) {
	// Never restrict non-post editors (widgets, site editor, etc.).
	if ( ! isset( $context->post ) ) {
		return $allowed;
	}

	$core = array(
		'core/paragraph', 'core/heading', 'core/list', 'core/list-item',
		'core/image', 'core/embed', 'core/quote', 'core/table',
		'core/buttons', 'core/button', 'core/columns', 'core/column',
		'core/group', 'core/spacer', 'core/separator', 'core/html', 'core/shortcode',
	);

	$stech = array();
	foreach ( glob( STECH_DIR . '/blocks/*/block.json' ) as $block_json ) {
		$data = json_decode( (string) file_get_contents( $block_json ), true );
		if ( ! empty( $data['name'] ) ) {
			$stech[] = $data['name'];
		}
	}

	return array_values( array_merge( $stech, $core ) );
}, 10, 2 );
