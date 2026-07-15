<?php
/**
 * Block: Inquiry Form (stech/inquiry-form)
 * Request-info form card. Matches .inquiry-form in the brand components.
 * Renders a Gravity Form when configured; otherwise falls back to the
 * approved static form markup (visual fallback only).
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$overline = get_field( 'inquiry_form_overline' );
$heading  = get_field( 'inquiry_form_heading' );
$intro    = get_field( 'inquiry_form_intro' );
$large    = get_field( 'inquiry_form_large' );
$form_id  = get_field( 'inquiry_form_gravity_form_id' );
$btn      = get_field( 'inquiry_form_button_label' );
$small    = get_field( 'inquiry_form_small' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $overline && ! $heading && ! $intro && ! $form_id && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Inquiry Form — add a heading, intro and a Gravity Form ID.', 'stech' ) . '</p>';
	return;
}

$classes = 'inquiry-form inquiry-form--center';
if ( $large ) {
	$classes .= ' inquiry-form--lg';
}

$btn_label = $btn ? $btn : __( 'Request Info', 'stech' );
?>
<aside<?php stech_block_attrs( $block, $classes ); ?>>
	<?php if ( $overline || $heading || $intro ) : ?>
		<div class="inquiry-form__head">
			<?php if ( $overline ) : ?>
				<span class="overline"><?php echo esc_html( $overline ); ?></span>
			<?php endif; ?>
			<?php if ( $heading ) : ?>
				<h3><?php echo esc_html( $heading ); ?></h3>
			<?php endif; ?>
			<?php if ( $intro ) : ?>
				<p><?php echo wp_kses_post( $intro ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( function_exists( 'gravity_form' ) && $form_id ) : ?>
		<?php gravity_form( $form_id, false, false, false, null, true ); ?>
	<?php else : ?>
		<div class="inquiry-form__row">
			<div class="inquiry-form__field">
				<label><?php esc_html_e( 'First name', 'stech' ); ?> <span class="req">*</span></label>
				<input type="text">
			</div>
			<div class="inquiry-form__field">
				<label><?php esc_html_e( 'Last name', 'stech' ); ?> <span class="req">*</span></label>
				<input type="text">
			</div>
		</div>
		<?php if ( $large ) : ?>
			<div class="inquiry-form__row">
				<div class="inquiry-form__field">
					<label><?php esc_html_e( 'Email', 'stech' ); ?> <span class="req">*</span></label>
					<input type="email">
				</div>
				<div class="inquiry-form__field">
					<label><?php esc_html_e( 'Phone', 'stech' ); ?></label>
					<input type="tel">
				</div>
			</div>
		<?php else : ?>
			<div class="inquiry-form__field">
				<label><?php esc_html_e( 'Email', 'stech' ); ?> <span class="req">*</span></label>
				<input type="email">
			</div>
		<?php endif; ?>
		<div class="inquiry-form__field">
			<label><?php esc_html_e( 'Program of interest', 'stech' ); ?></label>
			<select>
				<option><?php esc_html_e( 'Practical Nursing', 'stech' ); ?></option>
				<option><?php esc_html_e( 'Welding', 'stech' ); ?></option>
				<option><?php esc_html_e( 'Culinary Arts', 'stech' ); ?></option>
				<option><?php esc_html_e( 'Information Technology', 'stech' ); ?></option>
			</select>
		</div>
		<a href="#" class="btn btn--primary btn--block"><?php echo esc_html( $btn_label ); ?> <span class="arrow" aria-hidden="true">&rarr;</span></a>
	<?php endif; ?>

	<?php if ( $small ) : ?>
		<span class="inquiry-form__small"><?php echo wp_kses_post( $small ); ?></span>
	<?php endif; ?>
</aside>
