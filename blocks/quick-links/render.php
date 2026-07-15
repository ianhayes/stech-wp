<?php
/**
 * Block: Quick Links (stech/quick-links)
 * Horizontal icon-link strip on the blue band. Matches .quick-links in the
 * brand components. Each item is an <a> with an inline icon SVG + label.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$is_preview = ! empty( $block['is_preview'] );

if ( ! have_rows( 'quick_links_items' ) ) {
	if ( $is_preview ) {
		echo '<p class="acf-block-placeholder">' . esc_html__( 'Quick Links — add one or more icon links.', 'stech' ) . '</p>';
	}
	return;
}

/**
 * Inline the contents of an uploaded SVG attachment (recolourable via
 * currentColor, matching the brand icons). Returns '' when unavailable.
 */
if ( ! function_exists( 'stech_quick_links_inline_svg' ) ) {
	function stech_quick_links_inline_svg( $icon ) {
		$id = is_array( $icon ) ? ( $icon['ID'] ?? $icon['id'] ?? 0 ) : ( is_numeric( $icon ) ? (int) $icon : 0 );
		if ( ! $id ) {
			return '';
		}
		$path = get_attached_file( $id );
		if ( ! $path || ! is_readable( $path ) || 'svg' !== strtolower( pathinfo( $path, PATHINFO_EXTENSION ) ) ) {
			return '';
		}
		return (string) file_get_contents( $path );
	}
}
?>
<section<?php stech_block_attrs( $block, 'quick-links' ); ?>>
	<div class="quick-links__grid">
		<?php
		while ( have_rows( 'quick_links_items' ) ) :
			the_row();
			$label     = get_sub_field( 'quick_links_items_label' );
			$link      = stech_link( get_sub_field( 'quick_links_items_link' ) );
			$icon_svg  = get_sub_field( 'quick_links_items_icon' );
			$icon_name = get_sub_field( 'quick_links_items_icon_name' );

			$svg = stech_quick_links_inline_svg( $icon_svg );

			$href   = $link ? $link['url'] : '#';
			$target = $link ? $link['target'] : '';
			$rel    = $link ? $link['rel'] : '';
			?>
			<a class="quick-links__item" href="<?php echo $href; // esc'd in stech_link(). ?>"<?php echo $target . $rel; ?>>
				<?php
				if ( $svg ) {
					echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput — trusted admin-uploaded asset.
				} elseif ( $icon_name ) {
					printf( '<span class="dashicons dashicons-%s" aria-hidden="true"></span>', esc_attr( $icon_name ) );
				}
				echo esc_html( $label );
				?>
			</a>
		<?php endwhile; ?>
	</div>
</section>
