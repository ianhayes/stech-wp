<?php
/**
 * Default page template — block-composed via the editor (ACF blocks).
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

get_footer();
