<?php
/**
 * Block: Homepage Hero (stech/hero)
 * Full-bleed photo hero with paw mascot overlay. Matches .hero in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$bg       = get_field( 'hero_bg_image' );
$pill     = get_field( 'hero_pill' );
$headline = get_field( 'hero_headline' );
$accent   = get_field( 'hero_headline_accent' );
$text     = get_field( 'hero_text' );
$primary  = get_field( 'hero_primary_button' );
$ghost    = get_field( 'hero_ghost_button' );
$mascot   = get_field( 'hero_show_mascot' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $headline && ! $text && ! $pill && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Homepage Hero — add a background image, headline and CTAs.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'hero' ); ?> aria-label="<?php echo esc_attr__( 'Welcome banner', 'stech' ); ?>">
	<?php if ( $bg && ! empty( $bg['ID'] ) ) : ?>
		<div class="hero__bg"><?php echo wp_get_attachment_image( (int) $bg['ID'], 'stech-hero', false, array( 'alt' => '' ) ); ?></div>
	<?php endif; ?>

	<div class="container">
		<div class="hero__caption">
			<?php if ( $pill ) : ?>
				<span class="hero__pill"><?php echo esc_html( $pill ); ?></span>
			<?php endif; ?>

			<?php if ( $headline || $accent ) : ?>
				<h1 class="h-display">
					<?php echo esc_html( $headline ); ?>
					<?php if ( $accent ) : ?>
						<span class="accent"><?php echo esc_html( $accent ); ?></span>
					<?php endif; ?>
				</h1>
			<?php endif; ?>

			<?php if ( $text ) : ?>
				<p><?php echo wp_kses_post( $text ); ?></p>
			<?php endif; ?>

			<?php if ( $primary || $ghost ) : ?>
				<div class="hero__cta">
					<?php
					$p = stech_link( $primary );
					if ( $p ) :
						?>
						<a class="btn btn--primary" href="<?php echo $p['url']; // esc'd in helper. ?>"<?php echo $p['target'] . $p['rel']; ?>>
							<?php echo $p['title'] ?: esc_html__( 'Explore Programs', 'stech' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span>
						</a>
					<?php endif; ?>
					<?php
					$g = stech_link( $ghost );
					if ( $g ) :
						?>
						<a class="btn btn--ghost-white" href="<?php echo $g['url']; ?>"<?php echo $g['target'] . $g['rel']; ?>><?php echo $g['title']; ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $mascot ) : ?>
		<div class="hero__mascot" aria-hidden="true" style="--mascot-right:5%; --mascot-bottom:-40px; --mascot-width:clamp(180px,22vw,320px);"><?php stech_the_svg( 'paw', array( 'aria-hidden' => 'true' ) ); ?></div>
	<?php endif; ?>
</section>
