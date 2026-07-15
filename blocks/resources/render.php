<?php
/**
 * Block: Resources (stech/resources)
 * A list of downloadable resources. Matches .resources in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'resources_eyebrow' );
$heading = get_field( 'resources_heading' );

$is_preview = ! empty( $block['is_preview'] );
$has_items  = have_rows( 'resources_items' );

if ( ! $heading && ! $has_items && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Resources — add a heading and one or more downloadable items.', 'stech' ) . '</p>';
	return;
}
?>
<section<?php stech_block_attrs( $block, 'resources' ); ?>>
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
			<div class="resources__list">
				<?php
				while ( have_rows( 'resources_items' ) ) :
					the_row();
					$title = get_sub_field( 'resources_items_title' );
					$meta  = get_sub_field( 'resources_items_meta' );
					$file  = get_sub_field( 'resources_items_file' );
					$url   = ! empty( $file['url'] ) ? esc_url( $file['url'] ) : '';
					?>
					<a class="resource" href="<?php echo $url ?: '#'; ?>"<?php echo $url ? ' download' : ''; ?>>
						<span class="resource__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24"><path d="M6 2h9l5 5v15H6z"/><path d="M15 2v5h5"/></svg>
						</span>
						<span class="resource__body">
							<?php if ( $title ) : ?>
								<span class="resource__title"><?php echo esc_html( $title ); ?></span>
							<?php endif; ?>
							<?php if ( $meta ) : ?>
								<span class="resource__meta"><?php echo esc_html( $meta ); ?></span>
							<?php endif; ?>
						</span>
						<span class="resource__arrow" aria-hidden="true">
							<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
						</span>
					</a>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
