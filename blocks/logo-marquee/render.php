<?php
/**
 * Block: Logo Marquee (stech/logo-marquee)
 * Scrolling partner-logo marquee (duplicated aria-hidden track, reduced-motion
 * static), or a static N-column logo grid. Matches .logo-marquee / .logo-grid
 * in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$static_grid = (bool) get_field( 'logo_marquee_static_grid' );
$columns     = (int) get_field( 'logo_marquee_columns' );
$has_logos   = have_rows( 'logo_marquee_logos' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $has_logos && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Logo Marquee — add partner logos.', 'stech' ) . '</p>';
	return;
}

if ( ! $has_logos ) {
	return;
}

/**
 * Collect the logo rows once so we can render them (and, for the marquee,
 * an aria-hidden duplicate track that makes the scroll seamless).
 *
 * @var array<int,array{id:int,alt:string}> $logos
 */
$logos = array();
while ( have_rows( 'logo_marquee_logos' ) ) :
	the_row();
	$image = get_sub_field( 'logo_marquee_logo_image' );
	if ( ! $image || empty( $image['ID'] ) ) {
		continue;
	}
	$alt_override = get_sub_field( 'logo_marquee_logo_alt' );
	$logos[]      = array(
		'id'  => (int) $image['ID'],
		'alt' => $alt_override ? $alt_override : ( $image['alt'] ?? '' ),
	);
endwhile;

if ( ! $logos ) {
	return;
}

/**
 * Output a single logo cell.
 *
 * @param array{id:int,alt:string} $logo       Logo row.
 * @param string                   $item_class Wrapper class.
 * @param bool                     $decorative Render as an aria-hidden clone.
 */
$render_logo = static function ( $logo, $item_class, $decorative = false ) {
	$hidden = $decorative ? ' aria-hidden="true"' : '';
	$alt    = $decorative ? '' : $logo['alt'];
	printf(
		'<span class="%s"%s>%s</span>',
		esc_attr( $item_class ),
		$hidden, // phpcs:ignore WordPress.Security.EscapeOutput — static literal.
		wp_get_attachment_image(
			$logo['id'],
			'stech-logo-tile',
			false,
			array( 'alt' => $alt ) // wp_get_attachment_image() esc_attr()s this internally.
		)
	);
};

if ( $static_grid ) :
	$style = $columns > 0 ? ' style="--logo-cols:' . esc_attr( $columns ) . '"' : '';
	?>
	<section<?php stech_block_attrs( $block, 'logo-grid block block--tight' ); ?>>
		<div class="logo-grid__row"<?php echo $style; // phpcs:ignore WordPress.Security.EscapeOutput — escaped above. ?>>
			<?php foreach ( $logos as $logo ) : ?>
				<?php $render_logo( $logo, 'logo-grid__logo' ); ?>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
else :
	?>
	<section<?php stech_block_attrs( $block, 'logo-marquee block block--tight' ); ?>>
		<div class="logo-marquee__track">
			<?php foreach ( $logos as $logo ) : ?>
				<?php $render_logo( $logo, 'logo-marquee__logo' ); ?>
			<?php endforeach; ?>
			<?php foreach ( $logos as $logo ) : ?>
				<?php $render_logo( $logo, 'logo-marquee__logo', true ); ?>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
endif;
