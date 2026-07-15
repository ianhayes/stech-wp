<?php
/**
 * Block: Statement (stech/statement)
 * Large blockquote on dark with a bear.svg watermark. Matches .statement in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$quote    = get_field( 'statement_quote' );
$citation = get_field( 'statement_citation' );
$show_bg  = get_field( 'statement_show_bg_icon' );

$is_preview = ! empty( $block['is_preview'] );

if ( ! $quote && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Statement — add a quote and citation.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'statement' ); ?>>
	<?php if ( $show_bg ) : ?>
		<div class="statement__bg-icon"><img src="<?php echo esc_url( STECH_IMG . '/shared/bear.svg' ); ?>" alt=""></div>
	<?php endif; ?>
	<div class="container">
		<div class="statement__inner">
			<span class="statement__mark" aria-hidden="true">&ldquo;</span>
			<?php if ( $quote ) : ?>
				<blockquote><?php echo esc_html( $quote ); ?></blockquote>
			<?php endif; ?>
			<?php if ( $citation ) : ?>
				<span class="statement__cite"><?php echo esc_html( $citation ); ?></span>
			<?php endif; ?>
		</div>
	</div>
</section>
