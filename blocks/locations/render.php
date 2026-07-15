<?php
/**
 * Block: Locations (stech/locations)
 * Map embed + location cards, 2-col. Matches .locations / .location-card in
 * the brand components. Optional scrollable list variant (.locations--scroll).
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$overline  = get_field( 'locations_overline' );
$heading   = get_field( 'locations_heading' );
$map_embed = get_field( 'locations_map_embed' );
$map       = get_field( 'locations_map' );
$scroll    = get_field( 'locations_scroll' );

$is_preview = ! empty( $block['is_preview'] );
$has_cards  = have_rows( 'locations_cards' );

if ( ! $overline && ! $heading && ! $has_cards && ! $map_embed && empty( $map['lat'] ) ) {
	if ( $is_preview ) {
		echo '<p class="acf-block-placeholder">' . esc_html__( 'Locations — add a heading, a map embed and one or more location cards.', 'stech' ) . '</p>';
	}
	return;
}

$classes = 'locations';
if ( $scroll ) {
	$classes .= ' locations--scroll';
}

/**
 * Build a safe tel: href from a human-formatted phone number.
 */
if ( ! function_exists( 'stech_locations_tel' ) ) {
	function stech_locations_tel( $phone ) {
		$digits = preg_replace( '/[^0-9+]/', '', (string) $phone );
		return $digits ? 'tel:' . $digits : '';
	}
}
?>
<section<?php stech_block_attrs( $block, $classes ); ?>>
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

		<div class="locations__inner">
			<div class="locations__map">
				<?php
				if ( ! $map_embed && ! empty( $map['lat'] ) && ! empty( $map['lng'] ) ) {
					$lat  = (float) $map['lat'];
					$lng  = (float) $map['lng'];
					$bbox = sprintf( '%F,%F,%F,%F', $lng - 0.01, $lat - 0.005, $lng + 0.01, $lat + 0.005 );
					$map_embed = sprintf(
						'https://www.openstreetmap.org/export/embed.html?bbox=%s&layer=mapnik&marker=%F,%F',
						$bbox,
						$lat,
						$lng
					);
				}

				if ( $map_embed ) :
					$map_title = $heading ? $heading : __( 'Campus location map', 'stech' );
					?>
					<iframe title="<?php echo esc_attr( $map_title ); ?>" src="<?php echo esc_url( $map_embed ); ?>" loading="lazy"></iframe>
				<?php else : ?>
					<div style="display:flex;align-items:center;justify-content:center;height:100%;min-height:380px;flex-direction:column;gap:10px;color:var(--stech-gray);font-family:var(--font-condensed);letter-spacing:0.12em;text-transform:uppercase;font-size:0.8rem;"><svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="var(--blue)" stroke-width="1.5" aria-hidden="true"><path d="M12 21s-7-7.5-7-12a7 7 0 1114 0c0 4.5-7 12-7 12z"/><circle cx="12" cy="9" r="2.5"/></svg><?php esc_html_e( 'Map embed slot', 'stech' ); ?></div>
				<?php endif; ?>
			</div>

			<div class="locations__list">
				<?php
				if ( $has_cards ) :
					while ( have_rows( 'locations_cards' ) ) :
						the_row();
						$name   = get_sub_field( 'locations_cards_name' );
						$street = get_sub_field( 'locations_cards_street' );
						$phone  = get_sub_field( 'locations_cards_phone' );
						$email  = get_sub_field( 'locations_cards_email' );
						$hours  = get_sub_field( 'locations_cards_hours' );
						$tel    = stech_locations_tel( $phone );
						?>
						<div class="location-card">
							<?php if ( $name ) : ?>
								<h3><?php echo esc_html( $name ); ?></h3>
							<?php endif; ?>
							<address>
								<?php if ( $street ) : ?>
									<?php echo esc_html( $street ); ?><br>
								<?php endif; ?>
								<?php if ( $phone ) : ?>
									<?php if ( $tel ) : ?>
										<a href="<?php echo esc_url( $tel, array( 'tel' ) ); ?>"><?php echo esc_html( $phone ); ?></a><br>
									<?php else : ?>
										<?php echo esc_html( $phone ); ?><br>
									<?php endif; ?>
								<?php endif; ?>
								<?php if ( $email ) : ?>
									<a href="<?php echo esc_url( 'mailto:' . antispambot( $email ), array( 'mailto' ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a><br>
								<?php endif; ?>
								<?php if ( $hours ) : ?>
									<?php echo esc_html( $hours ); ?>
								<?php endif; ?>
							</address>
						</div>
					<?php endwhile; ?>
				<?php elseif ( $is_preview ) : ?>
					<div class="location-card">
						<h3><?php esc_html_e( 'Add a location', 'stech' ); ?></h3>
						<address><?php esc_html_e( 'Street, city and phone appear here.', 'stech' ); ?></address>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
