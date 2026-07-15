<?php
/**
 * Block: Steps (stech/steps)
 * Numbered process grid. Matches .steps in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'steps_eyebrow' );
$heading = get_field( 'steps_heading' );
$items   = get_field( 'steps_items' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $heading && empty( $items ) && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Steps — add a heading and step items.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'block steps' ); ?>>
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

		<?php if ( have_rows( 'steps_items' ) ) : ?>
			<div class="steps__grid">
				<?php
				$i = 0;
				while ( have_rows( 'steps_items' ) ) :
					the_row();
					$i++;
					$number = get_sub_field( 'steps_items_number' );
					$number = ( '' !== $number && null !== $number ) ? $number : $i;
					$step_h = get_sub_field( 'steps_items_heading' );
					$step_t = get_sub_field( 'steps_items_text' );
					?>
					<div class="step">
						<div class="step__num"><?php echo esc_html( $number ); ?></div>
						<?php if ( $step_h ) : ?>
							<h3><?php echo esc_html( $step_h ); ?></h3>
						<?php endif; ?>
						<?php if ( $step_t ) : ?>
							<p><?php echo esc_html( $step_t ); ?></p>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
