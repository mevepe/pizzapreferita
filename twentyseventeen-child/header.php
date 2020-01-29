<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<link
      href="https://fonts.googleapis.com/css?family=Poppins:300,900&display=swap"
	  rel="stylesheet"
/>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'twentyseventeen' ); ?></a>

	<header id="masthead" class="site-header" role="banner">

		<?php //get_template_part( 'template-parts/header/header', 'image' ); ?>

		<?php if ( has_nav_menu( 'top' ) ) : ?>
			<div class="navigation-top">
				<?php get_template_part( 'template-parts/navigation/navigation', 'top' ); ?>
			</div><!-- .navigation-top -->
		<?php endif; ?>

	</header><!-- #masthead -->

	<?php
		$home_intro_image = 'rgba(0, 0, 0, 0.36) url(' . get_header_image() . ')';
		$home_intro_backup_image = '#123 url(//unsplash.it/900)';
		$custom_logo__url = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );  
	?>
	<div class="home-intro waves-bottom" style='background: <?php echo empty($home_intro_image) ? $home_intro_backup_image : $home_intro_image?>'>
		<div class="home-intro-left-section">
			<img src="<?php echo $custom_logo__url[0]; ?>">
		</div>
		<div class="home-intro-right-section">
			<h2 class="home-intro-before-title-text"><?php echo get_theme_mod('before_main_title', '123'); ?></h2>
			<div class="home-intro-main-title"><h1 class="home-intro-main-title-text"><?php echo get_bloginfo('description'); ?></h1></div>
			<div class="home-intro-after-main-title">
				<a class="home-intro-address-line" href=""><?php echo get_theme_mod('address_line'); ?></a>
				<a class="home-intro-spacer">|</a>
				<a class="home-intro-phone-line" href="tel:<?php echo get_theme_mod('phone_raw_line'); ?>"><?php echo get_theme_mod('phone_line'); ?></a>
			</div>
		</div>
	</div><!-- .home-intro' -->

	<div class="site-content-contain">
		<div id="content" class="site-content">
