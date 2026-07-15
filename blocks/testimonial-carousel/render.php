<?php
/**
 * Block: Testimonial Carousel (stech/testimonial-carousel)
 * Paginated 2-up testimonial carousel. Slides are grouped into pages of two
 * cards; navigation, dots and pagination are wired by the page carousel script
 * via the data-test-* hooks. Matches .testimonial / .testimonial-carousel in
 * the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$is_preview = ! empty( $block['is_preview'] );

// Collect the slides up front so we can paginate them 2-up.
$slides = array();
if ( have_rows( 'testimonial_carousel_slides' ) ) {
	while ( have_rows( 'testimonial_carousel_slides' ) ) {
		the_row();
		$slides[] = array(
			'quote'  => get_sub_field( 'testimonial_carousel_quote' ),
			'avatar' => get_sub_field( 'testimonial_carousel_avatar' ),
			'name'   => get_sub_field( 'testimonial_carousel_name' ),
			'role'   => get_sub_field( 'testimonial_carousel_role' ),
		);
	}
}

// Empty-state placeholder in the editor when there's nothing to show yet.
if ( empty( $slides ) ) {
	if ( $is_preview ) {
		echo '<p class="acf-block-placeholder">' . esc_html__( 'Testimonial Carousel — add one or more testimonials.', 'stech' ) . '</p>';
	}
	return;
}

/**
 * Render a single testimonial card.
 *
 * @param array $slide { quote, avatar, name, role }.
 */
$render_card = static function ( $slide ) {
	$quote  = $slide['quote'] ?? '';
	$avatar = $slide['avatar'] ?? '';
	$name   = $slide['name'] ?? '';
	$role   = $slide['role'] ?? '';

	$has_avatar = is_array( $avatar ) && ! empty( $avatar['ID'] );

	// Initials fallback when no avatar image is supplied.
	$initials = '';
	if ( ! $has_avatar && $name ) {
		$parts = preg_split( '/\s+/', trim( $name ) );
		foreach ( $parts as $part ) {
			if ( '' !== $part ) {
				$initials .= mb_substr( $part, 0, 1 );
			}
			if ( mb_strlen( $initials ) >= 2 ) {
				break;
			}
		}
		$initials = mb_strtoupper( $initials );
	}
	?>
	<div class="testimonial-card">
		<div class="testimonial-card__mark" aria-hidden="true">&ldquo;</div>
		<?php if ( $quote ) : ?>
			<blockquote><?php echo esc_html( $quote ); ?></blockquote>
		<?php endif; ?>
		<div class="testimonial-card__author">
			<?php if ( $has_avatar ) : ?>
				<div class="testimonial-card__avatar">
					<?php
					echo wp_get_attachment_image(
						(int) $avatar['ID'],
						'stech-avatar',
						false,
						array( 'alt' => $avatar['alt'] ?? '' )
					);
					?>
				</div>
			<?php else : ?>
				<div class="testimonial-card__avatar" aria-hidden="true"><?php echo esc_html( $initials ); ?></div>
			<?php endif; ?>
			<div>
				<?php if ( $name ) : ?>
					<span class="testimonial-card__name"><?php echo esc_html( $name ); ?></span>
				<?php endif; ?>
				<?php if ( $role ) : ?>
					<span class="testimonial-card__meta"><?php echo esc_html( $role ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
};

// Group the slides into pages of two.
$pages = array_chunk( $slides, 2 );
?>
<section<?php stech_block_attrs( $block, 'testimonial' ); ?>>
	<div class="testimonial__bg-icon" aria-hidden="true"><?php stech_the_svg( 'icon', array( 'aria-hidden' => 'true' ) ); ?></div>
	<div class="container">
		<div class="testimonial-carousel" data-carousel>
			<button type="button" class="testimonial-carousel__nav testimonial-carousel__nav--prev" aria-label="<?php esc_attr_e( 'Previous testimonials', 'stech' ); ?>" data-prev>
				<svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
			</button>
			<div class="testimonial-carousel__viewport">
				<div class="testimonial-carousel__track" data-track>
					<?php foreach ( $pages as $page_slides ) : ?>
						<div class="testimonial-carousel__page">
							<?php
							foreach ( $page_slides as $slide ) {
								$render_card( $slide );
							}
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<button type="button" class="testimonial-carousel__nav testimonial-carousel__nav--next" aria-label="<?php esc_attr_e( 'Next testimonials', 'stech' ); ?>" data-next>
				<svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
			</button>
			<div class="testimonial-carousel__dots" aria-label="<?php esc_attr_e( 'Testimonial pages', 'stech' ); ?>" data-dots></div>
		</div>
	</div>
</section>
