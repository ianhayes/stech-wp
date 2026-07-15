<?php
/**
 * Block: Stat Strip (stech/stat-strip)
 * Full-width 4-up big-number stat band. Matches .stat-strip in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$has_stats  = have_rows( 'stat_strip_stats' );
$is_preview = ! empty( $block['is_preview'] );

if ( ! $has_stats && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Stat Strip — add stats (number and label).', 'stech' ) . '</p>';
	return;
}

if ( ! $has_stats ) {
	return;
}
?>
<section<?php stech_block_attrs( $block, 'stat-strip' ); ?> aria-label="<?php echo esc_attr__( 'At a glance', 'stech' ); ?>">
	<div class="stat-strip__inner">
		<?php
		while ( have_rows( 'stat_strip_stats' ) ) :
			the_row();
			$number = get_sub_field( 'stat_strip_number' );
			$label  = get_sub_field( 'stat_strip_label' );
			?>
			<div class="stat-strip__item">
				<?php if ( $number ) : ?>
					<div class="stat-strip__num"><?php echo esc_html( $number ); ?></div>
				<?php endif; ?>
				<?php if ( $label ) : ?>
					<div class="stat-strip__label"><?php echo esc_html( $label ); ?></div>
				<?php endif; ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>
