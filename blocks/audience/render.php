<?php
/**
 * Block: Audience Qualifier (stech/audience)
 * "Find your path" — 3-up numbered image cards. Matches .audience in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'audience_eyebrow' );
$heading = get_field( 'audience_heading' );
$lede    = get_field( 'audience_lede' );

$is_preview = ! empty( $block['is_preview'] );
$has_cards  = have_rows( 'audience_cards' );

if ( ! $heading && ! $has_cards && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Audience Qualifier — add a heading and audience cards.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'audience' ); ?>>
	<div class="container">
		<?php if ( $eyebrow || $heading || $lede ) : ?>
			<div class="audience__head">
				<?php if ( $eyebrow ) : ?>
					<span class="overline"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="h2"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $lede ) : ?>
					<p class="lede"><?php echo esc_html( $lede ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( have_rows( 'audience_cards' ) ) : ?>
			<div class="audience__grid">
				<?php
				$i = 0;
				while ( have_rows( 'audience_cards' ) ) :
					the_row();
					$i++;
					$card_heading = get_sub_field( 'audience_cards_heading' );
					$card_text    = get_sub_field( 'audience_cards_text' );
					$card_link    = get_sub_field( 'audience_cards_link' );
					$card_number  = get_sub_field( 'audience_cards_number' );
					$card_image   = get_sub_field( 'audience_cards_image' );

					$num = $card_number ? $card_number : sprintf( '%02d', $i );

					// Background image URL — prefer the named size, fall back to full.
					$bg_url = '';
					if ( is_array( $card_image ) ) {
						$bg_url = $card_image['sizes']['stech-card-tall'] ?? ( $card_image['url'] ?? '' );
					}

					$link = stech_link( $card_link );
					$href = $link ? $link['url'] : '#';
					$rel  = $link ? $link['target'] . $link['rel'] : '';
					?>
					<a class="audience__card" data-num="<?php echo esc_attr( $num ); ?>" href="<?php echo $href; // esc'd in helper. ?>"<?php echo $rel; // esc'd in helper. ?>>
						<?php if ( $bg_url ) : ?>
							<span class="audience__bg" style="background-image:url('<?php echo esc_url( $bg_url ); ?>')"></span>
						<?php endif; ?>
						<?php if ( $card_heading ) : ?>
							<h3><?php echo esc_html( $card_heading ); ?></h3>
						<?php endif; ?>
						<?php if ( $card_text ) : ?>
							<p><?php echo esc_html( $card_text ); ?></p>
						<?php endif; ?>
						<span class="card-link"><?php echo $link && $link['title'] ? $link['title'] : esc_html__( 'Explore', 'stech' ); // title esc'd in helper. ?> <span class="arrow" aria-hidden="true">&rarr;</span></span>
					</a>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
