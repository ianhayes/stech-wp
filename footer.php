<?php
/**
 * Site footer: 4-column footer + bottom legal bar.
 * Brand/address from Theme Settings › Contact; columns from footer menus
 * (headings from Global settings); legal links from the "legal" menu.
 * Approved defaults render as fallbacks.
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

$opt = function ( $name, $default = '' ) {
	$v = function_exists( 'get_field' ) ? get_field( $name, 'option' ) : null;
	return ( null === $v || '' === $v ) ? $default : $v;
};

$phone   = $opt( 'contact_phone', '(435) 586-2899' );
$phone_h = preg_replace( '/[^0-9+]/', '', $phone );
$email   = $opt( 'contact_email', 'info@stech.edu' );
$address = $opt( 'contact_address', "757 West 800 South\nCedar City, UT 84720" );

$col_heads = array(
	$opt( 'footer_col_1_heading', 'Programs' ),
	$opt( 'footer_col_2_heading', 'Quick Links' ),
	$opt( 'footer_col_3_heading', 'Connect' ),
);
?>
</main>

<footer class="site-footer">
	<div class="container">
		<div class="footer-grid">
			<div class="footer-col footer-col--brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-brand" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> Home">
					<img src="<?php echo esc_url( STECH_IMG . '/shared/logo-vertical.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="160" height="120">
				</a>
				<address class="footer-contact">
					<?php echo nl2br( esc_html( $address ) ); ?><br>
					<a href="tel:<?php echo esc_attr( $phone_h ); ?>"><?php echo esc_html( $phone ); ?></a><br>
					<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				</address>
			</div>

			<?php
			$footer_locations = array( 'footer_1', 'footer_2', 'footer_3' );
			foreach ( $footer_locations as $i => $loc ) :
				?>
				<div class="footer-col">
					<h4><?php echo esc_html( $col_heads[ $i ] ); ?></h4>
					<?php
					stech_nav( $loc, function () use ( $i ) {
						if ( 0 === $i ) {
							echo '<a href="/programs/">View All Programs &rarr;</a>';
						} elseif ( 1 === $i ) {
							echo '<a href="#">Admissions</a><a href="#">Financial Aid</a><a href="#">Student Portal</a>';
						} else {
							echo '<a href="#">Contact Us</a><a href="#">Request Info</a>';
						}
					} );
					?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="footer-bottom">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'stech' ); ?></p>
			<div class="footer-bottom__links">
				<?php
				stech_nav( 'legal', function () {
					echo '<a href="#">Privacy</a><a href="#">Accessibility</a><a href="#">Non-Discrimination</a><a href="#">Title IX</a><a href="#">Consumer Information</a>';
				} );
				?>
			</div>
		</div>
	</div>
</footer>

<?php
// Custom scripts (Theme Settings › Scripts & Analytics).
$custom_js = function_exists( 'get_field' ) ? get_field( 'custom_js', 'option' ) : '';
if ( $custom_js ) {
	echo "<script>\n" . $custom_js . "\n</script>\n"; // phpcs:ignore -- admin-provided.
}
wp_footer();
?>
</body>
</html>
