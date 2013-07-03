<?php
/**
 * The template for displaying headlines from categories
 * in the `template-front-page.php` page template
 *
 * Child Themes can replace this template part file via `section-headlines.php`
 *
 * @package Cakifo
 * @subpackage Template
 */

do_atomic( 'before_headlines' ); // cakifo_before_headlines ?>

<section id="headlines" class="clearfix">

	<?php do_atomic( 'open_headlines' ); // cakifo_open_headlines ?>

<?php

$start = microtime(true);

	$taxonomies = get_object_taxonomies( 'post', 'names' );

	/**
	 * Loop through each selected term.
	 */
	foreach ( hybrid_get_setting( 'headlines_category' ) as $selected_term ) :

		/* Get term info. */
		$term = get_terms( $taxonomies, array( 'include' => $selected_term ) );
		$term = $term[0];

		//var_dump($term);

		/**
		 * Create the loop for each selected term.
		 *
		 * @uses $GLOBALS['cakifo_do_not_duplicate'] Excludes posts from the 'Recent Posts' section
		 */
		$headlines = new WP_Query(
			array(
				'posts_per_page' => hybrid_get_setting( 'headlines_num_posts' ),
				//'post__not_in'   => $GLOBALS['cakifo_do_not_duplicate'],
				'tax_query'      => array(
					'relation' => 'AND',
					array(
						'taxonomy' => $term->taxonomy,
						'field' => 'id',
						'terms' => $selected_term,
					),
					array(
						// Exclude posts with the Aside, Link, Quote, and Status format
						'taxonomy'     => 'post_format',
						'terms'        => array( 'post-format-aside', 'post-format-link', 'post-format-quote', 'post-format-status' ),
						'field'        => 'slug',
						'operator'     => 'NOT IN',
					),
				),
			)
		);

	if ( $headlines->have_posts() ) : ?>

		<div class="headline-list">

			<?php do_atomic( 'open_headline_list' ); // cakifo_open_headline_list ?>

			<h2 class="widget-title">
				<a href="<?php echo get_term_link( $term ); ?>" title="<?php echo esc_attr( $term->description ); ?>"><?php echo $term->name; ?></a>
			</h2>

			<ol>
				<?php while ( $headlines->have_posts() ) : $headlines->the_post(); ?>

					<?php
						//$GLOBALS['cakifo_do_not_duplicate'][] = get_the_ID();
					?>

					<li class="clearfix">
						<?php do_atomic( 'open_headline_list_item' ); // cakifo_open_headline_list_item ?>

						<?php
							/**
							 * Get the thumbnail
							 */
							if ( current_theme_supports( 'get-the-image' ) )
								get_the_image(
									array(
										'size'          => 'small',
										'image_class'   => 'thumbnail',
										'default_image' => THEME_URI . '/images/default-thumb-mini.png'
									)
								);
						?>

						<?php
							echo apply_atomic_shortcode( 'headline_entry_title', '[entry-title tag="h3"]' );
							echo apply_atomic_shortcode( 'headline_meta', '<span class="headline-meta">' . __( '[entry-published] by [entry-author]', 'cakifo' ) . '</span>' );
						?>

						<?php do_atomic( 'close_headline_list_item' ); // cakifo_close_headline_list_item ?>
					</li>
				<?php endwhile; ?>
			</ol>

			<?php do_atomic( 'close_headline_list' ); // cakifo_close_headline_list ?>

		</div> <!-- .headline-list -->

	<?php endif;  wp_reset_postdata(); ?>

<?php endforeach;

$end = microtime(true);
var_dump($end - $start);

?>

	<?php unset( $GLOBALS['cakifo_do_not_duplicate'] ); // Kill the variable ?>

	<?php do_atomic( 'close_headlines' ); // cakifo_close_headlines ?>

</section> <!-- #headlines -->

<?php do_atomic( 'after_headlines' ); // cakifo_after_headlines ?>
