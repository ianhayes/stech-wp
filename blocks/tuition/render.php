<?php
/**
 * Block: Tuition Spotlight (stech/tuition)
 * Text + image cost pitch. Matches .tuition in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow   = get_field( 'tuition_eyebrow' );
$heading   = get_field( 'tuition_heading' );
$text      = get_field( 'tuition_text' );
$primary   = get_field( 'tuition_primary_button' );
$secondary = get_field( 'tuition_secondary_button' );
$image     = get_field( 'tuition_image' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $heading && ! $text && ! $image && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Tuition Spotlight — add a heading, text, image and buttons.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'block tuition' ); ?>>
	<div class="container">
		<div class="tuition__inner">
			<?php if ( $image && ! empty( $image['ID'] ) ) : ?>
				<div class="tuition__image">
					<?php
					echo wp_get_attachment_image(
						(int) $image['ID'],
						'stech-card',
						false,
						array( 'alt' => $image['alt'] ?? '' )
					);
					?>
				</div>
			<?php endif; ?>

			<div class="tuition__text">
				<?php if ( $eyebrow ) : ?>
					<span class="overline"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>

				<?php if ( $heading ) : ?>
					<h2 class="h1"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<p><?php echo wp_kses_post( $text ); ?></p>
				<?php endif; ?>

				<?php if ( $primary || $secondary ) : ?>
					<div class="tuition__cta">
						<?php
						$p = stech_link( $primary );
						if ( $p ) :
							?>
							<a class="btn btn--primary" href="<?php echo $p['url']; // esc'd in helper. ?>"<?php echo $p['target'] . $p['rel']; ?>>
								<?php echo $p['title'] ?: esc_html__( 'See Tuition', 'stech' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span>
							</a>
						<?php endif; ?>
						<?php
						$s = stech_link( $secondary );
						if ( $s ) :
							?>
							<a class="btn btn--ghost" href="<?php echo $s['url']; ?>"<?php echo $s['target'] . $s['rel']; ?>><?php echo $s['title'] ?: esc_html__( 'Financial Aid', 'stech' ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
