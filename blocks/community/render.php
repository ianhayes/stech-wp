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
<section<?php stech_block_attrs( $block, 'community' ); ?>>
	<div class="container">
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
				<div class="community__card community__card--<?php echo esc_attr( $mod ); ?>"<?php echo $style; // esc'd above. ?>>
					<?php if ( $overline ) : ?>
						<span class="overline overline--on-dark"><?php echo esc_html( $overline ); ?></span>
					<?php endif; ?>
					<?php if ( $heading ) : ?>
						<h3><?php echo esc_html( $heading ); ?></h3>
					<?php endif; ?>
					<?php if ( $text ) : ?>
						<p><?php echo wp_kses_post( $text ); ?></p>
					<?php endif; ?>
					<?php echo stech_link_tag( $button, 'btn btn--ghost-white', __( 'Learn More', 'stech' ) ); // escaped in helper. ?>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
</section>
