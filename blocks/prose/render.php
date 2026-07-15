<?php
/**
 * Block: Prose (stech/prose)
 * Rich long-form content, optionally with a sticky left image (prose--media).
 * Matches .prose / .prose--media in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$overline = get_field( 'prose_overline' );
$body     = get_field( 'prose_body' );
$media    = (bool) get_field( 'prose_media' );
$image    = get_field( 'prose_image' );

$has_image  = $media && $image && ! empty( $image['ID'] );
$is_preview = ! empty( $block['is_preview'] );

if ( ! $overline && ! $body && ! $has_image && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Prose — add rich content and an optional overline or image.', 'stech' ) . '</p>';
	return;
}

$base = 'prose' . ( $has_image ? ' prose--media' : '' );
?>
<section<?php stech_block_attrs( $block, 'block' ); ?>>
	<div class="container">
		<div class="<?php echo esc_attr( $base ); ?>">
			<?php if ( $has_image ) : ?>
				<div class="prose__media">
					<?php
					echo wp_get_attachment_image(
						(int) $image['ID'],
						'stech-card',
						false,
						array( 'alt' => $image['alt'] ?? '' )
					);
					?>
				</div>
				<div class="prose__body">
					<?php if ( $overline ) : ?>
						<span class="overline"><?php echo esc_html( $overline ); ?></span>
					<?php endif; ?>
					<?php echo wp_kses_post( $body ); ?>
				</div>
			<?php else : ?>
				<?php if ( $overline ) : ?>
					<span class="overline"><?php echo esc_html( $overline ); ?></span>
				<?php endif; ?>
				<?php echo wp_kses_post( $body ); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
