<?php
/**
 * Displays top navigation
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

?>
<nav id="site-navigation" class="main-navigation nav__list" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'twentyseventeen' ); ?>">
	<?php

	$args = array(
		'container'     => false,
		'theme_location'=> 'top',
		'menu'			=> 'top-menu',
		'menu_class'      => 'nav__list',
		'depth'         => 1,
		'fallback_cb'   => false,
		'li_class_list' => 'nav__list-item',
		'li_active_tag' => 'currently-active',
		'a_class_list'	=> 'nav__link',
		'items_wrap'	=> '%3$s',
		'walker'        => new Modified_Walker_Nav_Menu,
	);

	wp_nav_menu($args);
	?>

	<?php if (false) ://if ( ( twentyseventeen_is_frontpage() || ( is_home() && is_front_page() ) ) && has_custom_header() ) : ?>
		<a href="#content" class="menu-scroll-down"><?php echo twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ); ?><span class="screen-reader-text"><?php _e( 'Scroll down to content', 'twentyseventeen' ); ?></span></a>
	<?php endif; ?>
</nav><!-- #site-navigation -->