<?php
/**
 * Block: Mission & Philosophy (stech/mission-philosophy)
 * Two-column mission/philosophy statement on dark. Matches .mission-philosophy
 * in the brand components, with an optional Ursa bear watermark (--ursa).
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$ursa       = get_field( 'mission_philosophy_ursa_watermark' );
$has_cols   = have_rows( 'mission_philosophy_columns' );
$is_preview = ! empty( $block['is_preview'] );

if ( ! $has_cols && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Mission & Philosophy — add two columns (overline, heading, text).', 'stech' ) . '</p>';
	return;
}

if ( ! $has_cols ) {
	return;
}

$root_class = 'mission-philosophy' . ( $ursa ? ' mission-philosophy--ursa' : '' );
?>
<section<?php stech_block_attrs( $block, $root_class ); ?>>
	<?php if ( $ursa ) : ?>
		<div class="mission-philosophy__bg-icon"><img src="<?php echo esc_url( STECH_IMG . '/shared/bear.svg' ); ?>" alt=""></div>
	<?php endif; ?>
	<div class="mission-philosophy__inner">
		<?php
		while ( have_rows( 'mission_philosophy_columns' ) ) :
			the_row();
			$overline  = get_sub_field( 'mission_philosophy_col_overline' );
			$heading   = get_sub_field( 'mission_philosophy_col_heading' );
			$paragraph = get_sub_field( 'mission_philosophy_col_paragraph' );
			?>
			<div class="mission-philosophy__col">
				<?php if ( $overline ) : ?>
					<span class="overline overline--on-dark"><?php echo esc_html( $overline ); ?></span>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h3><?php echo esc_html( $heading ); ?></h3>
				<?php endif; ?>
				<?php if ( $paragraph ) : ?>
					<?php echo wp_kses_post( $paragraph ); ?>
				<?php endif; ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>
