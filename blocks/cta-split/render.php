<?php
/**
 * Block: CTA Split (stech/cta-split)
 * Image + text call-to-action on a dark blue split panel.
 * Matches .cta-split in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$overline = get_field( 'cta_split_overline' );
$heading  = get_field( 'cta_split_heading' );
$text     = get_field( 'cta_split_text' );
$cta      = get_field( 'cta_split_cta' );
$image    = get_field( 'cta_split_image' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $heading && ! $text && ! ( $image && ! empty( $image['ID'] ) ) && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'CTA Split — add an image, heading, text and button.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'block cta-split' ); ?>>
	<div class="container">
		<div class="cta-split__inner">
			<div class="cta-split__media">
				<?php if ( $image && ! empty( $image['ID'] ) ) : ?>
					<?php
					echo wp_get_attachment_image(
						(int) $image['ID'],
						'stech-card',
						false,
						array( 'alt' => $image['alt'] ?? '' ) // wp_get_attachment_image escapes attrs.
					);
					?>
				<?php endif; ?>
			</div>
			<div class="cta-split__text">
				<?php if ( $overline ) : ?>
					<span class="overline overline--on-dark"><?php echo esc_html( $overline ); ?></span>
				<?php endif; ?>

				<?php if ( $heading ) : ?>
					<h2 class="h2"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<p><?php echo wp_kses_post( $text ); ?></p>
				<?php endif; ?>

				<?php
				$c = stech_link( $cta );
				if ( $c ) :
					?>
					<a class="btn btn--ghost-white" href="<?php echo $c['url']; // esc'd in helper. ?>"<?php echo $c['target'] . $c['rel']; ?>>
						<?php echo $c['title'] ?: esc_html__( 'Apply Now', 'stech' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
