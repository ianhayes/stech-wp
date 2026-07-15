<?php
/**
 * Block: Comparison Table (stech/compare-table)
 * "How we stack up" table with a highlighted STECH column. Matches
 * .compare-table in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = get_field( 'compare_table_eyebrow' );
$heading  = get_field( 'compare_table_heading' );
$footnote = get_field( 'compare_table_footnote' );

$has_columns = have_rows( 'compare_table_columns' );
$has_rows    = have_rows( 'compare_table_rows' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $heading && ! $eyebrow && ! $has_columns && ! $has_rows && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Comparison Table — add columns and rows.', 'stech' ) . '</p>';
	return;
}

// Collect the highlight flag per column index so tbody cells can inherit it.
$col_highlight = array();
if ( have_rows( 'compare_table_columns' ) ) {
	while ( have_rows( 'compare_table_columns' ) ) {
		the_row();
		$col_highlight[] = (bool) get_sub_field( 'compare_table_columns_highlight' );
	}
}
?>
<section<?php stech_block_attrs( $block, 'compare-table block' ); ?>>
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

	<?php if ( $has_columns || $has_rows ) : ?>
		<table class="compare-table">
			<?php if ( $col_highlight ) : ?>
				<thead>
					<tr>
						<?php
						$c = 0;
						while ( have_rows( 'compare_table_columns' ) ) :
							the_row();
							$label    = get_sub_field( 'compare_table_columns_label' );
							$hi_class = ! empty( $col_highlight[ $c ] ) ? ' class="col-highlight"' : '';
							$c++;
							?>
							<th<?php echo $hi_class; // esc'd literal. ?>><?php echo esc_html( $label ); ?></th>
						<?php endwhile; ?>
					</tr>
				</thead>
			<?php endif; ?>
			<tbody>
				<?php
				while ( have_rows( 'compare_table_rows' ) ) :
					the_row();
					if ( ! have_rows( 'compare_table_rows_cells' ) ) {
						continue;
					}
					?>
					<tr>
						<?php
						$i = 0;
						while ( have_rows( 'compare_table_rows_cells' ) ) :
							the_row();
							$value   = get_sub_field( 'compare_table_rows_cells_value' );
							$is_yes  = (bool) get_sub_field( 'compare_table_rows_cells_is_yes' );
							$classes = array();
							if ( ! empty( $col_highlight[ $i ] ) ) {
								$classes[] = 'col-highlight';
							}
							if ( $is_yes ) {
								$classes[] = 'is-yes';
							}
							$i++;
							$cls = $classes ? ' class="' . esc_attr( implode( ' ', $classes ) ) . '"' : '';
							?>
							<td<?php echo $cls; ?>><?php echo esc_html( $value ); ?></td>
						<?php endwhile; ?>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	<?php endif; ?>

	<?php if ( $footnote ) : ?>
		<p class="text-note text-note--center" style="margin-top:16px"><?php echo wp_kses_post( $footnote ); ?></p>
	<?php endif; ?>
</section>
