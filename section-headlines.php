<?php
/**
 * The template for displaying headlines from taxonomies
 * in the `template-front-page.php` page template
 *
 * Child Themes can replace this template part file via `section-headlines.php`
 *
 * @package    Cakifo
 * @subpackage Template
 */

do_atomic( 'before_headlines' ); // cakifo_before_headlines ?>

<section class="headline-columns" class="clearfix">

	<?php do_atomic( 'open_headlines' ); // cakifo_open_headlines ?>

	<?php
		/**
		 * Loop through each selected term.
		 */
		foreach ( hybrid_get_setting( 'headlines_category' ) as $selected_term ) :

			// Separate the taxonomy and term ID.
			if ( is_array( $selected_term ) ) {
				list( $taxonomy, $term_id ) = $selected_term;

			// Back-compat when only an ID is used.
			} elseif ( is_string( $selected_term ) || is_int( $selected_term )  ) {
				$term_id = $selected_term;
				$taxonomy = 'category';
			}

			// Get term info.
			$term = get_term_by( 'id', $term_id, $taxonomy );

			/**
			 * Create the loop for each selected term.
			 *
			 * @uses $GLOBALS['cakifo_do_not_duplicate'] Excludes posts being duplicated
			 */
			$headlines = new WP_Query(
				array(
					'posts_per_page' => hybrid_get_setting( 'headlines_num_posts' ),
					'post__not_in'   => $GLOBALS['cakifo_do_not_duplicate'],
					'tax_query'      => array(
						array(
							'terms'    => $term_id,
							'taxonomy' => $taxonomy,
							'field'    => 'id',
						)
					),
					'no_found_rows'          => true,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false
				)
			);

		if ( $headlines->have_posts() ) :
	?>

		<div class="headline-list">

			<?php do_atomic( 'open_headline_list' ); // cakifo_open_headline_list ?>

			<?php
				// Get the plural version of a post format name.
				if ( 'post_format' == $term->taxonomy ) {
					$name = hybrid_get_plural_post_format_string( $term->slug );
				} else {
					$name = $term->name;
				}
			?>

			<h2 class="widget-title">
				<a href="<?php echo get_term_link( $term ); ?>"><?php echo $name; ?></a>
			</h2>

			<ol>
				<?php while ( $headlines->have_posts() ) : $headlines->the_post(); ?>

					<?php
						// Make sure the post is not duplicated.
						$GLOBALS['cakifo_do_not_duplicate'][] = get_the_ID();
					?>

					<li class="headline-item clearfix">
						<?php do_atomic( 'open_headline_list_item' ); // cakifo_open_headline_list_item ?>

						<?php
							// Get the thumbnail.
							if ( current_theme_supports( 'get-the-image' ) ) {
								get_the_image( array(
									'size'          => 'small',
									'image_class'   => 'thumbnail',
									'meta_key'      => false,
									'default_image' => THEME_URI . '/images/default-thumb-mini.png'
								) );
							}
						?>

						<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

						<?php echo apply_atomic_shortcode( 'headline_meta', '<span class="headline-meta">' . __( '[entry-published] by [entry-author]', 'cakifo' ) . '</span>' ); ?>

						<?php do_atomic( 'close_headline_list_item' ); // cakifo_close_headline_list_item ?>
					</li>
				<?php endwhile; ?>
			</ol>

			<?php do_atomic( 'close_headline_list' ); // cakifo_close_headline_list ?>

		</div> <!-- .headline-list -->

	<?php endif; ?>

	<?php endforeach; ?>

	<?php unset( $GLOBALS['cakifo_do_not_duplicate'] ); // Kill the variable. ?>

	<?php do_atomic( 'close_headlines' ); // cakifo_close_headlines ?>

</section> <!-- #headlines -->

<?php do_atomic( 'after_headlines' ); // cakifo_after_headlines ?>
