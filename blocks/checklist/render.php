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
$base = 'checklist';
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
			</div>
		<?php endif; ?>

		<?php if ( $has_items ) : ?>
			<div class="checklist__grid">
				<?php
				while ( have_rows( 'checklist_items' ) ) :
					the_row();
					$label = (string) get_sub_field( 'checklist_items_label' );
					$desc  = (string) get_sub_field( 'checklist_items_description' );

					if ( '' === $label && '' === $desc ) {
						continue;
					}
					?>
					<div class="checklist__item">
						<span class="checklist__check"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg></span>
						<?php if ( '' !== $desc ) : ?>
							<div><strong><?php echo esc_html( $label ); ?></strong><br><?php echo esc_html( $desc ); ?></div>
						<?php else : ?>
							<?php echo esc_html( $label ); ?>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
