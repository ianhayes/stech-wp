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
<section<?php stech_block_attrs( $block, 'block resources' ); ?>>
	<div class="container">
		<?php if ( $eyebrow || $heading ) : ?>
			<div class="news__head" style="margin-bottom:24px">
				<div class="news__head-text">
					<?php if ( $eyebrow ) : ?>
						<span class="overline"><?php echo esc_html( $eyebrow ); ?></span>
					<?php endif; ?>
					<?php if ( $heading ) : ?>
						<h2 class="h2"><?php echo esc_html( $heading ); ?></h2>
					<?php endif; ?>
				</div>
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
						<span class="resource__icon"><?php echo stech_icon( 'resicon-01' ); ?></span>
						<span class="resource__body">
							<?php if ( $title ) : ?>
								<span class="resource__title"><?php echo esc_html( $title ); ?></span>
							<?php endif; ?>
							<?php if ( $meta ) : ?>
								<span class="resource__meta"><?php echo esc_html( $meta ); ?></span>
							<?php endif; ?>
						</span>
						<span class="resource__arrow"><?php echo stech_icon( 'resarrow-01' ); ?></span>
					</a>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
