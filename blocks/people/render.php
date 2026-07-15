<?php
/**
 * Block: Faculty & Staff (stech/people)
 * Section header + grid of avatar person cards.
 * Cards come from the Staff CPT or a manual repeater.
 * Matches .people / .person-card in the brand components.
 *
 * @package STECH
 * @var array $block ACF block array.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = get_field( 'people_eyebrow' );
$heading  = get_field( 'people_heading' );
$source   = get_field( 'people_source' ) ?: 'manual';
$centered = (bool) get_field( 'people_centered' );
$count    = (int) get_field( 'people_count' );
$count    = $count > 0 ? $count : 8;

$is_preview  = ! empty( $block['is_preview'] );
$manual_mode = 'manual' === $source;

// Empty-state placeholder in the editor when there's nothing to show yet.
if ( $manual_mode && ! $heading && ! have_rows( 'people_cards' ) && $is_preview ) {
	echo '<p class="acf-block-placeholder">' . esc_html__( 'Faculty & Staff — add a heading and person cards.', 'stech' ) . '</p>';
	return;
}

$grid_class = 'people__grid' . ( $centered ? ' people__grid--center' : '' );

/**
 * Render a single person card.
 *
 * @param array $args { name, role, bio, image_html }.
 */
$render_card = static function ( $args ) {
	$name       = $args['name'] ?? '';
	$role       = $args['role'] ?? '';
	$bio        = $args['bio'] ?? '';
	$image_html = $args['image_html'] ?? '';
	?>
	<div class="person-card">
		<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput — wp_get_attachment_image output. ?>
		<?php if ( $name ) : ?>
			<h3><?php echo esc_html( $name ); ?></h3>
		<?php endif; ?>
		<?php if ( $role ) : ?>
			<span class="person-card__role"><?php echo esc_html( $role ); ?></span>
		<?php endif; ?>
		<?php if ( $bio ) : ?>
			<p><?php echo esc_html( $bio ); ?></p>
		<?php endif; ?>
	</div>
	<?php
};
?>
<section<?php stech_block_attrs( $block, 'people block' ); ?>>
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

		<div class="<?php echo esc_attr( $grid_class ); ?>">
			<?php
			if ( $manual_mode && have_rows( 'people_cards' ) ) :
				while ( have_rows( 'people_cards' ) ) :
					the_row();
					$image = get_sub_field( 'people_cards_avatar' );

					$image_html = '';
					if ( is_array( $image ) && ! empty( $image['ID'] ) ) {
						$image_html = wp_get_attachment_image(
							(int) $image['ID'],
							'stech-avatar',
							false,
							array(
								'class' => 'person-card__avatar',
								'alt'   => $image['alt'] ?? '',
							)
						);
					}

					$render_card( array(
						'name'       => get_sub_field( 'people_cards_name' ),
						'role'       => get_sub_field( 'people_cards_role' ),
						'bio'        => get_sub_field( 'people_cards_bio' ),
						'image_html' => $image_html,
					) );
				endwhile;
			elseif ( 'staff' === $source ) :
				$staff = new WP_Query( array(
					'post_type'      => 'staff',
					'posts_per_page' => $centered ? 1 : $count,
					'post_status'    => 'publish',
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'no_found_rows'  => true,
				) );

				if ( $staff->have_posts() ) :
					while ( $staff->have_posts() ) :
						$staff->the_post();

						$image_html = '';
						if ( has_post_thumbnail() ) {
							$image_html = get_the_post_thumbnail(
								get_the_ID(),
								'stech-avatar',
								array(
									'class' => 'person-card__avatar',
									'alt'   => get_the_title(),
								)
							);
						}

						$depts = get_the_terms( get_the_ID(), 'staff_department' );
						$role  = ( $depts && ! is_wp_error( $depts ) ) ? $depts[0]->name : '';

						$render_card( array(
							'name'       => get_the_title(),
							'role'       => $role,
							'bio'        => wp_trim_words( get_the_excerpt(), 20 ),
							'image_html' => $image_html,
						) );
					endwhile;
					wp_reset_postdata();
				elseif ( $is_preview ) :
					echo '<p class="acf-block-placeholder">' . esc_html__( 'Faculty & Staff — no staff members published yet.', 'stech' ) . '</p>';
				endif;
			endif;
			?>
		</div>
	</div>
</section>
