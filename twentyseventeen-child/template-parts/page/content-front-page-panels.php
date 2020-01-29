<?php
/**
 * Template part for displaying pages on front page
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

global $twentyseventeencounter;

?>

<article id="panel<?php echo $twentyseventeencounter; ?>" <?php post_class( 'twentyseventeen-panel' ); ?> >

	<?php
	if ( has_post_thumbnail() ) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'twentyseventeen-featured-image' );

		// Calculate aspect ratio: h / w * 100%.
		$ratio = $thumbnail[2] / $thumbnail[1] * 100;
		?>

		<div class="panel-image waves-top waves-bottom" style="background-image: url(<?php echo esc_url( $thumbnail[0] ); ?>);">
			<div class="panel-image-prop" style="padding-top: <?php echo esc_attr( $ratio ); ?>%"></div>
		</div><!-- .panel-image -->

	<?php endif; ?>

	<?php
	$fields = get_fields();

	$images = preg_grep('/([f])/', $fields);

	$title = get_field('title');

	$translation = get_field('image-align');

	if ($translation)
	{
		$image_align_class = 'from-right';
	} else {
		$image_align_class = 'from-left';
	}

	if ($title) {
		$title = '<h2 class="entry-content-entry-header-title">' . $title . '</h2>';
	} else {
		$title = the_title( '<h2 class="entry-content-entry-header-title">', '</h2>' );
	}
	?>

	<div class="panel-content">
		<div class="wrap">

			<div class="entry-content more-stuff-grid fade-in <?php echo $image_align_class; ?>">

				<div class="entry-content-before-header">
					<h1 class="entry-content-before-entry-title"><?php echo get_field('title::before_text'); ?></h1>
				</div>

				<div class="entry-content-entry-header">
					<?php echo $title; ?>
				</div><!-- .entry-header -->

				<div class="entry-content-star-separator">
				✻
				</div>

				<div class="entry-content-post-content">
					<?php
					echo $post->post_content;
					?>
				</div>

				<?php if ( $images ): ?>

				<div class="entry-content-images">
					<?php foreach( $images as $class => $url): ?>
						<img class="entry-content-image-inside-panel" src="<?php echo $url ?>">
					<?php endforeach; ?>
				</div>

				<?php endif; ?>


				<?php
				// Show recent blog posts if is blog posts page (Note that get_option returns a string, so we're casting the result as an int).
				if ( get_the_ID() === (int) get_option( 'page_for_posts' ) ) :
				?>

				<?php
				// Show three most recent posts.
				$recent_posts = new WP_Query(
					array(
						'posts_per_page'      => 3,
						'post_status'         => 'publish',
						'ignore_sticky_posts' => true,
						'no_found_rows'       => true,
					)
				);
				?>

				<?php if ( $recent_posts->have_posts() ) : ?>

					<div class="recent-posts">

						<?php
						while ( $recent_posts->have_posts() ) :
							$recent_posts->the_post();
							get_template_part( 'template-parts/post/content', 'excerpt' );
						endwhile;
						wp_reset_postdata();
						?>
					</div><!-- .recent-posts -->
				<?php endif; ?>
			<?php endif; ?>

		</div><!-- .wrap -->
	</div><!-- .panel-content -->

</article><!-- #post-<?php the_ID(); ?> -->
