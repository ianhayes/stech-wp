<?php
/**
 * Block: CTA Banner (stech/cta-banner)
 * Dark full-width call-to-action. Matches .cta-banner in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$heading   = get_field( 'cta_banner_heading' );
$text      = get_field( 'cta_banner_text' );
$primary   = get_field( 'cta_banner_primary_button' );
$secondary = get_field( 'cta_banner_secondary_button' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $heading && ! $text && ! $primary && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'CTA Banner — add a heading, text and button.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'cta-banner' ); ?>>
	<div class="container">
		<?php if ( $heading ) : ?>
			<h2 class="h1"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<?php if ( $text ) : ?>
			<p><?php echo wp_kses_post( $text ); ?></p>
		<?php endif; ?>

		<?php if ( $primary || $secondary ) : ?>
			<div class="cta-banner__actions">
				<?php
				$p = stech_link( $primary );
				if ( $p ) :
					?>
					<a class="btn btn--primary" href="<?php echo $p['url']; // esc'd in helper. ?>"<?php echo $p['target'] . $p['rel']; ?>>
						<?php echo $p['title'] ?: esc_html__( 'Get Started', 'stech' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span>
					</a>
				<?php endif; ?>
				<?php
				$s = stech_link( $secondary );
				if ( $s ) :
					?>
					<a class="btn btn--ghost-white" href="<?php echo $s['url']; ?>"<?php echo $s['target'] . $s['rel']; ?>><?php echo $s['title']; ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
