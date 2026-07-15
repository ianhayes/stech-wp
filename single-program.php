<?php
/**
 * Single Program — block-composed (page-header, info-tabs, outcomes, …).
 * Renders the block content directly like a page so full-bleed blocks work;
 * no wrapping container or duplicate title.
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
