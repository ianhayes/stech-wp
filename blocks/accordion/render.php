<?php
/**
 * Block: Accordion / FAQ (stech/accordion)
 * Expandable Q&A list. Matches .accordion in the brand components; blocks.js
 * toggles aria-expanded and animates .accordion__panel max-height.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'accordion_eyebrow' );
$heading = get_field( 'accordion_heading' );
$has_items = have_rows( 'accordion_items' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $heading && ! $has_items && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Accordion / FAQ — add a heading and question items.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'block' ); ?>>
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

		<?php if ( $has_items ) : ?>
			<div class="accordion">
				<?php
				$i = 0;
				while ( have_rows( 'accordion_items' ) ) :
					the_row();
					$question = get_sub_field( 'accordion_items_question' );
					$answer   = get_sub_field( 'accordion_items_answer' );
					if ( ! $question && ! $answer ) {
						continue;
					}
					$expanded = 0 === $i ? 'true' : 'false';
					$i++;
					?>
					<div class="accordion__item">
						<button class="accordion__trigger" aria-expanded="<?php echo esc_attr( $expanded ); ?>"><?php echo esc_html( $question ); ?><span class="accordion__icon"></span></button>
						<div class="accordion__panel">
							<div class="accordion__panel-inner"><?php echo wp_kses_post( $answer ); ?></div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
