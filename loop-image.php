<?php
/**
 * Image Content Template
 *
 * Template used to show posts with the 'image' post format.
 *
 * This can be overridden in child themes with loop-image.php
 *
 * @package Cakifo
 * @subpackage Template
 * @since Cakifo 1.5
 */
?>
<?php do_atomic( 'before_entry' ); //cakifo_before_entry ?>

	<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

		<?php do_atomic( 'open_entry' ); //cakifo_open_entry ?>

		<?php if ( is_singular() && is_main_query() ) : ?>

			<header class="entry-header">
				<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title permalink=""]' ); ?>
				<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
			</header> <!-- .entry-header -->

			<div class="entry-content">
				<?php
					/* Get content if it exists */
					if ( cakifo_post_has_content() ) {
						the_content();
					} else {
						/* Get full size version of the "Featured Image" */
						get_the_image( array( 'image_class' => 'aligncenter', 'size' => 'full', 'meta_key' => false, 'link_to_post' => false ) );
					}
				 ?>

				<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'cakifo' ), 'after' => '</p>' ) ); ?>
			</div> <!-- .entry-content -->

			<footer class="entry-meta">
				<?php echo apply_atomic_shortcode( 'byline_image', '<div class="entry-meta-col">' . __( '[entry-published] by [entry-author] [entry-edit-link]', 'cakifo' ) . '</div>' ); ?>
				<?php echo apply_atomic_shortcode( 'entry_meta_image', '<div class="entry-meta-col">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="Tagged: "]', 'cakifo' ) . '</div>' ); ?>
			</footer> <!-- .entry-meta -->

			<?php do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular) ?>

		<?php else: ?>

			<header class="entry-header">
				<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
				<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
			</header> <!-- .entry-header -->

			<div class="entry-content">
				<?php
					/* Get content if it exists */
					if ( cakifo_post_has_content() ) {
						the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'cakifo' ) );
					} else {
						/* Get full size version of the "Featured Image" */
						get_the_image( array( 'image_class' => 'aligncenter', 'size' => 'full', 'meta_key' => false, 'link_to_post' => false ) );
					}
				 ?>

				<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'cakifo' ), 'after' => '</p>' ) ); ?>
			</div> <!-- .entry-content -->

			<footer class="entry-meta">
				<?php echo apply_atomic_shortcode( 'byline_image', '<div class="entry-meta-col">' . __( '[entry-published] by [entry-author] [entry-comments-link] ', 'cakifo' ) . '</div>' ); ?>
				<?php echo apply_atomic_shortcode( 'entry_meta_image', '<div class="entry-meta-col">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="Tagged: "] [entry-edit-link]', 'cakifo' ) . '</div>' ); ?>
			</footer> <!-- .entry-meta -->

		<?php endif; ?>

	</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); //cakifo_after_entry ?>
