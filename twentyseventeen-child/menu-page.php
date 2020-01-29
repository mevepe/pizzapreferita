<?php
/**
 * The template for displaying menu-page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * Template Name: Menu Page
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<?php
$taxonomy     = 'product_cat';
$orderby      = 'name';  
$show_count   = 0;      // 1 for yes, 0 for no
$pad_counts   = 0;      // 1 for yes, 0 for no
$hierarchical = 0;      // 1 for yes, 0 for no  
$title        = '';  
$empty        = 1;

$args = array(
	   'taxonomy'     => $taxonomy,
	   'show_count'   => $show_count,
	   'pad_counts'   => $pad_counts,
	   'hierarchical' => $hierarchical,
	   'title_li'     => $title,
	   'hide_empty'   => $empty
);
$all_categories = get_categories( $args );

$home_intro_image = 'rgba(0, 0, 0, 0.36) url(' . wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' )[0] . ')';
$home_intro_backup_image = '#123 url(//unsplash.it/900)';
$custom_logo__url = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );  
?>
<div class="waves-top waves-bottom" style='background: <?php echo empty($home_intro_image) ? $home_intro_backup_image : $home_intro_image?>'>
<div class="wrap menu-wrapper">
<?php
foreach ($all_categories as $cat) {
	if($cat->category_parent == 0) {
		$category_id = $cat->term_id;
		echo '<div class="menu-category">';       
		echo '<p class="menu-category-header" href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name . ':' . '</p>';

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 10,
			'product_cat'    => $cat->slug,
		);
	
		$loop = new WP_Query( $args );
	
		echo '<div class="menu-elements-wrapper">';
		while ( $loop->have_posts() ) : $loop->the_post();
			global $product;

			$content = $product->get_description();

			$units = $product->get_meta( 'units' );

			if ($product->get_meta( 'units_gram' )) {
				$units = $units . ' ' . 'гр.';
			}

			if ($product->get_meta( 'units_centimeters' )) {
				$units = $units . ' ' . 'см.';
			}

			if ($product->get_meta( 'units_milliliters' )) {
				$units = $units . ' ' . 'мл.';
			}

			if ($product->get_meta( 'units_pieces' )) {
				$count = $product->get_attribute( 'count' );
				$units = '(' . $units . ')';
				$units = $count . ' ' . 'шт.' . $units;
			}
			
			if ($content) {
				$content = '(' . $content . ')';
			}

			if ( $product->get_sale_price() == 0 ) {
				$price = $product->get_price();
			} else {
				$price = $product->get_sale_price() . '/' . $product->get_price();
			}

			if ($price) {
				$price = $price . '&#8381';
			}

			echo '<div class="menu-element-wrapper">';
			echo '<div class=menu-element-title-units-wrap>';
			echo '<p class="menu-element-title" href="' . get_permalink() . '">' . get_the_title() . '</p>';
			echo '<p class="menu-element-units">' . $units . '</p>';
			echo '</div>';
			echo '<p class="menu-element-details">' . $content . '</p>';
			echo '<p class="menu-element-price">' . $price . '</p>';
			echo '</div>' . '<!-- .menu-element-wrapper -->';
		endwhile;
		echo '</div>' . '<!-- .menu-elements-wrapper -->';
	
		wp_reset_query();

		echo '</div>' . '<!-- .menu-category -->';
	}       
}
?>
</div><!-- .wrap -->
</div>

<?php
get_footer();
