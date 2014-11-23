<?php
/**
 * The template for displaying headlines from taxonomies
 * in the `template-front-page.php` page template
 *
 * Child Themes can replace this template part file via `section-headlines.php`
 *
 * @package Cakifo
 * @subpackage Template
 */

do_atomic( 'before_headlines' ); ?>

<section class="headline-columns clearfix">

	<?php do_atomic( 'open_headlines' ); ?>

	<?php if ( is_active_sidebar( 'front-page-headlines' ) ) : ?>

		<?php dynamic_sidebar( 'front-page-headlines' ); ?>

	<?php elseif ( hybrid_get_setting( 'headlines_category' ) ) : ?>

		<?php
			/* Back-compat for when the headline terms were stored in a setting. */
			foreach ( hybrid_get_setting( 'headlines_category' ) as $term ) :

				the_widget( 'Cakifo_Widget_Headline_Terms',
					array(
						'term'           => $term[0] . ':' . $term[1],
						'limit'          => hybrid_get_setting( 'headlines_num_posts' ),
						'show_thumbnail' => true,
						'show_meta'      => true
					),
					array(
						'before_widget' => '<div class="headline-list">',
						'after_widget'  => '</div> <!-- /.headline-lists -->',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>'
					)
				);

			endforeach;
		?>

	<?php endif; ?>

	<?php do_atomic( 'close_headlines' ); ?>

</section> <!-- .headline-columns -->

<?php do_atomic( 'after_headlines' ); ?>
