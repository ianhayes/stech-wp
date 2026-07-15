<?php
/**
 * Custom post types + taxonomies.
 *
 * The theme registers only what isn't already owned by a kept plugin:
 *   - Programs (custom) + Cluster / Audience taxonomies
 *   - Staff / Faculty (custom) + Department taxonomy
 * Events stay with The Events Calendar (tribe_events); Jobs with WP Job Manager
 * (job_listing); News uses core Posts (relabelled below).
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {

	register_post_type( 'program', array(
		'labels'        => stech_pt_labels( 'Program', 'Programs' ),
		'public'        => true,
		'has_archive'   => 'programs',
		'menu_icon'     => 'dashicons-welcome-learn-more',
		'menu_position' => 20,
		'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
		'show_in_rest'  => true,
		'rewrite'       => array( 'slug' => 'programs', 'with_front' => false ),
	) );

	register_post_type( 'staff', array(
		'labels'        => stech_pt_labels( 'Staff Member', 'Faculty & Staff' ),
		'public'        => true,
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-groups',
		'menu_position' => 21,
		'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		'show_in_rest'  => true,
		'rewrite'       => array( 'slug' => 'faculty-staff', 'with_front' => false ),
	) );

	register_taxonomy( 'program_cluster', 'program', array(
		'labels'            => stech_tax_labels( 'Cluster', 'Program Clusters' ),
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'programs/cluster', 'with_front' => false ),
	) );

	register_taxonomy( 'program_audience', 'program', array(
		'labels'            => stech_tax_labels( 'Audience', 'Audiences' ),
		'hierarchical'      => false,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'programs/for', 'with_front' => false ),
	) );

	register_taxonomy( 'staff_department', 'staff', array(
		'labels'            => stech_tax_labels( 'Department', 'Departments' ),
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'faculty-staff/department', 'with_front' => false ),
	) );
} );

/**
 * Relabel core Posts as "News" to match the IA without a separate CPT.
 */
add_filter( 'post_type_labels_post', function ( $labels ) {
	$labels->name          = __( 'News', 'stech' );
	$labels->singular_name = __( 'News Article', 'stech' );
	$labels->menu_name     = __( 'News', 'stech' );
	$labels->all_items     = __( 'All News', 'stech' );
	$labels->add_new_item  = __( 'Add News Article', 'stech' );
	return $labels;
} );

/** Build a standard CPT labels array. */
function stech_pt_labels( $singular, $plural ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'menu_name'          => $plural,
		'add_new_item'       => "Add {$singular}",
		'edit_item'          => "Edit {$singular}",
		'new_item'           => "New {$singular}",
		'view_item'          => "View {$singular}",
		'search_items'       => "Search {$plural}",
		'not_found'          => "No {$plural} found",
		'all_items'          => "All {$plural}",
	);
}

/** Build a standard taxonomy labels array. */
function stech_tax_labels( $singular, $plural ) {
	return array(
		'name'          => $plural,
		'singular_name' => $singular,
		'menu_name'     => $plural,
		'all_items'     => "All {$plural}",
		'edit_item'     => "Edit {$singular}",
		'add_new_item'  => "Add {$singular}",
		'search_items'  => "Search {$plural}",
	);
}
