<?php

include(get_stylesheet_directory_uri() . 'assets/classes/class-walker-nav-menu.php');

/**
 * Nav Menu API: Walker_Nav_Menu class
 *
 * @package WordPress
 * @subpackage Nav_Menus
 * @since 4.6.0
 */

/**
 * Core class used to implement an HTML list of nav menu items.
 *
 * @since 3.0.0
 *
 * @see Walker
 */
class Modified_Walker_Nav_Menu extends Walker_Nav_Menu {
	/**
	 * What the class handles.
	 *
	 * @since 3.0.0
	 * @var string
	 *
	 * @see Walker::$tree_type
	 */
	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	/**
	 * Database fields to use.
	 *
	 * @since 3.0.0
	 * @todo Decouple this.
	 * @var array
	 *
	 * @see Walker::$db_fields
	 */
	public $db_fields = array(
		'parent' => 'menu_item_parent',
		'id'     => 'db_id',
	);

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<div' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener noreferrer';
		} else {
			$atts['rel'] = $item->xfn;
		}
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title        Title attribute.
		 *     @type string $target       Target attribute.
		 *     @type string $rel          The rel attribute.
		 *     @type string $href         The href attribute.
		 *     @type string $aria_current The aria-current attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</div>{$n}";
	}

} // Walker_Nav_Menu


add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles()
{

    $parent_style = 'parent-style'; // This is 'twentyseventeen-style' for the Twenty Seventeen theme.

    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    //wp_enqueue_style('php-style', get_stylesheet_directory_uri() . '/css/style.php');

    /* wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    ); */
}

function add_additional_class_on_li($classes, $item, $args)
{
    if ($args->li_class_list) {
        $result[] = $args->li_class_list;
    }

    if (in_array('current-menu-item', $classes)) {
        $result[] = $args->li_active_tag;
    }

    return empty($result) ? $classes : $result;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 1, 3);

function add_menu_link_class($atts, $item, $args)
{
    if (property_exists($args, 'a_class_list')) {
        $atts['class'] = $args->a_class_list;
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_menu_link_class', 1, 3);

function register_scripts_method()
{
    wp_deregister_script('observers');
    wp_register_script('observers', get_stylesheet_directory_uri() . '/js/observers.js');
    wp_enqueue_script('observers', $src = '', $deps = array(), $ver = false, $in_footer = true );
}

add_action('wp_enqueue_scripts', 'register_scripts_method');

function add_google_fonts() {
	wp_enqueue_style( 'add_google_fonts', 'https://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff&display=swap', false );

	wp_enqueue_style( 'add_google_fonts', 'https://fonts.googleapis.com/css?family=Amatic+SC&display=swap', false );
	
	
}
add_action( 'wp_enqueue_scripts', 'add_google_fonts' );

function theme_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'header_section' , array(
        'title'      => __( 'Home page setup', 'twentyseventeen_child' ),
        'priority'   => 30,
    ) );

    $wp_customize->add_setting( 'before_main_title' , array(
        'default'   => 'Leggendario',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'before_main_title', array(
        'label'      => __( 'Header ::before title', 'twentyseventeen_child' ),
        'section'    => 'header_section',
        'settings'   => 'before_main_title',
        'type'       => 'text',
    ) ) );

    $wp_customize->add_setting( 'address_line' , array(
        'default'   => 'smth',
        'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'address_line', array(
        'label'      => __( 'Header address line', 'twentyseventeen_child' ),
        'section'    => 'header_section',
        'settings'   => 'address_line',
        
    ) ) );

    $wp_customize->add_setting( 'phone_line' , array(
        'default'   => '+7 (981) 957-89-75',
        'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'phone_line', array(
        'label'      => __( 'Header phone line', 'twentyseventeen_child' ),
        'section'    => 'header_section',
        'settings'   => 'phone_line',
        
    ) ) );

    $wp_customize->add_setting( 'phone_raw_line' , array(
        'default'   => '+79819578975',
        'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'phone_raw_line', array(
        'label'      => __( 'Header phone::raw line', 'twentyseventeen_child' ),
        'section'    => 'header_section',
        'settings'   => 'phone_raw_line',
        
    ) ) );
}
add_action( 'customize_register', 'theme_customize_register' );

/**
 * Display the custom text field
 * @since 1.0.0
 */
