<?php
/**
 * Block: Price Table (stech/price-table)
 * Two-column cost breakdown. Matches .price-table in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$head_label  = get_field( 'price_table_head_label' );
$head_value  = get_field( 'price_table_head_value' );
$total_label = get_field( 'price_table_total_label' );
$total_value = get_field( 'price_table_total_value' );
$rows        = get_field( 'price_table_rows' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $head_label && ! $head_value && ! $total_label && empty( $rows ) && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Price Table — add a head row, cost rows and a total.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'block' ); ?>>
	<div class="container">
		<div class="price-table">
	<?php if ( $head_label || $head_value ) : ?>
		<div class="price-table__row price-table__row--head">
			<span class="price-table__label"><?php echo esc_html( $head_label ); ?></span>
			<span class="price-table__value"><?php echo esc_html( $head_value ); ?></span>
		</div>
	<?php endif; ?>

	<?php
	if ( have_rows( 'price_table_rows' ) ) :
		while ( have_rows( 'price_table_rows' ) ) :
			the_row();
			$label = get_sub_field( 'label' );
			$sub   = get_sub_field( 'sub' );
			$value = get_sub_field( 'value' );
			?>
			<div class="price-table__row">
				<span class="price-table__label"><?php echo esc_html( $label ); ?><?php if ( $sub ) : ?> <span class="price-table__sub"><?php echo esc_html( $sub ); ?></span><?php endif; ?></span>
				<span class="price-table__value"><?php echo esc_html( $value ); ?></span>
			</div>
			<?php
		endwhile;
	endif;
	?>

	<?php if ( $total_label || $total_value ) : ?>
		<div class="price-table__row price-table__row--total">
			<span class="price-table__label"><?php echo esc_html( $total_label ); ?></span>
			<span class="price-table__value"><?php echo esc_html( $total_value ); ?></span>
		</div>
	<?php endif; ?>
		</div>
	</div>
</section>
