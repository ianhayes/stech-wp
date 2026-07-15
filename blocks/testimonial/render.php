<?php
/**
 * Block: Testimonial (stech/testimonial)
 * Single blockquote testimonial. Matches .testimonial in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$quote  = get_field( 'testimonial_quote' );
$avatar = get_field( 'testimonial_avatar' );
$name   = get_field( 'testimonial_name' );
$role   = get_field( 'testimonial_role' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $quote && ! $name && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Testimonial — add a quote and attribution.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'block testimonial' ); ?>>
	<div class="testimonial__bg-icon"><img src="<?php echo esc_url( STECH_IMG . '/shared/paw.svg' ); ?>" alt=""></div>
	<div class="container">
		<div class="testimonial__card">
			<div class="testimonial__mark" aria-hidden="true">&ldquo;</div>

			<?php if ( $quote ) : ?>
				<blockquote><?php echo esc_html( $quote ); ?></blockquote>
			<?php endif; ?>

			<?php if ( ( $avatar && ! empty( $avatar['ID'] ) ) || $name || $role ) : ?>
				<div class="testimonial__author">
					<?php if ( $avatar && ! empty( $avatar['ID'] ) ) : ?>
						<?php
						echo wp_get_attachment_image(
							(int) $avatar['ID'],
							'stech-avatar',
							false,
							array(
								'class' => 'testimonial__avatar',
								'alt'   => $avatar['alt'] ?? '',
							)
						);
						?>
					<?php endif; ?>
					<div>
						<?php if ( $name ) : ?>
							<span class="testimonial__name"><?php echo esc_html( $name ); ?></span>
						<?php endif; ?>
						<?php if ( $role ) : ?>
							<span class="testimonial__meta"><?php echo esc_html( $role ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
