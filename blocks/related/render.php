<?php
/**
 * Block: Related Programs (stech/related)
 * 3-up grid of program cards. Matches .related + .program-card in the brand
 * components. Pulls from the Program CPT (with program_cluster term as the tag)
 * or from a manual repeater.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'related_eyebrow' );
$heading = get_field( 'related_heading' );
$source  = get_field( 'related_source' ) ?: 'manual';

$is_preview = ! empty( $block['is_preview'] );

/**
 * Build a normalised list of cards from either the Program CPT relationship
 * or the manual repeater. Each card: image_id, image_alt, tag, title, url.
 */
$cards = array();

if ( 'programs' === $source ) {
	$programs = get_field( 'related_programs' );
	if ( $programs ) {
		foreach ( $programs as $program ) {
			$pid   = is_object( $program ) ? $program->ID : (int) $program;
			$terms = get_the_terms( $pid, 'program_cluster' );
			$tag   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
			$cards[] = array(
				'image_id'  => get_post_thumbnail_id( $pid ),
				'image_alt' => get_the_title( $pid ),
				'tag'       => $tag,
				'title'     => get_the_title( $pid ),
				'url'       => get_permalink( $pid ),
			);
		}
	}
} elseif ( have_rows( 'related_cards' ) ) {
	while ( have_rows( 'related_cards' ) ) {
		the_row();
		$image = get_sub_field( 'related_cards_image' );
		$link  = stech_link( get_sub_field( 'related_cards_link' ) );
		$cards[] = array(
			'image_id'  => is_array( $image ) ? ( $image['ID'] ?? 0 ) : 0,
			'image_alt' => is_array( $image ) ? ( $image['alt'] ?? '' ) : '',
			'tag'       => get_sub_field( 'related_cards_tag' ),
			'title'     => get_sub_field( 'related_cards_title' ),
			'url'       => $link ? $link['url'] : '',
			'link'      => $link,
		);
	}
}

if ( ! $heading && ! $cards && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Related Programs — add a heading and program cards.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'block related' ); ?>>
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

		<?php if ( $cards ) : ?>
			<div class="related__grid">
				<?php
				foreach ( $cards as $card ) :
					$href    = $card['url'] ?: '#';
					$rel     = isset( $card['link'] ) && $card['link'] ? $card['link']['target'] . $card['link']['rel'] : '';
					$explore = isset( $card['link'] ) && $card['link'] && $card['link']['title'] ? $card['link']['title'] : __( 'Explore', 'stech' );
					?>
					<a class="program-card" href="<?php echo esc_url( $href ); ?>"<?php echo $rel; // esc'd in stech_link(). ?>>
						<?php
						if ( ! empty( $card['image_id'] ) ) {
							echo wp_get_attachment_image(
								(int) $card['image_id'],
								'stech-card-tall',
								false,
								array( 'alt' => $card['image_alt'] ) // wp_get_attachment_image escapes attrs.
							);
						}
						?>
						<div class="program-card__content">
							<?php if ( $card['tag'] ) : ?>
								<span class="program-card__tag"><?php echo esc_html( $card['tag'] ); ?></span>
							<?php endif; ?>
							<?php if ( $card['title'] ) : ?>
								<h3><?php echo esc_html( $card['title'] ); ?></h3>
							<?php endif; ?>
							<span class="program-card__arrow"><?php echo $explore; // link title esc'd in stech_link(); fallback is a static string. ?> &rarr;</span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
