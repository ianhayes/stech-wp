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
		// No CPT archive: /programs/ is the composed directory PAGE; single
		// programs live at /programs/<slug>/ via the rewrite slug below.
		'has_archive'   => false,
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
 * Resolve /programs/<slug>/ to a single Program even though a composed
 * /programs/ PAGE exists (the page would otherwise shadow the CPT and 404).
 * The page still owns /programs/ itself; only deeper single-segment paths map
 * to the Program CPT.
 */
add_action( 'init', function () {
	// Maps /programs/<slug>/ to a Program CPT single (the /programs/ PAGE would
	// otherwise shadow the CPT and 404).
	add_rewrite_rule( '^programs/([^/]+)/?$', 'index.php?program=$matches[1]', 'top' );

	// Deep PAGES nested under /programs/ (e.g.
	// /programs/transfer-pathways/suu-dual-enrollment/) are otherwise captured by
	// the Program CPT's auto-generated attachment rewrite rules and 404. Route a
	// two-segments-deep /programs/ path to the request filter, which serves the
	// matching PAGE when one exists.
	add_rewrite_rule( '^programs/([^/]+/[^/]+)/?$', 'index.php?stech_prog_page=$matches[1]', 'top' );
}, 20 );

/** Register the internal query var used to resolve deep /programs/ pages. */
add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'stech_prog_page';
	return $vars;
} );

/**
 * If /programs/<slug>/ matched the CPT rule but no Program with that slug
 * exists AND a real PAGE lives there (e.g. course-schedules), serve the page.
 */
add_filter( 'request', function ( $qv ) {
	if ( ! empty( $qv['program'] ) && ! get_page_by_path( $qv['program'], OBJECT, 'program' ) ) {
		$page = get_page_by_path( 'programs/' . $qv['program'], OBJECT, 'page' );
		if ( $page ) {
			return array( 'pagename' => 'programs/' . $qv['program'] );
		}
	}
	// Deep /programs/<a>/<b>/ path: serve the matching PAGE when one exists,
	// otherwise let it 404 naturally.
	if ( ! empty( $qv['stech_prog_page'] ) ) {
		$path = 'programs/' . $qv['stech_prog_page'];
		if ( get_page_by_path( $path, OBJECT, 'page' ) ) {
			return array( 'pagename' => $path );
		}
	}
	return $qv;
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
