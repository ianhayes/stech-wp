<?php
/**
 * Block: Industry Partners (stech/oac)
 * Occupational advisory committee — overline, heading, intro, member list and
 * a grid of partner logo tiles. Matches .oac-section / .oac in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$overline = get_field( 'oac_overline' );
$heading  = get_field( 'oac_heading' );
$intro    = get_field( 'oac_intro' );
$has_orgs  = have_rows( 'oac_orgs' );
$has_logos = have_rows( 'oac_logos' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $heading && ! $overline && ! $intro && ! $has_orgs && ! $has_logos ) {
	if ( $is_preview ) {
		echo '<p class="acf-block-placeholder">' . esc_html__( 'Industry Partners — add a heading and partner logo tiles.', 'stech' ) . '</p>';
	}
	return;
}
?>
<section<?php stech_block_attrs( $block, 'block oac-section' ); ?>>
	<div class="oac">
		<?php if ( $overline ) : ?>
			<span class="overline"><?php echo esc_html( $overline ); ?></span>
		<?php endif; ?>

		<?php if ( $heading ) : ?>
			<h3><?php echo esc_html( $heading ); ?></h3>
		<?php endif; ?>

		<?php if ( $intro ) : ?>
			<?php echo wp_kses_post( $intro ); ?>
		<?php endif; ?>

		<?php if ( $has_orgs ) : ?>
			<ul class="oac__list">
				<?php
				while ( have_rows( 'oac_orgs' ) ) :
					the_row();
					$org_name = get_sub_field( 'oac_org_name' );
					if ( ! $org_name ) {
						continue;
					}
					?>
					<li><?php echo esc_html( $org_name ); ?></li>
				<?php endwhile; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $has_logos ) : ?>
			<div class="oac__logos">
				<?php
				while ( have_rows( 'oac_logos' ) ) :
					the_row();
					$image = get_sub_field( 'oac_logo_image' );
					$name  = get_sub_field( 'oac_logo_name' );
					if ( empty( $image['ID'] ) ) {
						continue;
					}
					$alt = $name ? $name : ( $image['alt'] ?? '' );
					?>
					<div class="oac__logo">
						<?php
						echo wp_get_attachment_image(
							(int) $image['ID'],
							'stech-logo-tile',
							false,
							array( 'alt' => $alt )
						);
						?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
