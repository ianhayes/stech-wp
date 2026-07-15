<?php
/**
 * Template helpers shared by header/footer and block render templates.
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline a brand SVG from src/img/shared (logo, paw, bear, icon…).
 * Inlining keeps them recolourable via currentColor and avoids extra requests.
 *
 * @param string $name  File name without extension (e.g. 'logo', 'paw').
 * @param array  $attrs Extra attributes to merge onto the <svg> (class, aria-*).
 */
function stech_svg( $name, $attrs = array() ) {
	$file = STECH_DIR . '/src/img/shared/' . sanitize_file_name( $name ) . '.svg';
	if ( ! is_readable( $file ) ) {
		return '';
	}
	$svg = file_get_contents( $file );

	if ( $attrs ) {
		$inject = '';
		foreach ( $attrs as $k => $v ) {
			$inject .= sprintf( ' %s="%s"', esc_attr( $k ), esc_attr( $v ) );
		}
		$svg = preg_replace( '/<svg\b/', '<svg' . $inject, $svg, 1 );
	}
	return $svg;
}

/** Echo helper for stech_svg(). */
function stech_the_svg( $name, $attrs = array() ) {
	echo stech_svg( $name, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput — trusted theme asset.
}

/**
 * Inline a design icon from src/img/icons (the 43 approved inline SVGs extracted
 * from the brand system: feature-*, quicklink-*, prog-*, nav-*, play-*, check-*,
 * resarrow-*, resicon-*, alert-*, ring-*). Decorative — rendered aria-hidden.
 *
 * @param string $name Icon file name without extension (e.g. 'feature-01').
 * @return string Inline SVG, or '' if not found.
 */
function stech_icon( $name ) {
	if ( ! $name ) {
		return '';
	}
	$file = STECH_DIR . '/src/img/icons/' . sanitize_file_name( $name ) . '.svg';
	return is_readable( $file ) ? file_get_contents( $file ) : '';
}

/** Echo helper for stech_icon(). */
function stech_the_icon( $name ) {
	echo stech_icon( $name ); // phpcs:ignore WordPress.Security.EscapeOutput — trusted theme asset.
}

/**
 * List available design icon names (for ACF select choices / editor pickers),
 * optionally filtered by a name prefix (e.g. 'feature', 'quicklink', 'prog').
 *
 * @return string[] icon names.
 */
function stech_icon_choices( $prefix = '' ) {
	$names = array();
	foreach ( glob( STECH_DIR . '/src/img/icons/*.svg' ) as $f ) {
		$n = basename( $f, '.svg' );
		if ( '' === $prefix || 0 === strpos( $n, $prefix ) ) {
			$names[] = $n;
		}
	}
	sort( $names );
	return $names;
}

/**
 * Normalise an ACF Link field (return format: array) into safe parts.
 * Returns null when empty.
 *
 * @param array|null $link ACF link array (url/title/target).
 * @return array|null { url, title, target, rel }
 */
function stech_link( $link ) {
	if ( empty( $link['url'] ) ) {
		return null;
	}
	$target = $link['target'] ?? '';
	return array(
		'url'    => esc_url( $link['url'] ),
		'title'  => esc_html( $link['title'] ?? '' ),
		'target' => $target ? ' target="' . esc_attr( $target ) . '"' : '',
		'rel'    => '_blank' === $target ? ' rel="noopener"' : '',
	);
}

/**
 * Render an ACF Link as a full <a>. $classes applied to the anchor.
 */
function stech_link_tag( $link, $classes = '', $fallback_label = '' ) {
	$l = stech_link( $link );
	if ( ! $l ) {
		return '';
	}
	$label = $l['title'] ?: esc_html( $fallback_label );
	return sprintf(
		'<a class="%s" href="%s"%s%s>%s</a>',
		esc_attr( $classes ),
		$l['url'],
		$l['target'],
		$l['rel'],
		$label
	);
}

/**
 * Nav walker that outputs FLAT <a> tags (no <ul>/<li>), matching the approved
 * markup where nav links are direct children (.main-nav a, .footer-col a).
 * Add a menu-item CSS class of "btn" to render an item as a primary button.
 */
class Stech_Nav_Links extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$is_btn  = array_intersect( array( 'btn', 'button', 'cta' ), $classes );
		$current = array_intersect( array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor', 'current_page_ancestor' ), $classes );

		$attr  = $is_btn ? ' class="btn btn--primary btn--sm"' : '';
		$attr .= $current ? ' aria-current="page"' : '';
		$attr .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '" rel="noopener"' : '';

		$url = ! empty( $item->url ) ? esc_url( $item->url ) : '#';
		$output .= '<a href="' . $url . '"' . $attr . '>' . esc_html( $item->title ) . '</a>';
	}
}

/**
 * Render a nav menu with the flat-link walker, or print $fallback markup
 * (a closure) when no menu is assigned to $location — so a fresh install
 * still shows the approved default navigation.
 */
function stech_nav( $location, callable $fallback ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu( array(
			'theme_location' => $location,
			'container'      => false,
			'items_wrap'     => '%3$s',
			'depth'          => 1,
			'walker'         => new Stech_Nav_Links(),
			'fallback_cb'    => false,
		) );
	} else {
		$fallback();
	}
}

/**
 * Simple breadcrumb trail for interior page headers.
 * Prefers Yoast's breadcrumb when available (keeps SEO structured data),
 * otherwise builds Home › Ancestors › Current.
 */
function stech_breadcrumb() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav class="breadcrumb" aria-label="Breadcrumb">', '</nav>' );
		return;
	}

	$items = array( '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'stech' ) . '</a>' );

	if ( is_singular() ) {
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
		foreach ( $ancestors as $ancestor ) {
			$items[] = '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
		}
		$items[] = '<span class="breadcrumb__current" aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_archive() ) {
		$items[] = '<span class="breadcrumb__current" aria-current="page">' . esc_html( get_the_archive_title() ) . '</span>';
	}

	echo '<nav class="breadcrumb" aria-label="Breadcrumb">' . implode( ' <span class="breadcrumb__sep" aria-hidden="true">/</span> ', $items ) . '</nav>'; // phpcs:ignore WordPress.Security.EscapeOutput
}
