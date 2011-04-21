<?php
/**
 * Loop Meta Template
 *
 * Displays information at the top of the page about archive and search results when viewing those pages.  
 * This is not shown on the front page or singular views.
 *
 * @package Cakifo
 * @subpackage Template
 */
?>

	<?php if ( ( is_home() && !is_front_page() ) && hybrid_get_setting( 'featured_show' ) ) : ?>

		<?php global $wp_query; ?>

		<div class="loop-meta loop-meta-home">

			<h1 class="loop-title"><span><?php echo get_post_field( 'post_title', $wp_query->get_queried_object_id() ); ?></span></h1>

			<div class="loop-description">
				<?php echo apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', $wp_query->get_queried_object_id() ) ); ?>
			</div> <!-- .loop-description -->

		</div> <!-- .loop-meta -->

	<?php elseif ( is_category() ) : ?>

		<div class="loop-meta">

			<h1 class="loop-title"><?php printf( __( 'Category Archives: %s', hybrid_get_textdomain() ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>

			<div class="loop-description">
				<?php echo category_description(); ?>
			</div> <!-- .loop-description -->

		</div> <!-- .loop-meta -->

	<?php elseif ( is_tag() ) : ?>

		<div class="loop-meta">

            <h1 class="loop-title"><?php printf( __( 'Tag Archives: %s', hybrid_get_textdomain() ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h1>

			<div class="loop-description">
				<?php echo tag_description(); ?>
			</div> <!-- .loop-description -->

		</div> <!-- .loop-meta -->

	<?php elseif ( is_tax() ) : ?>

		<div class="loop-meta">

			<h1 class="loop-title">
				<?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); ?>
                <?php printf( __( 'Archives: %s', hybrid_get_textdomain() ), '<span>' . $term->name . '</span>' ); ?>
            </h1>

			<div class="loop-description">
				<?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?>
			</div> <!-- .loop-description -->

		</div> <!-- .loop-meta -->

	<?php elseif ( is_author() ) : ?>

		<?php $user_id = get_query_var( 'author' ); ?>

		<div id="hcard-<?php the_author_meta( 'user_nicename', $user_id ); ?>" class="loop-meta vcard">

            <h1 class="loop-title"><?php printf( __( 'Author: %s', hybrid_get_textdomain() ), '<span class="fn n">' . get_the_author_meta( 'display_name', $user_id ) . '</span>' ); ?></h1>

			<div class="loop-description">
				<?php $desc = get_the_author_meta( 'description', $user_id ); ?>

				<?php if ( !empty( $desc ) ) { ?>
					<?php echo get_avatar( get_the_author_meta( 'user_email', $user_id ), '100', '', get_the_author_meta( 'display_name', $user_id ) ); ?>

					<p class="user-bio">
						<?php echo $desc; ?>
					</p> <!-- .user-bio -->
				<?php } ?>
			</div> <!-- .loop-description -->

		</div> <!-- .loop-meta -->

	<?php elseif ( is_search() ) : ?>

		<div class="loop-meta">

            <?php $results = absint( $wp_query->found_posts ); ?>

            <h1 class="loop-title">
				<?php
					printf( _n( "%d Search Result for: %s", "%d Search Results for: %s",
						$results, '<span>' . esc_attr( get_search_query() ) . '</span>' ),
						$results, '<span>' . esc_attr( get_search_query() ) . '</span>'
					);
				?>
			</h1>

			<div class="loop-description">
				<p>
				<?php printf( __( 'You are browsing the search results for &quot;%1$s&quot;', hybrid_get_textdomain() ), esc_attr( get_search_query() ) ); ?>
				</p>
			</div> <!-- .loop-description -->

		</div> <!-- .loop-meta -->

	<?php elseif ( is_date() ) : ?>

		<div class="loop-meta">
			<h1 class="loop-title"><?php _e( 'Archives by date', hybrid_get_textdomain() ); ?></h1>

			<div class="loop-description">
				<p>
				<?php _e( 'You are browsing the site archives by date.', hybrid_get_textdomain() ); ?>
				</p>
			</div> <!-- .loop-description -->

		</div> <!-- .loop-meta -->

	<?php elseif ( is_post_type_archive() ) : ?>

		<?php $post_type = get_post_type_object( get_query_var( 'post_type' ) ); ?>

		<div class="loop-meta">

			<h1 class="loop-title"><?php post_type_archive_title(); ?></h1>

			<div class="loop-description">
				<?php if ( !empty( $post_type->description ) ) echo "<p>{$post_type->description}</p>"; ?>
			</div> <!-- .loop-description -->

		</div> <!-- .loop-meta -->

	<?php elseif ( is_archive() ) : ?>

		<div class="loop-meta">

			<h1 class="loop-title"><?php _e( 'Archives', hybrid_get_textdomain() ); ?></h1>

			<div class="loop-description">
				<p>
					<?php _e( 'You are browsing the site archives.', hybrid_get_textdomain() ); ?>
				</p>
			</div> <!-- .loop-description -->

		</div> <!-- .loop-meta -->

	<?php endif; ?>