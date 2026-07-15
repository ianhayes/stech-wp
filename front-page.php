<?php
/**
 * Front page — block-composed home. Falls back to latest posts if unset.
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
