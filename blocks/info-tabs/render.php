<?php
/**
 * Block: Info Tabs (stech/info-tabs)
 * Vertical tabbed information block. Matches .info-tabs in the brand components.
 * Page-scoped JS wires the tabs via data-tab / data-panel + aria-selected / aria-hidden.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow    = get_field( 'info_tabs_eyebrow' );
$heading    = get_field( 'info_tabs_heading' );
$has_tabs   = have_rows( 'info_tabs_tabs' );
$is_preview = ! empty( $block['is_preview'] );

if ( ! $eyebrow && ! $heading && ! $has_tabs ) {
	if ( $is_preview ) {
		echo '<p class="acf-block-placeholder">' . esc_html__( 'Info Tabs — add a heading and one or more tabs with panel content.', 'stech' ) . '</p>';
	}
	return;
}

// Unique, stable id base so multiple blocks on a page do not collide.
$uid = 'it-' . substr( md5( $block['id'] ?? uniqid( '', true ) ), 0, 8 );
$i   = 0;
?>
<section<?php stech_block_attrs( $block, 'block info-tabs' ); ?> aria-label="<?php esc_attr_e( 'Program details', 'stech' ); ?>">
	<div class="info-tabs__container">
		<?php if ( $eyebrow || $heading ) : ?>
			<div class="info-tabs__head">
				<?php if ( $eyebrow ) : ?>
					<span class="overline"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="h1"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $has_tabs ) : ?>
			<div class="info-tabs__layout">
				<div class="info-tabs__nav" role="tablist" aria-label="<?php esc_attr_e( 'Program details tabs', 'stech' ); ?>" aria-orientation="vertical">
					<?php
					while ( have_rows( 'info_tabs_tabs' ) ) :
						the_row();
						$label = get_sub_field( 'info_tabs_tabs_label' );
						$tid   = $uid . '-' . $i;
						?>
						<button type="button" class="info-tabs__btn" id="btn-<?php echo esc_attr( $tid ); ?>" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="tab-<?php echo esc_attr( $tid ); ?>" data-tab="<?php echo esc_attr( $tid ); ?>"><?php echo esc_html( $label ); ?></button>
						<?php
						$i++;
					endwhile;
					?>
				</div>
				<div class="info-tabs__panels">
					<?php
					$i = 0;
					while ( have_rows( 'info_tabs_tabs' ) ) :
						the_row();
						$panel      = get_sub_field( 'info_tabs_tabs_panel' );
						$alert      = get_sub_field( 'info_tabs_tabs_alert' );
						$alert_icon = get_sub_field( 'info_tabs_tabs_alert_icon' );
						$tid        = $uid . '-' . $i;
						?>
						<div class="info-tabs__panel" id="tab-<?php echo esc_attr( $tid ); ?>" role="tabpanel" aria-labelledby="btn-<?php echo esc_attr( $tid ); ?>" data-panel="<?php echo esc_attr( $tid ); ?>" aria-hidden="<?php echo 0 === $i ? 'false' : 'true'; ?>">
							<?php if ( $alert ) : ?>
								<div class="info-tabs__panel__alert">
									<?php echo stech_icon( $alert_icon ?: 'alert-01' ); // phpcs:ignore WordPress.Security.EscapeOutput — trusted theme asset. ?>
									<span><?php echo wp_kses_post( $alert ); ?></span>
								</div>
							<?php endif; ?>
							<?php echo wp_kses_post( $panel ); ?>
						</div>
						<?php
						$i++;
					endwhile;
					?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
