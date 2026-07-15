<?php
/**
 * Site header: announcement bar, utility bar, sticky primary nav.
 * Global chrome is driven by nav menus + Theme Settings (ACF options),
 * with the approved defaults as fallbacks so a fresh install looks right.
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

$opt = function ( $name, $default = '' ) {
	$v = function_exists( 'get_field' ) ? get_field( $name, 'option' ) : null;
	return ( null === $v || '' === $v ) ? $default : $v;
};

$phone    = $opt( 'contact_phone', '(435) 586-2899' );
$phone_h  = preg_replace( '/[^0-9+]/', '', $phone );
$email    = $opt( 'contact_email', 'info@stech.edu' );
$location = $opt( 'contact_location', 'Cedar City, UT' );

// Announcement bar (Theme Settings › Announcement Bar).
$ann_on   = (bool) $opt( 'announcement_enabled', false );
$ann_text = $opt( 'announcement_text', '' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'stech' ); ?></a>

<?php if ( $ann_on && $ann_text ) : ?>
	<div class="announcement" role="region" aria-label="<?php esc_attr_e( 'Site announcement', 'stech' ); ?>">
		<div class="container">
			<p class="announcement__msg"><?php echo wp_kses_post( $ann_text ); ?></p>
			<button class="announcement__close" aria-label="<?php esc_attr_e( 'Dismiss announcement', 'stech' ); ?>">&times;</button>
		</div>
	</div>
<?php endif; ?>

<!-- Utility bar -->
<div class="utility-bar">
	<div class="container">
		<div class="utility-bar__inner">
			<div class="utility-bar__left">
				<a href="tel:<?php echo esc_attr( $phone_h ); ?>"><?php echo esc_html( $phone ); ?></a>
				<span class="utility-bar__sep">|</span>
				<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				<span class="utility-bar__sep">|</span>
				<span><?php echo esc_html( $location ); ?></span>
			</div>
			<div class="utility-bar__right">
				<?php
				stech_nav( 'utility', function () {
					$sep = '<span class="utility-bar__sep">|</span>';
					echo '<a href="#">Course Schedules</a>' . $sep;
					echo '<a href="#">Canvas</a>' . $sep;
					echo '<a href="#">Student Portal</a>' . $sep;
					echo '<a href="#">Donate</a>';
				} );
				?>
			</div>
		</div>
	</div>
</div>

<!-- Header -->
<header class="site-header">
	<div class="container">
		<div class="header-inner">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> Home">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					printf(
						'<img src="%s" alt="%s logo" width="180" height="48">',
						esc_url( STECH_IMG . '/shared/logo.svg' ),
						esc_attr( get_bloginfo( 'name' ) )
					);
				}
				?>
			</a>

			<nav class="main-nav" aria-label="<?php esc_attr_e( 'Primary', 'stech' ); ?>">
				<?php
				stech_nav( 'primary', function () {
					echo '<a href="#">Programs</a>';
					echo '<a href="#">High School</a>';
					echo '<a href="#">Admissions &amp; Aid</a>';
					echo '<a href="#">Student Resources</a>';
					echo '<a href="#">Community</a>';
					echo '<a href="#">About</a>';
				} );
				?>
			</nav>

			<button class="menu-toggle" aria-label="<?php esc_attr_e( 'Menu', 'stech' ); ?>" aria-expanded="false" aria-controls="primary-nav">
				<span></span><span></span><span></span>
			</button>
		</div>
	</div>
</header>

<main id="main" class="site-main">
