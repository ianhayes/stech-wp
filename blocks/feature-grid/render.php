<?php
/**
 * Block: Feature Grid (stech/feature-grid)
 * Four-up icon feature cards. Matches .feature-grid / .why-card in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$overline = get_field( 'feature_grid_overline' );
$heading  = get_field( 'feature_grid_heading' );
$lede     = get_field( 'feature_grid_lede' );

$is_preview = ! empty( $block['is_preview'] );
$has_cards  = have_rows( 'feature_grid_cards' );

if ( ! $heading && ! $overline && ! $has_cards ) {
	if ( $is_preview ) {
		echo '<p class="acf-block-placeholder">' . esc_html__( 'Feature Grid — add a heading and one or more icon cards.', 'stech' ) . '</p>';
	}
	return;
}
?>
<section<?php stech_block_attrs( $block, 'feature-grid block' ); ?>>
	<div class="container">
		<?php if ( $overline || $heading || $lede ) : ?>
			<div class="section-head">
				<?php if ( $overline ) : ?>
					<span class="overline"><?php echo esc_html( $overline ); ?></span>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="h2"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $lede ) : ?>
					<p><?php echo wp_kses_post( $lede ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $has_cards ) : ?>
			<div class="feature-grid__grid">
				<?php
				while ( have_rows( 'feature_grid_cards' ) ) :
					the_row();
					$icon  = get_sub_field( 'feature_grid_cards_icon' );
					$title = get_sub_field( 'feature_grid_cards_title' );
					$text  = get_sub_field( 'feature_grid_cards_text' );
					?>
					<div class="why-card">
						<div class="why-card__icon" aria-hidden="true">
							<?php echo stech_icon( $icon ?: 'feature-01' ); // phpcs:ignore WordPress.Security.EscapeOutput — trusted theme asset. ?>
						</div>
						<?php if ( $title ) : ?>
							<h3><?php echo esc_html( $title ); ?></h3>
						<?php endif; ?>
						<?php if ( $text ) : ?>
							<p><?php echo wp_kses_post( $text ); ?></p>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
