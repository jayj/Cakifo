<?php
/**
 * Template functions related to posts.
 *
 * @package    Cakifo
 * @subpackage Functions
 */
/**
 * Outputs the post thumbnail if the theme supports the Get The Image extension.
 *
 * @since Cakifo 1.7.0
 *
 * @param string $size  Optional. The image size. Default: thumbnail.
 * @return string       HTML for the thumbnail.
 */
function cakifo_post_thumbnail( $size = 'thumbnail' ) {
	if ( ! current_theme_supports( 'get-the-image' ) ) {
		return;
	}

	get_the_image( array(
		'size'       => $size,
		'attachment' => false
	));
}

/**
 * Outputs the current post's date.
 *
 * @since Cakifo 1.7.0
 *
 * @return void
 */
function cakifo_post_date() {
	echo cakifo_get_post_date();
}

/**
 * Get the current post's date.
 *
 * @since  Cakifo 1.7.0
 *
 * @return string
 */
function cakifo_get_post_date() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	return sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		get_the_date(),
		esc_attr( get_the_modified_date( 'c' ) ),
		get_the_modified_date()
	);
}


/**
 * Outputs the current post's author with link to the author archive page.
 *
 * @since Cakifo 1.7.0
 *
 * @return void
 */
function cakifo_post_author() {
	echo cakifo_get_post_author();
}

/**
 * Get the current post's author with link to the author archive page.
 *
 * @since Cakifo 1.7.0
 *
 * @return string
 */
function cakifo_get_post_author() {
	return sprintf( '<span class="author vcard">%1$s <a class="url fn n" href="%2$s">%3$s</a></span>',
		_x( 'by', 'Used before post author name.', 'cakifo' ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		get_the_author()
	);
}
if ( ! function_exists( 'cakifo_author_box' ) ) :
/**
 * Function to add an author box
 *
 * @since Cakifo 1.0.0
 */
function cakifo_author_box() { ?>

	<?php if ( get_the_author_meta( 'description' ) && is_multi_author() ) : ?>

		<?php do_atomic( 'before_author_box' ); ?>

		<div class="author-profile clearfix vcard">

			<?php do_atomic( 'open_author_box' ); ?>

			<?php
				$author = sprintf( '<a class="url fn n" href="%1$s">%2$s</a>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					get_the_author()
				);
			?>

			<h4 class="author-name">
				<?php printf( __( 'Article written by %s', 'cakifo' ), $author ); ?>
			</h4>

			<?php echo get_avatar( get_the_author_meta( 'user_email' ), 96 ); ?>

			<div class="author-description author-bio">
				<?php echo wpautop( get_the_author_meta( 'description' ) ); ?>
			</div>

			<?php do_atomic( 'close_author_box' ); ?>

		</div> <!-- .author-profile -->

		<?php do_atomic( 'after_author_box' );

	endif;
}
endif; // cakifo_author_box