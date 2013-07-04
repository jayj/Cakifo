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
		/**
		 * Loop through each selected term.
		 */
		foreach ( hybrid_get_setting( 'headlines_category' ) as $selected_term ) :

			/* Separate the taxonomy and term ID. */
			if ( is_array( $selected_term ) ) {
				list( $taxonomy, $term_id ) = $selected_term;

			/* Back-compat when only an ID is used. */
			} elseif ( is_string( $selected_term ) || is_int( $selected_term )  ) {
				$term_id = $selected_term;
				$taxonomy = 'category';
			}

			/* Get term info. */
			$term = get_term_by( 'id', $term_id, $taxonomy );

			/**
			 * Create the loop for each selected term.
			 *
			 * @uses $GLOBALS['cakifo_do_not_duplicate'] Excludes posts from the 'Recent Posts' section
			 */
			$headlines = get_posts(
				array(
					'posts_per_page' => hybrid_get_setting( 'headlines_num_posts' ),
					'post__not_in'   => $GLOBALS['cakifo_do_not_duplicate'],
					'tax_query'      => array(
						'relation' => 'AND',
						array(
							'terms'    => $term_id,
							'taxonomy' => $taxonomy,
							'field'    => 'id',
						),
						array(
							// Exclude posts with the Aside, Link, Quote, and Status format
							'taxonomy' => 'post_format',
							'terms'    => array( 'post-format-aside', 'post-format-link', 'post-format-quote', 'post-format-status' ),
							'field'    => 'slug',
							'operator' => 'NOT IN',
						)
					),
				)
			);

		if ( ! empty( $headlines ) ) :
	?>

		<div class="headline-list">

			<?php do_atomic( 'open_headline_list' ); // cakifo_open_headline_list ?>

			<h2 class="widget-title">
				<a href="<?php echo get_term_link( $term ); ?>" title="<?php echo esc_attr( $term->description ); ?>"><?php echo $term->name; ?></a>
			</h2>

			<ol>
				<?php foreach( $headlines as $post ) : ?>

					<?php
						setup_postdata( $post );
						$GLOBALS['cakifo_do_not_duplicate'][] = get_the_ID();
					?>

					<li class="clearfix">
						<?php do_atomic( 'open_headline_list_item' ); // cakifo_open_headline_list_item ?>

						<?php
							/**
							 * Get the thumbnail
							 */
							if ( current_theme_supports( 'get-the-image' ) ) {
								get_the_image( array(
									'size'          => 'small',
									'image_class'   => 'thumbnail',
									'meta_key'      => false,
									'default_image' => THEME_URI . '/images/default-thumb-mini.png'
								) );
							}
						?>

						<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
						<?php echo apply_atomic_shortcode( 'headline_meta', '<span class="headline-meta">' . __( '[entry-published] by [entry-author]', 'cakifo' ) . '</span>' ); ?>

						<?php do_atomic( 'close_headline_list_item' ); // cakifo_close_headline_list_item ?>
					</li>
				<?php endforeach; ?>
			</ol>

			<?php do_atomic( 'close_headline_list' ); // cakifo_close_headline_list ?>

		</div> <!-- .headline-list -->

	<?php endif; ?>

	<?php endforeach; ?>

	<?php unset( $GLOBALS['cakifo_do_not_duplicate'] ); // Kill the variable ?>

	<?php do_atomic( 'close_headlines' ); // cakifo_close_headlines ?>

</section> <!-- #headlines -->

<?php do_atomic( 'after_headlines' ); // cakifo_after_headlines ?>
