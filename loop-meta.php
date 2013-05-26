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

<?php do_atomic( 'before_loop_meta' ); // cakifo_before_loop_meta ?>

<?php if ( ( is_home() && ! is_front_page() ) && hybrid_get_setting( 'featured_show' ) ) : ?>

	<?php global $wp_query; ?>

	<div class="loop-meta-home">

		<h1 class="loop-title">
			<?php echo get_post_field( 'post_title', $wp_query->get_queried_object_id() ); ?>
		</h1>

		<div class="loop-description">
			<?php echo apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', $wp_query->get_queried_object_id() ) ); ?>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta-home -->

<?php elseif ( is_category() ) : ?>

	<div class="loop-meta">

		<h1 class="loop-title">
			<?php printf( __( 'Category Archives: %s', 'cakifo' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?>
		</h1>

		<div class="loop-description">
			<?php echo category_description(); ?>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php elseif ( is_tag() ) : ?>

	<div class="loop-meta">

		<h1 class="loop-title">
			<?php printf( __( 'Tag Archives: %s', 'cakifo' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?>
		</h1>

		<div class="loop-description">
			<?php echo tag_description(); ?>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php elseif ( is_tax( 'post_format' ) ) : ?>

	<div class="loop-meta">

		<h1 class="loop-title">
			<?php printf( __( 'Post Format: %s', 'cakifo' ), '<span>' . get_post_format_string( get_post_format( get_the_ID() ) ) . '</span>' ); ?>
		</h1>

		<div class="loop-description">
			<?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php elseif ( is_tax() ) : ?>

	<div class="loop-meta">

		<h1 class="loop-title">
			<?php printf( __( 'Archives: %s', 'cakifo' ), '<span>' . single_term_title( '', false ) . '</span>' ); ?>
		</h1>

		<div class="loop-description">
			<?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php elseif ( is_author() ) : ?>

	<?php
		$user_id = get_query_var( 'author' );
		$name = get_the_author_meta( 'display_name', $user_id );
	?>

	<div id="hcard-<?php the_author_meta( 'user_nicename', $user_id ); ?>" class="loop-meta vcard">

		<h1 class="loop-title">
			<?php printf( __( 'Author: %s', 'cakifo' ), '<span class="fn n">' . $name . '</span>' ); ?>
		</h1>

		<div class="loop-description">
			<?php echo get_avatar( get_the_author_meta( 'user_email', $user_id ), 96, '', $name ); ?>

			<?php echo wpautop( get_the_author_meta( 'description', $user_id ) ); ?>

			<?php if ( $twitter = get_the_author_meta( 'twitter', $user_id ) ) { ?>
				<p class="twitter-link">
					<a href="<?php echo esc_url( "http://twitter.com/{$twitter}" ); ?>" title="<?php printf( esc_attr__( 'Follow %s on Twitter', 'cakifo' ), $name ); ?>">
						<?php printf( __( 'Follow %s on Twitter', 'cakifo' ), $name ); ?>
					</a>
				</p>
			<?php } // Twitter ?>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php elseif ( is_search() ) : ?>

	<div class="loop-meta">

		<?php $results = absint( $wp_query->found_posts ); ?>

		<h1 class="loop-title">
			<?php printf( _n( '%1$d Search Result for: %2$s', '%1$d Search Results for: %2$s', $results, 'cakifo' ), $results, '<span>' . esc_attr( get_search_query() ) . '</span>' ); ?>
		</h1>

		<div class="loop-description">
			<p>
				<?php printf( __( 'You are browsing the search results for &quot;%1$s&quot;', 'cakifo' ), esc_attr( get_search_query() ) ); ?>
			</p>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php elseif ( is_date() ) : ?>

	<div class="loop-meta">

		<h1 class="loop-title"><?php _e( 'Archives by date', 'cakifo' ); ?></h1>

		<div class="loop-description">
			<p>
				<?php _e( 'You are browsing the site archives by date.', 'cakifo' ); ?>
			</p>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php elseif ( is_post_type_archive() ) : ?>

	<?php $post_type = get_post_type_object( get_query_var( 'post_type' ) ); ?>

	<div class="loop-meta">

		<h1 class="loop-title"><?php post_type_archive_title(); ?></h1>

		<div class="loop-description">
			<?php
				if ( ! empty( $post_type->description ) )
					echo wpautop( $post_type->description );
			?>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php elseif ( is_archive() ) : ?>

	<div class="loop-meta">

		<h1 class="loop-title"><?php _e( 'Archives', 'cakifo' ); ?></h1>

		<div class="loop-description">
			<p>
				<?php _e( 'You are browsing the site archives.', 'cakifo' ); ?>
			</p>
		</div> <!-- .loop-description -->

	</div> <!-- .loop-meta -->

<?php endif; ?>

<?php do_atomic( 'after_loop_meta' ); // cakifo_after_loop_meta ?>
