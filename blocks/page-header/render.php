<?php
/**
 * Block: Page Header (stech/page-header)
 * Interior page header (B2). Matches .page-header in the brand components.
 * Layouts: blue-photo (default), flat-wash, plain (no photo), with-form.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$layout    = get_field( 'page_header_layout' ) ?: 'blue-photo';
$bg        = get_field( 'page_header_bg_image' );
$title     = get_field( 'page_header_title' );
$lede      = get_field( 'page_header_lede' );
$primary   = get_field( 'page_header_primary_button' );
$secondary = get_field( 'page_header_secondary_button' );
$show_paw  = get_field( 'page_header_show_paw' );

$is_preview = ! empty( $block['is_preview'] );

// Layout → BEM modifier classes (mirrors the approved per-page recipes).
$modifiers = array(
	'blue-photo' => 'page-header--blue-photo',
	'flat-wash'  => 'page-header--blue-photo page-header--flat-wash',
	'plain'      => 'page-header--plain',
	'with-form'  => 'page-header--blue-photo page-header--flat-wash page-header--bg-top page-header--paw-bottom page-header--with-form',
);
$modifier = $modifiers[ $layout ] ?? $modifiers['blue-photo'];
$base     = 'page-header ' . $modifier;

$is_form   = 'with-form' === $layout;
$is_plain  = 'plain' === $layout;
$has_photo = ! $is_plain;

if ( ! $title && ! $lede && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Page Header — choose a layout and add a title, lede and CTAs.', 'stech' ) . '</p>';
	return;
}

/**
 * Left-column / core content shared by every layout.
 */
$render_content = static function () use ( $title, $lede, $primary, $secondary, $is_form, $is_plain ) {
	// On the light "plain" layout, ghost-white buttons would be invisible; use the
	// light-background button styles instead. Dark layouts keep the approved ghost-white.
	$btn_primary_class   = $is_plain ? 'btn btn--primary' : 'btn btn--ghost-white';
	$btn_secondary_class = $is_plain ? 'btn btn--ghost' : 'btn btn--ghost-white';
	stech_breadcrumb();

	if ( $title ) :
		?>
		<h1 class="h1 page-header__title"><?php echo esc_html( $title ); ?></h1>
	<?php endif; ?>

	<?php if ( $lede ) : ?>
		<p class="lede page-header__lede<?php echo $is_form ? ' page-header__lede--narrow' : ''; ?>"><?php echo wp_kses_post( $lede ); ?></p>
	<?php endif; ?>

	<?php
	if ( $is_form ) {
		$deadline = get_field( 'page_header_deadline_text' );
		if ( $deadline ) :
			?>
			<div class="page-header__deadline"><span class="page-header__deadline-dot" aria-hidden="true"></span><?php echo esc_html( $deadline ); ?></div>
		<?php endif;
	}
	?>

	<?php if ( $primary || $secondary ) : ?>
		<div class="page-header__cta">
			<?php
			$p = stech_link( $primary );
			if ( $p ) :
				?>
				<a class="<?php echo esc_attr( $btn_primary_class ); ?>" href="<?php echo $p['url']; // esc'd in helper. ?>"<?php echo $p['target'] . $p['rel']; ?>>
					<?php echo $p['title'] ?: esc_html__( 'Apply Now', 'stech' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span>
				</a>
			<?php endif; ?>
			<?php
			$s = stech_link( $secondary );
			if ( $s ) :
				?>
				<a class="<?php echo esc_attr( $btn_secondary_class ); ?>" href="<?php echo $s['url']; ?>"<?php echo $s['target'] . $s['rel']; ?>><?php echo $s['title']; ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php
	if ( $is_form && have_rows( 'page_header_facts' ) ) :
		?>
		<div class="page-header__facts">
			<?php
			while ( have_rows( 'page_header_facts' ) ) :
				the_row();
				$num   = get_sub_field( 'num' );
				$unit  = get_sub_field( 'unit' );
				$label = get_sub_field( 'label' );
				?>
				<div>
					<span class="page-header__fact-num"><?php echo esc_html( $num ); ?><?php if ( $unit ) : ?><span class="page-header__fact-unit"><?php echo esc_html( $unit ); ?></span><?php endif; ?></span>
					<span class="page-header__fact-label"><?php echo esc_html( $label ); ?></span>
				</div>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
	<?php
};

/**
 * Inquiry aside for the with-form layout: a Gravity Form when one is set,
 * otherwise the approved static markup as a visual fallback.
 */
$render_form = static function () {
	$fid       = get_field( 'page_header_form_id' );
	$overline  = get_field( 'page_header_form_overline' ) ?: __( 'Get Started', 'stech' );
	$heading   = get_field( 'page_header_form_heading' ) ?: __( 'Request Info', 'stech' );
	$intro     = get_field( 'page_header_form_intro' );
	?>
	<aside class="inquiry-form">
		<div class="inquiry-form__head">
			<span class="overline"><?php echo esc_html( $overline ); ?></span>
			<h3><?php echo esc_html( $heading ); ?></h3>
			<?php if ( $intro ) : ?>
				<p><?php echo esc_html( $intro ); ?></p>
			<?php endif; ?>
		</div>
		<?php
		if ( function_exists( 'gravity_form' ) && $fid ) {
			gravity_form( (int) $fid, false, false, false, null, true );
		} else {
			?>
			<div class="inquiry-form__row">
				<div class="inquiry-form__field"><label><?php esc_html_e( 'First name', 'stech' ); ?> <span class="req">*</span></label><input type="text"></div>
				<div class="inquiry-form__field"><label><?php esc_html_e( 'Last name', 'stech' ); ?> <span class="req">*</span></label><input type="text"></div>
			</div>
			<div class="inquiry-form__field"><label><?php esc_html_e( 'Email', 'stech' ); ?> <span class="req">*</span></label><input type="email"></div>
			<div class="inquiry-form__check"><input type="checkbox" id="ph-sms"><label for="ph-sms"><?php esc_html_e( 'Text me admissions updates', 'stech' ); ?></label></div>
			<a href="#" class="btn btn--primary btn--block"><?php esc_html_e( 'Request Info', 'stech' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span></a>
			<span class="inquiry-form__small"><?php esc_html_e( 'By submitting you agree to our', 'stech' ); ?> <a href="#"><?php esc_html_e( 'privacy policy', 'stech' ); ?></a>.</span>
			<?php
		}
		?>
	</aside>
	<?php
};
?>
<section<?php stech_block_attrs( $block, $base ); ?> aria-label="<?php esc_attr_e( 'Page header', 'stech' ); ?>">
	<?php if ( $has_photo && $bg && ! empty( $bg['ID'] ) ) : ?>
		<div class="page-header__bg"><?php echo wp_get_attachment_image( (int) $bg['ID'], 'stech-hero', false, array( 'alt' => '' ) ); ?></div>
	<?php endif; ?>

	<div class="container">
		<div class="page-header__inner">
			<?php if ( $is_form ) : ?>
				<div>
					<?php $render_content(); ?>
				</div>
				<?php $render_form(); ?>
			<?php else : ?>
				<?php $render_content(); ?>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $show_paw ) : ?>
		<div class="page-header__paw"><?php stech_the_svg( 'paw', array( 'aria-hidden' => 'true' ) ); ?></div>
	<?php endif; ?>
</section>
