<?php
/**
 * Block: Gallery (stech/gallery)
 * Bento image grid with tall/wide items. Matches .gallery in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'gallery_eyebrow' );
$heading = get_field( 'gallery_heading' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $heading && ! $eyebrow && ! have_rows( 'gallery_items' ) && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Gallery — add a heading and image items.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'gallery block' ); ?>>
	<div class="container">
		<?php if ( $eyebrow || $heading ) : ?>
			<div class="section-head">
				<?php if ( $eyebrow ) : ?>
					<span class="overline"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="h2"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( have_rows( 'gallery_items' ) ) : ?>
			<div class="gallery__grid">
				<?php
				while ( have_rows( 'gallery_items' ) ) :
					the_row();
					$image = get_sub_field( 'gallery_items_image' );
					$size  = get_sub_field( 'gallery_items_size' );

					if ( ! is_array( $image ) || empty( $image['ID'] ) ) {
						continue;
					}

					$item_class = 'gallery__item';
					if ( 'tall' === $size ) {
						$item_class .= ' gallery__item--tall';
					} elseif ( 'wide' === $size ) {
						$item_class .= ' gallery__item--wide';
					}

					$img_size = ( 'tall' === $size ) ? 'stech-card-tall' : 'stech-card';
					?>
					<div class="<?php echo esc_attr( $item_class ); ?>">
						<?php
						echo wp_get_attachment_image(
							(int) $image['ID'],
							$img_size,
							false,
							array( 'alt' => $image['alt'] ?? '' ) // wp_get_attachment_image escapes attrs.
						); // phpcs:ignore WordPress.Security.EscapeOutput — wp_get_attachment_image output.
						?>
					</div>
					<?php
				endwhile;
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
