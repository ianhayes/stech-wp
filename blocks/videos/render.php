<?php
/**
 * Block: Highlight Videos (stech/videos)
 * Two-up grid of video cards with thumbnail, tag and play button overlay.
 * Matches .videos in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow    = get_field( 'videos_eyebrow' );
$heading    = get_field( 'videos_heading' );
$has_cards  = have_rows( 'videos_cards' );
$is_preview = ! empty( $block['is_preview'] );

if ( ! $heading && ! $eyebrow && ! $has_cards ) {
	if ( $is_preview ) {
		echo '<p class="acf-block-placeholder">' . esc_html__( 'Highlight Videos — add a heading and video cards.', 'stech' ) . '</p>';
	}
	return;
}
?>
<section<?php stech_block_attrs( $block, 'videos' ); ?>>
	<div class="container">
		<?php if ( $eyebrow || $heading ) : ?>
			<div class="section-head">
				<?php if ( $eyebrow ) : ?>
					<span class="overline"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="h2"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $has_cards ) : ?>
			<div class="videos__grid">
				<?php
				while ( have_rows( 'videos_cards' ) ) :
					the_row();
					$thumb = get_sub_field( 'videos_card_thumb' );
					$tag   = get_sub_field( 'videos_card_tag' );
					$title = get_sub_field( 'videos_card_title' );
					$url   = get_sub_field( 'videos_card_url' );
					?>
					<a class="video-card" href="<?php echo esc_url( $url ?: '#' ); ?>">
						<div class="video-card__thumb">
							<?php
							if ( $thumb && ! empty( $thumb['ID'] ) ) {
								echo wp_get_attachment_image(
									(int) $thumb['ID'],
									'stech-card',
									false,
									array( 'alt' => $thumb['alt'] ?? '' )
								);
							}
							?>
							<span class="video-card__play" aria-hidden="true"><?php echo stech_icon( 'play-01' ); // phpcs:ignore WordPress.Security.EscapeOutput — trusted theme asset. ?></span>
						</div>
						<div class="video-card__body">
							<?php if ( $tag ) : ?>
								<span class="video-card__tag"><?php echo esc_html( $tag ); ?></span>
							<?php endif; ?>
							<?php if ( $title ) : ?>
								<h3><?php echo esc_html( $title ); ?></h3>
							<?php endif; ?>
						</div>
					</a>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
