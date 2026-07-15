<?php
/**
 * 404 — keep users oriented with the brand chrome + a way back.
 *
 * @package STECH
 */
defined( 'ABSPATH' ) || exit;
get_header(); ?>
<section class="page-header page-header--plain">
	<div class="container">
		<p class="overline"><?php esc_html_e( 'Error 404', 'stech' ); ?></p>
		<h1 class="h1"><?php esc_html_e( 'We couldn’t find that page.', 'stech' ); ?></h1>
		<p class="page-header__lede"><?php esc_html_e( 'The page may have moved. Try searching or head back home.', 'stech' ); ?></p>
		<p>
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'stech' ); ?> <span class="arrow">&rarr;</span></a>
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/programs/' ) ); ?>"><?php esc_html_e( 'Browse Programs', 'stech' ); ?></a>
		</p>
		<div style="max-width:420px;margin-top:var(--space-8);"><?php get_search_form(); ?></div>
	</div>
</section>
<?php get_footer();
