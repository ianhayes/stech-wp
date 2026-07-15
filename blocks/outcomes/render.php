<?php
/**
 * Block: Outcomes (stech/outcomes)
 * Animated stat rings + placement pie. Matches .outcomes in the brand components.
 * Behaviour: wrap in [data-animate-stats]; blocks.js counts up rings from
 * data-value/data-suffix and draws the pie from data-segments on scroll-in.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$rings    = get_field( 'outcomes_rings' );
$show_pie = get_field( 'outcomes_show_pie' );

// Build the pie's comma-separated data-segments from the legend rows.
$pie_label   = get_field( 'outcomes_pie_label' );
$pie_legend  = get_field( 'outcomes_pie_legend' );
$pie_values  = array();
if ( $show_pie && is_array( $pie_legend ) ) {
	foreach ( $pie_legend as $row ) {
		$pie_values[] = (float) ( $row['outcomes_legend_value'] ?? 0 );
	}
}
$has_pie = $show_pie && ! empty( $pie_values );

$is_preview = ! empty( $block['is_preview'] );

if ( empty( $rings ) && ! $has_pie ) {
	if ( $is_preview ) {
		echo '<p class="acf-block-placeholder">' . esc_html__( 'Outcomes — add stat rings and an optional placement pie.', 'stech' ) . '</p>';
	}
	return;
}
?>
<section<?php stech_block_attrs( $block, 'outcomes' ); ?> data-animate-stats>
	<div class="container">
		<div class="outcomes__row">
			<?php if ( ! empty( $rings ) ) : ?>
				<?php foreach ( $rings as $ring ) : ?>
					<?php
					$value  = (float) ( $ring['outcomes_ring_value'] ?? 0 );
					$suffix = $ring['outcomes_ring_suffix'] ?? '%';
					$label  = $ring['outcomes_ring_label'] ?? '';
					?>
					<div class="stat-ring" data-value="<?php echo esc_attr( $value ); ?>">
						<div class="stat-ring__circle">
							<svg viewBox="0 0 120 120" aria-hidden="true">
								<circle class="stat-ring__track" cx="60" cy="60" r="52" pathLength="100"></circle>
								<circle class="stat-ring__bar" cx="60" cy="60" r="52" pathLength="100"></circle>
							</svg>
							<span class="stat-ring__num" data-suffix="<?php echo esc_attr( $suffix ); ?>">0</span>
						</div>
						<?php if ( $label ) : ?>
							<span class="stat-ring__label"><?php echo esc_html( $label ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( $has_pie ) : ?>
				<div class="placement-pie" data-segments="<?php echo esc_attr( implode( ',', $pie_values ) ); ?>">
					<div class="placement-pie__chart"></div>
					<?php if ( $pie_label ) : ?>
						<span class="placement-pie__label"><?php echo esc_html( $pie_label ); ?></span>
					<?php endif; ?>
					<div class="placement-pie__legend">
						<?php foreach ( $pie_legend as $row ) : ?>
							<?php
							$swatch = $row['outcomes_legend_swatch'] ?? 'lpn';
							if ( ! in_array( $swatch, array( 'lpn', 'continuing', 'non' ), true ) ) {
								$swatch = 'lpn';
							}
							$leg_label = $row['outcomes_legend_label'] ?? '';
							$leg_value = $row['outcomes_legend_value'] ?? '';
							?>
							<div class="placement-pie__legend-item">
								<span class="placement-pie__legend-swatch placement-pie__legend-swatch--<?php echo esc_attr( $swatch ); ?>"></span>
								<?php echo esc_html( trim( $leg_label . ' ' . ( '' !== $leg_value ? $leg_value . '%' : '' ) ) ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
