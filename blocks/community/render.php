<?php
/**
 * Block: Community (stech/community)
 * Two-up promo cards on image/dark backgrounds. Matches .community in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$is_preview = ! empty( $block['is_preview'] );
$has_cards  = have_rows( 'community_cards' );

$section_overline = get_field( 'community_overline' );
$section_heading  = get_field( 'community_heading' );
$section_lede     = get_field( 'community_lede' );
$has_section_head = $section_overline || $section_heading || $section_lede;

if ( ! $has_cards && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Community — add up to two promo cards (overline, heading, text, button and background image).', 'stech' ) . '</p>';
	return;
}

if ( ! $has_cards ) {
	return;
}

// Card background/positioning modifiers, in order.
$modifiers = array( 'a', 'b' );
?>
<section<?php stech_block_attrs( $block, 'block community' ); ?>>
	<div class="container">
		<?php if ( $has_section_head ) : ?>
			<div class="section-head">
				<?php if ( $section_overline ) : ?>
					<span class="overline"><?php echo esc_html( $section_overline ); ?></span>
				<?php endif; ?>
				<?php if ( $section_heading ) : ?>
					<h2 class="h1"><?php echo esc_html( $section_heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $section_lede ) : ?>
					<p class="lede"><?php echo esc_html( $section_lede ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="community__grid">
			<?php
			$i = 0;
			while ( have_rows( 'community_cards' ) ) :
				the_row();
				$overline = get_sub_field( 'community_cards_overline' );
				$heading  = get_sub_field( 'community_cards_heading' );
				$text     = get_sub_field( 'community_cards_text' );
				$button   = get_sub_field( 'community_cards_button' );
				$image    = get_sub_field( 'community_cards_image' );

				$mod = $modifiers[ $i % count( $modifiers ) ];
				$i++;

				// Background image URL — prefer the named card size, fall back to full.
				$bg_url = '';
				if ( is_array( $image ) ) {
					$bg_url = $image['sizes']['stech-card'] ?? ( $image['url'] ?? '' );
				}

				$style = $bg_url ? ' style="--card-bg:url(\'' . esc_url( $bg_url ) . '\')"' : '';
				?>
				<article class="community__card community__card--<?php echo esc_attr( $mod ); ?>"<?php echo $style; // esc'd above. ?>>
					<?php if ( $overline ) : ?>
						<span class="overline"><?php echo esc_html( $overline ); ?></span>
					<?php endif; ?>
					<?php if ( $heading ) : ?>
						<h3><?php echo esc_html( $heading ); ?></h3>
					<?php endif; ?>
					<?php if ( $text ) : ?>
						<p><?php echo wp_kses_post( $text ); ?></p>
					<?php endif; ?>
					<?php
					$btn_link = stech_link( $button );
					if ( $btn_link ) :
						$btn_label = $btn_link['title'] ?: esc_html__( 'Learn More', 'stech' );
						?>
						<div><a class="btn btn--ghost-white btn--sm" href="<?php echo $btn_link['url']; // esc'd in helper. ?>"<?php echo $btn_link['target'] . $btn_link['rel']; // esc'd in helper. ?>><?php echo $btn_label; // esc'd. ?> <span class="arrow">&rarr;</span></a></div>
					<?php endif; ?>
				</article>
			<?php endwhile; ?>
		</div>
	</div>
</section>
