<?php
/**
 * Block: Photo Slider (stech/photo-slider)
 * Full-bleed photo carousel with captions and dots. Matches .photo-slider in the brand components.
 * Page-scoped JS builds the dots into [data-slider-dots] and drives the track.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$has_slides = have_rows( 'photo_slider_slides' );
$is_preview = ! empty( $block['is_preview'] );

if ( ! $has_slides ) {
	if ( $is_preview ) {
		echo '<p class="acf-block-placeholder">' . esc_html__( 'Photo Slider — add slides with an image and caption.', 'stech' ) . '</p>';
	}
	return;
}
?>
<section<?php stech_block_attrs( $block, 'photo-slider' ); ?> data-slider aria-label="<?php esc_attr_e( 'Photo slider', 'stech' ); ?>">
	<div class="photo-slider__viewport">
		<div class="photo-slider__track" data-slider-track>
			<?php
			while ( have_rows( 'photo_slider_slides' ) ) :
				the_row();
				$image   = get_sub_field( 'photo_slider_slide_image' );
				$eyebrow = get_sub_field( 'photo_slider_slide_eyebrow' );
				$caption = get_sub_field( 'photo_slider_slide_caption' );
				?>
				<div class="photo-slider__slide">
					<?php
					if ( $image && ! empty( $image['ID'] ) ) {
						echo wp_get_attachment_image(
							(int) $image['ID'],
							'stech-hero',
							false,
							array( 'alt' => $image['alt'] ?? '' )
						);
					}
					?>
					<?php if ( $eyebrow || $caption ) : ?>
						<div class="photo-slider__caption">
							<?php if ( $eyebrow ) : ?>
								<span class="photo-slider__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
							<?php endif; ?>
							<?php if ( $caption ) : ?>
								<p class="photo-slider__text"><?php echo esc_html( $caption ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
	<div class="photo-slider__dots" role="tablist" aria-label="<?php esc_attr_e( 'Slider navigation', 'stech' ); ?>" data-slider-dots></div>
</section>
