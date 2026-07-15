<?php
/**
 * Block: Feature Row (stech/feature-row)
 * Side-image + text feature row, alternating via an optional flip.
 * Matches .feature-row in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'feature_row_eyebrow' );
$heading = get_field( 'feature_row_heading' );
$text    = get_field( 'feature_row_text' );
$cta     = get_field( 'feature_row_cta' );
$image   = get_field( 'feature_row_image' );
$flip    = (bool) get_field( 'feature_row_flip' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $heading && ! $text && ! ( $image && ! empty( $image['ID'] ) ) && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Feature Row — add an image, heading and text.', 'stech' ) . '</p>';
	return;
}

$base = 'block feature-row' . ( $flip ? ' feature-row--flip' : '' );
?>
<section<?php stech_block_attrs( $block, $base ); ?>>
	<div class="container">
		<div class="feature-row__inner">
			<div class="feature-row__media">
				<?php if ( $image && ! empty( $image['ID'] ) ) : ?>
					<?php
					echo wp_get_attachment_image(
						(int) $image['ID'],
						'stech-card',
						false,
						array( 'alt' => $image['alt'] ?? '' )
					);
					?>
				<?php endif; ?>
			</div>
			<div class="feature-row__text">
				<?php if ( $eyebrow ) : ?>
					<span class="overline"><?php echo esc_html( $eyebrow ); ?></span>
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
					<a class="btn btn--primary" href="<?php echo $c['url']; // esc'd in helper. ?>"<?php echo $c['target'] . $c['rel']; ?>>
						<?php echo $c['title'] ?: esc_html__( 'Learn More', 'stech' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
