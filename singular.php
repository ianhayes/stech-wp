<?php
/**
 * Fallback for any singular view (post, program, staff, page).
 * CPTs get bespoke single-{type}.php later; this keeps output valid meanwhile.
 *
 * @package STECH
 */
defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) : the_post(); ?>
	<article <?php post_class( 'singular container' ); ?>>
		<header class="singular__head section-head">
			<h1 class="h1"><?php the_title(); ?></h1>
		</header>
		<div class="singular__body prose__body">
			<?php the_content(); ?>
		</div>
	</article>
<?php endwhile;
get_footer();
