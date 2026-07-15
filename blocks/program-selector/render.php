<?php
/**
 * Block: Program Selector (stech/program-selector)
 * Category filter buttons (aria-pressed) + paginated program-card carousel.
 * Matches .programs + .carousel in the brand components. Page-scoped JS reads the
 * [data-filters]/[data-track]/[data-dots]/[data-prev]/[data-next] hooks and the
 * per-card data-category to filter and paginate.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'program_selector_eyebrow' );
$heading = get_field( 'program_selector_heading' );
$lede    = get_field( 'program_selector_lede' );
$footer  = get_field( 'program_selector_footer_button' );

$is_preview = ! empty( $block['is_preview'] );
$has_cards  = have_rows( 'program_selector_cards' );

if ( ! $heading && ! $has_cards && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Program Selector — add a heading, category filters and program cards.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'block programs program-selector' ); ?>>
	<div class="container">
		<?php if ( $eyebrow || $heading || $lede ) : ?>
			<div class="section-head">
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

		<div class="programs__filters" data-filters>
			<?php
			if ( have_rows( 'program_selector_filters' ) ) :
				$fi = 0;
				while ( have_rows( 'program_selector_filters' ) ) :
					the_row();
					$fi++;
					$f_label    = get_sub_field( 'program_selector_filters_label' );
					$f_category  = get_sub_field( 'program_selector_filters_category' );
					if ( ! $f_label ) {
						continue;
					}
					$pressed = 1 === $fi ? 'true' : 'false';
					?>
					<button class="programs__filter" data-filter="<?php echo esc_attr( $f_category ?: 'all' ); ?>" aria-pressed="<?php echo esc_attr( $pressed ); ?>"><?php echo esc_html( $f_label ); ?></button>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>

		<div class="carousel">
			<button class="carousel__nav carousel__nav--prev" data-prev aria-label="<?php esc_attr_e( 'Previous', 'stech' ); ?>"><?php echo stech_icon( 'nav-01' ); // phpcs:ignore WordPress.Security.EscapeOutput — trusted theme asset. ?></button>
			<div class="carousel__viewport">
				<div class="carousel__track" data-track>
					<?php if ( have_rows( 'program_selector_cards' ) ) : ?>
						<div class="carousel__page">
							<?php
							while ( have_rows( 'program_selector_cards' ) ) :
								the_row();
								$c_image    = get_sub_field( 'program_selector_cards_image' );
								$c_tag      = get_sub_field( 'program_selector_cards_tag' );
								$c_category = get_sub_field( 'program_selector_cards_category' );
								$c_title    = get_sub_field( 'program_selector_cards_title' );
								$c_link     = get_sub_field( 'program_selector_cards_link' );

								$link = stech_link( $c_link );
								$href = $link ? $link['url'] : '#';
								$rel  = $link ? $link['target'] . $link['rel'] : '';
								?>
								<a class="program-card" data-category="<?php echo esc_attr( $c_category ?: 'all' ); ?>" href="<?php echo esc_url( $href ); ?>"<?php echo $rel; // esc'd in helper. ?>>
									<?php
									if ( is_array( $c_image ) && ! empty( $c_image['ID'] ) ) {
										echo wp_get_attachment_image(
											(int) $c_image['ID'],
											'stech-card',
											false,
											array( 'alt' => $c_image['alt'] ?? '' )
										);
									}
									?>
									<div class="program-card__content">
										<?php if ( $c_tag ) : ?>
											<span class="program-card__tag"><?php echo esc_html( $c_tag ); ?></span>
										<?php endif; ?>
										<?php if ( $c_title ) : ?>
											<h3><?php echo esc_html( $c_title ); ?></h3>
										<?php endif; ?>
										<span class="program-card__arrow"><?php echo $link && $link['title'] ? $link['title'] : esc_html__( 'Explore', 'stech' ); ?> &rarr;</span>
									</div>
								</a>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<button class="carousel__nav carousel__nav--next" data-next aria-label="<?php esc_attr_e( 'Next', 'stech' ); ?>"><?php echo stech_icon( 'nav-02' ); // phpcs:ignore WordPress.Security.EscapeOutput — trusted theme asset. ?></button>
			<div class="carousel__pagination" data-dots></div>
		</div>

		<?php
		$foot = stech_link( $footer );
		if ( $foot ) :
			?>
			<div class="programs__footer">
				<a class="btn btn--ghost" href="<?php echo $foot['url']; // esc'd in helper. ?>"<?php echo $foot['target'] . $foot['rel']; ?>>
					<?php echo $foot['title'] ?: esc_html__( 'View All Certificates', 'stech' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>
