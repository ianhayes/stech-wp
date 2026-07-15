<?php
/**
 * Block: Accreditation (stech/accreditation)
 * Accreditation callout. Matches .accreditation in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$overline     = get_field( 'accreditation_overline' );
$heading      = get_field( 'accreditation_heading' );
$lede         = get_field( 'accreditation_lede' );
$org_name     = get_field( 'accreditation_org_name' );
$address      = get_field( 'accreditation_address' );
$status_label = get_field( 'accreditation_status_label' );
$status       = get_field( 'accreditation_status' );
$link         = get_field( 'accreditation_link' );
$has_logos    = have_rows( 'accreditation_logos' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $overline && ! $heading && ! $lede && ! $has_logos && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Accreditation — add an overline, heading, body and logo(s).', 'stech' ) . '</p>';
	return;
}

$name_link = stech_link( $org_name );
$dir_link  = stech_link( $link );
?>
<section<?php stech_block_attrs( $block, 'accreditation-section' ); ?>>
	<div class="container">
		<div class="accreditation">
			<?php if ( $overline ) : ?>
				<span class="overline"><?php echo esc_html( $overline ); ?></span>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h3><?php echo esc_html( $heading ); ?></h3>
			<?php endif; ?>

			<?php if ( $lede ) : ?>
				<p class="accreditation__lede"><?php echo wp_kses_post( $lede ); ?></p>
			<?php endif; ?>

			<?php if ( $has_logos || $name_link || $address ) : ?>
				<div class="accreditation__main">
					<?php if ( $has_logos ) : ?>
						<div class="accreditation__logo">
							<?php
							while ( have_rows( 'accreditation_logos' ) ) :
								the_row();
								$logo = get_sub_field( 'accreditation_logo_image' );
								if ( empty( $logo['url'] ) ) {
									continue;
								}
								?>
								<img
									src="<?php echo esc_url( $logo['url'] ); ?>"
									alt="<?php echo esc_attr( $logo['alt'] ?? '' ); ?>"
									<?php if ( ! empty( $logo['width'] ) ) : ?>width="<?php echo (int) $logo['width']; ?>"<?php endif; ?>
									<?php if ( ! empty( $logo['height'] ) ) : ?>height="<?php echo (int) $logo['height']; ?>"<?php endif; ?>
								>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>

					<?php if ( $name_link || $address ) : ?>
						<div class="accreditation__details">
							<?php if ( $name_link ) : ?>
								<a class="accreditation__name" href="<?php echo $name_link['url']; // esc'd in helper. ?>"<?php echo $name_link['target'] . $name_link['rel']; ?>><?php echo $name_link['title']; ?></a>
							<?php endif; ?>
							<?php if ( $address ) : ?>
								<span class="accreditation__address"><?php echo esc_html( $address ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $status ) : ?>
				<p class="accreditation__decision"><strong><?php echo esc_html( $status_label ?: __( 'Status:', 'stech' ) ); ?></strong> <?php echo esc_html( $status ); ?></p>
			<?php endif; ?>

			<?php if ( $dir_link ) : ?>
				<p class="accreditation__link"><a href="<?php echo $dir_link['url']; ?>"<?php echo $dir_link['target'] . $dir_link['rel']; ?>><?php echo $dir_link['title'] ?: esc_html__( 'View directory', 'stech' ); ?> &rarr;</a></p>
			<?php endif; ?>
		</div>
	</div>
</section>
