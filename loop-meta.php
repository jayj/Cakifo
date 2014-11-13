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

do_atomic( 'before_loop_meta' ); ?>

<?php
	/*
	 * Add Breadcrumb Trail
	 */
	if ( current_theme_supports( 'breadcrumb-trail' ) && ( ! is_home() && ! is_front_page() ) ) :

		breadcrumb_trail( array(
			'labels' => array(
				'browse' => __( 'You are here:', 'cakifo' )
			)
		));

	endif;
?>

<?php if ( ( is_home() && ! is_front_page() ) ) : ?>

	<div class="loop-meta-home">

		<h1 class="loop-title"><?php echo get_post_field( 'post_title', get_queried_object_id() ); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<div class="loop-description">
				<?php echo apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', get_queried_object_id() ) ); ?>
			</div> <!-- .loop-description -->
		<?php endif; ?>

	</div> <!-- .loop-meta-home -->

<?php elseif ( is_author() ) : ?>

	<?php
		$user_id = get_query_var( 'author' );
		$name = get_the_author_meta( 'display_name', $user_id );
	?>

	<div id="hcard-<?php the_author_meta( 'user_nicename', $user_id ); ?>" class="loop-meta vcard">

		<?php the_archive_title( '<h1 class="loop-title">', '</h1>' ); ?>

		<div class="loop-description">
			<?php echo get_avatar( get_the_author_meta( 'user_email', $user_id ), 80 ); ?>

			<?php echo wpautop( get_the_author_meta( 'description', $user_id ) ); ?>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php elseif ( is_search() ) : ?>

	<div class="loop-meta">

		<?php $results = absint( $wp_query->found_posts ); ?>

		<h1 class="loop-title"><?php printf( _n( '%1$d Search Result for: %2$s', '%1$d Search Results for: %2$s', $results, 'cakifo' ), $results, '<span>' . esc_attr( get_search_query() ) . '</span>' ); ?></h1>

		<div class="loop-description">
			<p><?php printf( __( 'You are browsing the search results for &quot;%1$s&quot;', 'cakifo' ), esc_attr( get_search_query() ) ); ?></p>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php elseif ( is_post_type_archive() ) : ?>

	<?php $post_type = get_post_type_object( get_query_var( 'post_type' ) ); ?>

	<div class="loop-meta">

		<h1 class="loop-title"><?php post_type_archive_title(); ?></h1>

		<div class="loop-description">
			<?php
				if ( ! empty( $post_type->description ) ) {
					echo wpautop( $post_type->description );
				}
			?>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php elseif ( is_archive() ) : ?>

	<div class="loop-meta">

		<?php
			the_archive_title( '<h1 class="loop-title">', '</h1>' );
			the_archive_description( '<div class="loop-description">', '</div>' );
		?>

	</div> <!-- .loop-meta -->

<?php endif; ?>

<?php do_atomic( 'after_loop_meta' ); ?>
