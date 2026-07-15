<?php
/**
 * Block: Lead-in (stech/lead-in)
 * Left-aligned intro text. Matches .lead-in in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$overline = get_field( 'lead_in_overline' );
$heading  = get_field( 'lead_in_heading' );
$body     = get_field( 'lead_in_body' );
$center   = get_field( 'lead_in_center' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $overline && ! $heading && ! $body && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Lead-in — add an overline, heading and intro text.', 'stech' ) . '</p>';
	return;
}

$extra = $center ? 'lead-in lead-in--center' : 'lead-in';
?>
<section<?php stech_block_attrs( $block, 'block' ); ?>>
	<div class="container">
		<div class="<?php echo esc_attr( $extra ); ?>">
			<?php if ( $overline ) : ?>
				<span class="overline"><?php echo esc_html( $overline ); ?></span>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h2 class="h2"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<?php if ( $body ) : ?>
				<?php echo wp_kses_post( $body ); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
