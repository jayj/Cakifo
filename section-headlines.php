<?php
/**
 * The template for displaying headlines from categories
 * in the template-front-page.php page template
 *
 * Child Themes can replace this template part file via {section-headlines.php}
 *
 * @package Cakifo
 * @subpackage Template
 */

do_atomic( 'before_headlines' ); // cakifo_before_headlines ?>

<section id="headlines" class="clearfix">

	<?php do_atomic( 'open_headlines' ); // cakifo_open_headlines ?>

<?php
	/**
	 * Loop through each selected category
	 */
	foreach ( hybrid_get_setting( 'headlines_category' ) as $category ) :

		/**
		 * Create the loop for each selected category
		 *
		 * @uses $GLOBALS['cakifo_do_not_duplicate'] Excludes posts from the 'Recent Posts' section
		 */
		$headlines = get_posts( array(
			'numberposts'  => hybrid_get_setting( 'headlines_num_posts' ),
			'post__not_in' => $GLOBALS['cakifo_do_not_duplicate'],
			'category'     => $category,
			'post_status'  => 'publish',
			'tax_query'    => array(
				array(
					// Exclude posts with the Aside, Link, Quote, and Status format
					'taxonomy'     => 'post_format',
					'terms'        => array( 'post-format-aside', 'post-format-link', 'post-format-quote', 'post-format-status' ),
					'field'        => 'slug',
					'operator'     => 'NOT IN',
				),
			),
		) );

	if ( ! empty( $headlines ) ) : ?>

		<div class="headline-list">

			<?php do_atomic( 'open_headline_list' ); // cakifo_open_headline_list ?>

			<?php $cat = get_category( $category ); ?>

			<h2 class="widget-title">
				<a href="<?php echo get_category_link( $category ); ?>" title="<?php echo esc_attr( $cat->name ); ?>"><?php echo $cat->name; ?></a>
			</h2>

			<ol>
				<?php foreach ( $headlines as $post ) : $GLOBALS['cakifo_do_not_duplicate'][] = get_the_ID(); ?>
					<li>
						<?php do_atomic( 'open_headline_list_item' ); // cakifo_open_headline_list_item ?>

						<?php
							/* Get the thumbnail */
							if ( current_theme_supports( 'get-the-image' ) )
								get_the_image( array(
									'meta_key'      => 'Thumbnail',
									'size'          => 'small',
									'image_class'   => 'thumbnail',
									'default_image' => THEME_URI . '/images/default-thumb-mini.gif'
								) );
						?>

						<?php
							echo apply_atomic_shortcode( 'headline_entry_title', '[entry-title tag="h3"]' );
							echo apply_atomic_shortcode( 'headline_meta', '<span class="headline-meta">' . __( '[entry-published] by [entry-author]', 'cakifo' ) . '</span>' );
						?>

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
