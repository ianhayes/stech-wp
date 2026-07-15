<?php
/**
 * Block: Checklist (stech/checklist)
 * Left-aligned section head + checkmark grid. Matches .checklist in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$overline = get_field( 'checklist_overline' );
$heading  = get_field( 'checklist_heading' );
$lede     = get_field( 'checklist_lede' );
$columns  = get_field( 'checklist_columns' );

$is_preview = ! empty( $block['is_preview'] );
$has_items  = have_rows( 'checklist_items' );

if ( ! $heading && ! $has_items ) {
	if ( $is_preview ) {
		echo '<p class="acf-block-placeholder">' . esc_html__( 'Checklist — add a heading and checklist items.', 'stech' ) . '</p>';
	}
	return;
}

// Column variant → modifier class ( 2 = default, no modifier ).
$base = 'block checklist';
if ( '3' === $columns ) {
	$base .= ' checklist--3';
} elseif ( '4' === $columns ) {
	$base .= ' checklist--4';
}
?>
<section<?php stech_block_attrs( $block, $base ); ?>>
	<div class="container">
		<?php if ( $overline || $heading ) : ?>
			<div class="section-head section-head--left">
				<?php if ( $overline ) : ?>
					<span class="overline"><?php echo esc_html( $overline ); ?></span>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="h2"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $lede ) : ?>
					<p><?php echo esc_html( $lede ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $has_items ) : ?>
			<div class="checklist__grid">
				<?php
				while ( have_rows( 'checklist_items' ) ) :
					the_row();
					$label = (string) get_sub_field( 'checklist_items_label' );

					if ( '' === $label ) {
						continue;
					}
					?>
					<div class="checklist__item"><span class="checklist__check"><?php echo stech_icon( 'check-01' ); // phpcs:ignore WordPress.Security.EscapeOutput — trusted inline SVG asset. ?></span><?php echo esc_html( $label ); ?></div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
