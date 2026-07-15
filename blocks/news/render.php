<?php
/**
 * Block: News Grid (stech/news)
 * Section header + "All News" button and a 3-up grid of news cards.
 * Cards come from the latest posts (default) or a manual repeater.
 * Matches .news / .news-card in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'news_eyebrow' );
$heading = get_field( 'news_heading' );
$lede    = get_field( 'news_lede' );
$cta     = get_field( 'news_cta' );
$source  = get_field( 'news_source' ) ?: 'posts';
$count   = (int) get_field( 'news_count' );
$count   = $count > 0 ? $count : 3;

$is_preview  = ! empty( $block['is_preview'] );
$manual_mode = 'manual' === $source;
$has_cards   = $manual_mode && have_rows( 'news_cards' );

// Empty-state placeholder in the editor when there's nothing to show yet.
if ( $manual_mode && ! $heading && ! $has_cards && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'News Grid — add a heading and news cards.', 'stech' ) . '</p>';
	return;
}

/**
 * Render a single news card.
 *
 * @param array  $args { tag, heading, excerpt, href, rel, image_html }.
 */
$render_card = static function ( $args ) {
	$tag        = $args['tag'] ?? '';
	$card_head  = $args['heading'] ?? '';
	$excerpt    = $args['excerpt'] ?? '';
	$href       = $args['href'] ?? '';
	$rel        = $args['rel'] ?? '';
	$image_html = $args['image_html'] ?? '';
	?>
	<article class="news-card">
		<?php if ( $image_html ) : ?>
			<div class="news-card__img"><?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput — wp_get_attachment_image output. ?></div>
		<?php endif; ?>
		<div class="news-card__body">
			<?php if ( $tag ) : ?>
				<span class="news-card__tag"><?php echo esc_html( $tag ); ?></span>
			<?php endif; ?>
			<?php if ( $card_head ) : ?>
				<h3>
					<?php if ( $href ) : ?>
						<a href="<?php echo esc_url( $href ); ?>"<?php echo $rel; // esc'd in helper. ?>><?php echo esc_html( $card_head ); ?></a>
					<?php else : ?>
						<?php echo esc_html( $card_head ); ?>
					<?php endif; ?>
				</h3>
			<?php endif; ?>
			<?php if ( $excerpt ) : ?>
				<p><?php echo esc_html( $excerpt ); ?></p>
			<?php endif; ?>
		</div>
	</article>
	<?php
};
?>
<section<?php stech_block_attrs( $block, 'news' ); ?>>
	<div class="container">
		<?php if ( $eyebrow || $heading || $lede || $cta ) : ?>
			<div class="news__head">
				<div class="news__head-text">
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
				<?php echo stech_link_tag( $cta, 'btn btn--ghost', __( 'All News', 'stech' ) ); // phpcs:ignore WordPress.Security.EscapeOutput — escaped in helper. ?>
			</div>
		<?php endif; ?>

		<div class="news__grid">
			<?php
			if ( $manual_mode && have_rows( 'news_cards' ) ) :
				while ( have_rows( 'news_cards' ) ) :
					the_row();
					$image = get_sub_field( 'news_cards_image' );
					$link  = stech_link( get_sub_field( 'news_cards_link' ) );

					$image_html = '';
					if ( is_array( $image ) && ! empty( $image['ID'] ) ) {
						$image_html = wp_get_attachment_image(
							(int) $image['ID'],
							'stech-card',
							false,
							array( 'alt' => $image['alt'] ?? '' )
						);
					}

					$render_card( array(
						'tag'        => get_sub_field( 'news_cards_tag' ),
						'heading'    => get_sub_field( 'news_cards_heading' ),
						'excerpt'    => get_sub_field( 'news_cards_excerpt' ),
						'href'       => $link ? $link['url'] : '',
						'rel'        => $link ? $link['target'] . $link['rel'] : '',
						'image_html' => $image_html,
					) );
				endwhile;
			else :
				$news = new WP_Query( array(
					'post_type'           => 'post',
					'posts_per_page'      => $count,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
				) );

				if ( $news->have_posts() ) :
					while ( $news->have_posts() ) :
						$news->the_post();

						$image_html = '';
						if ( has_post_thumbnail() ) {
							$image_html = get_the_post_thumbnail( get_the_ID(), 'stech-card', array( 'alt' => get_the_title() ) );
						}

						$cats = get_the_category();
						$tag  = ! empty( $cats ) ? $cats[0]->name : '';

						$render_card( array(
							'tag'        => $tag,
							'heading'    => get_the_title(),
							'excerpt'    => wp_trim_words( get_the_excerpt(), 20 ),
							'href'       => get_permalink(),
							'rel'        => '',
							'image_html' => $image_html,
						) );
					endwhile;
					wp_reset_postdata();
				elseif ( $is_preview ) :
					echo '<p class="acf-block-placeholder">' . esc_html__( 'News Grid — no posts published yet.', 'stech' ) . '</p>';
				endif;
			endif;
			?>
		</div>
	</div>
</section>
