<?php
/**
 * Accessible search form matching the brand input styling.
 *
 * @package STECH
 */
defined( 'ABSPATH' ) || exit;
$id = 'search-' . wp_unique_id();
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'stech' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $id ); ?>" class="search-form__field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'stech' ); ?>" required>
	<button type="submit" class="btn btn--primary btn--sm"><?php esc_html_e( 'Search', 'stech' ); ?></button>
</form>
