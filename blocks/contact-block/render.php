<?php
/**
 * Block: Contact Block (stech/contact-block)
 * Two-column contact section — info + details beside a message form.
 * Matches .contact-block / .inquiry-form in the brand components.
 * Renders a Gravity Form when configured; otherwise falls back to the
 * approved static form markup (visual fallback only).
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$overline = get_field( 'contact_block_overline' );
$heading  = get_field( 'contact_block_heading' );
$text     = get_field( 'contact_block_text' );
$location = get_field( 'contact_block_location_label' );
$address  = get_field( 'contact_block_address' );
$phone    = get_field( 'contact_block_phone' );
$email    = get_field( 'contact_block_email' );

$form_overline = get_field( 'contact_block_form_overline' );
$form_heading  = get_field( 'contact_block_form_heading' );
$form_id       = get_field( 'contact_block_gravity_form_id' );
$form_button   = get_field( 'contact_block_form_button_label' );
$form_small    = get_field( 'contact_block_form_small' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $heading && ! $text && ! $phone && ! $email && ! $form_id && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Contact Block — add a heading, contact details and a Gravity Form ID.', 'stech' ) . '</p>';
	return;
}

$btn_label = $form_button ? $form_button : __( 'Send Message', 'stech' );
$tel_href  = $phone ? preg_replace( '/[^0-9+]/', '', $phone ) : '';
?>
<section<?php stech_block_attrs( $block, 'contact-block block' ); ?>>
	<div class="container">
		<div class="contact-block__inner">
			<div class="contact-block__info">
				<?php if ( $overline ) : ?>
					<span class="overline"><?php echo esc_html( $overline ); ?></span>
				<?php endif; ?>

				<?php if ( $heading ) : ?>
					<h2 class="h2"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<?php echo wp_kses_post( wpautop( $text ) ); ?>
				<?php endif; ?>

				<?php if ( $location || $address || $phone || $email ) : ?>
					<p>
						<?php if ( $location ) : ?>
							<strong><?php echo esc_html( $location ); ?></strong><br>
						<?php endif; ?>
						<?php if ( $address ) : ?>
							<?php echo nl2br( esc_html( $address ) ); ?><br>
						<?php endif; ?>
						<?php if ( $phone ) : ?>
							<a href="tel:<?php echo esc_attr( $tel_href ); ?>"><?php echo esc_html( $phone ); ?></a><br>
						<?php endif; ?>
						<?php if ( $email ) : ?>
							<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
						<?php endif; ?>
					</p>
				<?php endif; ?>
			</div>

			<aside class="inquiry-form">
				<?php if ( $form_overline || $form_heading ) : ?>
					<div class="inquiry-form__head">
						<?php if ( $form_overline ) : ?>
							<span class="overline"><?php echo esc_html( $form_overline ); ?></span>
						<?php endif; ?>
						<?php if ( $form_heading ) : ?>
							<h3><?php echo esc_html( $form_heading ); ?></h3>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( function_exists( 'gravity_form' ) && $form_id ) : ?>
					<?php gravity_form( (int) $form_id, false, false, false, null, true ); ?>
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
					<div class="inquiry-form__field">
						<label><?php esc_html_e( 'Email', 'stech' ); ?> <span class="req">*</span></label>
						<input type="email">
					</div>
					<div class="inquiry-form__field">
						<label><?php esc_html_e( 'Phone', 'stech' ); ?></label>
						<input type="tel">
					</div>
					<div class="inquiry-form__field">
						<label><?php esc_html_e( 'Message', 'stech' ); ?></label>
						<input type="text" placeholder="<?php esc_attr_e( 'Tell us what you\'re looking for', 'stech' ); ?>">
					</div>
					<a href="#" class="btn btn--primary btn--block"><?php echo esc_html( $btn_label ); ?> <span class="arrow" aria-hidden="true">&rarr;</span></a>
				<?php endif; ?>

				<?php if ( $form_small ) : ?>
					<span class="inquiry-form__small"><?php echo wp_kses_post( $form_small ); ?></span>
				<?php endif; ?>
			</aside>
		</div>
	</div>
</section>
