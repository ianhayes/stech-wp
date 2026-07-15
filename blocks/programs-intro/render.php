<?php
/**
 * Block: Programs Intro (stech/programs-intro)
 * Two-column intro (text + CTA) beside three icon feature cards.
 * Matches .programs-intro in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'programs_intro_eyebrow' );
$heading = get_field( 'programs_intro_heading' );
$text    = get_field( 'programs_intro_text' );
$cta     = get_field( 'programs_intro_cta' );
$cards   = get_field( 'programs_intro_cards' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $eyebrow && ! $heading && ! $text && ! $cards && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Programs Intro — add a heading, text, CTA and up to three feature cards.', 'stech' ) . '</p>';
	return;
}

?>
<section<?php stech_block_attrs( $block, 'block programs-intro' ); ?>>
	<div class="container">
		<div class="programs-intro__inner">
			<div class="programs-intro__text">
				<?php if ( $eyebrow ) : ?>
					<span class="overline"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>

				<?php if ( $heading ) : ?>
					<h2 class="h2"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<p><?php echo wp_kses_post( $text ); ?></p>
				<?php endif; ?>

				<?php
				$c = stech_link( $cta );
				if ( $c ) :
					?>
					<a class="btn btn--primary" href="<?php echo $c['url']; // esc'd in helper. ?>"<?php echo $c['target'] . $c['rel']; ?>>
						<?php echo $c['title'] ?: esc_html__( 'Browse All', 'stech' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span>
					</a>
				<?php endif; ?>
			</div>

			<?php if ( have_rows( 'programs_intro_cards' ) ) : ?>
				<div class="programs-intro__cards">
					<?php
					while ( have_rows( 'programs_intro_cards' ) ) :
						the_row();
						$icon      = get_sub_field( 'programs_intro_cards_icon' );
						$card_head = get_sub_field( 'programs_intro_cards_heading' );
						$card_text = get_sub_field( 'programs_intro_cards_text' );
						?>
						<div class="programs-intro__card">
							<div class="programs-intro__icon">
								<?php echo stech_icon( $icon ?: 'prog-01' ); // phpcs:ignore WordPress.Security.EscapeOutput — trusted inline SVG. ?>
							</div>
							<div>
								<?php if ( $card_head ) : ?>
									<h4><?php echo esc_html( $card_head ); ?></h4>
								<?php endif; ?>
								<?php if ( $card_text ) : ?>
									<p><?php echo esc_html( $card_text ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