function cfwc_create_units() {
	$args = array(
	'id' => 'units',
	'label' => __( 'Units', 'cfwc' ),
	'class' => 'cfwc-custom-field',
	'desc_tip' => true,
	);
	
	woocommerce_wp_text_input( $args );
	//woocommerce_wp_checkbox( $args );
}
add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_units' );

/**
 * Save the custom field
 * @since 1.0.0
 */
function cfwc_save_units( $post_id ) {
	$product = wc_get_product( $post_id );
	$title = isset( $_POST['units'] ) ? $_POST['units'] : '';
	$product->update_meta_data( 'units', sanitize_text_field( $title ) );
	$product->save();
}
add_action( 'woocommerce_process_product_meta', 'cfwc_save_units' );

/**
 * Display the custom text field
 * @since 1.0.0
 */
function cfwc_create_units_gram() {
	$args = array(
	'id' => 'units_gram',
	'label' => __( 'Граммы (гр.)', 'cfwc' ),
	'class' => 'cfwc-custom-field',
	'desc_tip' => true,
	);
	
	//woocommerce_wp_text_input( $args );
	woocommerce_wp_checkbox( $args );
}
add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_units_gram' );

/**
 * Save the custom field
 * @since 1.0.0
 */
function cfwc_save_units_gram( $post_id ) {
	$product = wc_get_product( $post_id );
	$title = isset( $_POST['units_gram'] ) ? $_POST['units_gram'] : '';
	$product->update_meta_data( 'units_gram', sanitize_text_field( $title ) );
	$product->save();
}
add_action( 'woocommerce_process_product_meta', 'cfwc_save_units_gram' );

/**
 * Display the custom text field
 * @since 1.0.0
 */
function cfwc_create_units_centimeters() {
	$args = array(
	'id' => 'units_centimeters',
	'label' => __( 'Сантиметры (см.)', 'cfwc' ),
	'class' => 'cfwc-custom-field',
	'desc_tip' => true,
	);
	
	//woocommerce_wp_text_input( $args );
	woocommerce_wp_checkbox( $args );
}
add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_units_centimeters' );

/**
 * Save the custom field
 * @since 1.0.0
 */
function cfwc_save_units_centimeters( $post_id ) {
	$product = wc_get_product( $post_id );
	$title = isset( $_POST['units_centimeters'] ) ? $_POST['units_centimeters'] : '';
	$product->update_meta_data( 'units_centimeters', sanitize_text_field( $title ) );
	$product->save();
}
add_action( 'woocommerce_process_product_meta', 'cfwc_save_units_centimeters' );

/**
 * Display the custom text field
 * @since 1.0.0
 */
function cfwc_create_units_milliliters() {
	$args = array(
	'id' => 'units_milliliters',
	'label' => __( 'Миллилитры (мл.)', 'cfwc' ),
	'class' => 'cfwc-custom-field',
	'desc_tip' => true,
	);
	
	//woocommerce_wp_text_input( $args );
	woocommerce_wp_checkbox( $args );
}
add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_units_milliliters' );

/**
 * Save the custom field
 * @since 1.0.0
 */
function cfwc_save_units_milliliters( $post_id ) {
	$product = wc_get_product( $post_id );
	$title = isset( $_POST['units_milliliters'] ) ? $_POST['units_milliliters'] : '';
	$product->update_meta_data( 'units_milliliters', sanitize_text_field( $title ) );
	$product->save();
}
add_action( 'woocommerce_process_product_meta', 'cfwc_save_units_milliliters' );

/**
 * Display the custom text field
 * @since 1.0.0
 */
function cfwc_create_units_pieces() {
	$args = array(
	'id' => 'units_pieces',
	'label' => __( 'Количество (шт.)', 'cfwc' ),
	'class' => 'cfwc-custom-field',
	'desc_tip' => true,
	);
	
	//woocommerce_wp_text_input( $args );
	woocommerce_wp_checkbox( $args );
}
add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_units_pieces' );

/**
 * Save the custom field
 * @since 1.0.0
 */
function cfwc_save_units_pieces( $post_id ) {
	$product = wc_get_product( $post_id );
	$title = isset( $_POST['units_pieces'] ) ? $_POST['units_pieces'] : '';
	$product->update_meta_data( 'units_pieces', sanitize_text_field( $title ) );
	$product->save();
}
add_action( 'woocommerce_process_product_meta', 'cfwc_save_units_pieces' );