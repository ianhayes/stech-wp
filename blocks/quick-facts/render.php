<?php
/**
 * Block: Quick Facts (stech/quick-facts)
 * Compact 6-up program facts strip. Matches .quick-facts in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$is_preview = ! empty( $block['is_preview'] );
$has_facts  = have_rows( 'quick_facts_facts' );

if ( ! $has_facts ) {
	if ( $is_preview ) {
		echo '<p class="acf-block-placeholder">' . esc_html__( 'Quick Facts — add facts (number + label).', 'stech' ) . '</p>';
	}
	return;
}
?>
<section<?php stech_block_attrs( $block, 'quick-facts' ); ?>>
	<div class="quick-facts__inner">
		<?php
		while ( have_rows( 'quick_facts_facts' ) ) :
			the_row();
			$num      = (string) get_sub_field( 'num' );
			$label    = (string) get_sub_field( 'label' );
			$is_text  = (bool) get_sub_field( 'num_is_text' );
			$num_class = 'quick-facts__num' . ( $is_text ? ' quick-facts__num--text' : '' );

			if ( '' === $num && '' === $label ) {
				continue;
			}
			?>
			<div class="quick-facts__item">
				<?php if ( '' !== $num ) : ?>
					<div class="<?php echo esc_attr( $num_class ); ?>"><?php echo esc_html( $num ); ?></div>
				<?php endif; ?>
				<?php if ( '' !== $label ) : ?>
					<div class="quick-facts__label"><?php echo esc_html( $label ); ?></div>
				<?php endif; ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>
