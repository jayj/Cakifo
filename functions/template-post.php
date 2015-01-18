<?php
/**
 * Template functions related to posts.
 *
 * @package    Cakifo
 * @subpackage Functions
 */

/**
 * Print HTML with meta information for the current post-date/time and author.
 *
 * @since Cakifo 1.7.0
 */
function cakifo_posted_on() {

	$post_type = get_post_type();
	$post_format = get_post_format();
	$post_format_link = '';

	/**
	 * Filter the entry meta separator.
	 *
	 * @since Cakifo 1.7.0
	 *
	 * @param string $separator
	 */
	$separator = apply_filters( 'cakifo_entry_meta_separator', '<span class="sep"> | </span>' );


	// Turn on output buffering so the whole string have apply_filters at the end
	ob_start();


	// Post format archive link
	if ( has_post_format( $post_format ) ) {
		printf( '<span class="entry-format">%s </span>', cakifo_get_post_format_link() );
		$published_string = _x( 'published on', 'Used after post format name and before publish date.', 'cakifo' );
	} else {
		$post_format = 'standard';
		$published_string = _x( 'Published on', 'Used before publish date.', 'cakifo' );
	}

	// Published date
	if ( in_array( $post_type, array( 'post', 'attachment' ) ) ) {

		$time_string = cakifo_get_post_date();

		printf( '<span class="posted-on">%1$s <a href="%2$s" rel="bookmark">%3$s</a> </span>',
			$published_string,
			esc_url( get_permalink() ),
			$time_string
		);
	}

	// Author
	if ( post_type_supports( $post_type, 'author' ) || is_multi_author() ) {
		cakifo_post_author();
	}

	// Comments link
	if ( post_type_supports( $post_type, 'comments' ) && ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">' . $separator;
			comments_popup_link( __( 'Leave a comment', 'cakifo' ), __( '1 Comment', 'cakifo' ), __( '% Comments', 'cakifo' ) );
		echo '</span>';
	}

	// Edit link
	edit_post_link( __( 'Edit', 'cakifo' ), '<span class="edit">' . $separator, '</span>' );


	// Attachment image metadata
	if ( is_attachment() && wp_attachment_is_image() ) {
		$post_format = 'attachment_image';

		echo '<div class="image-sizes">';
		printf( __( 'Sizes: %s', 'cakifo' ), cakifo_get_image_size_links() );
		echo '</div>';

	} else {
		$post_format = 'attachment';
	}

	$output = ob_get_clean();

	/**
	 * Filter the posted on meta information.
	 *
	 * The filter name is based on the post format:
	 * 	- Standard posts:    `cakifo_byline_standard`
	 *  - Post formats:      `cakifo_byline_$format`
	 *  - Image attachments: `cakifo_byline_attachment_image`
	 *  - Other attachments: `cakifo_byline_attachment`
	 *
	 * This filter provides backward compatibility with earlier versions of Cakifo
	 * that used shortcodes in the string. A compatibility plugin with the shortcodes
	 * will be released soon.
	 *
	 *
	 * @since Cakifo 1.0
	 * @since Cakifo 1.7.0 Added the $post_id parameter
	 *
	 * @param string $output  The output string
	 * @param int    $post_id ID of the current post
	 */
	echo do_shortcode( apply_filters( "cakifo_byline_{$post_format}", $output, get_the_ID() ) );
}

/**
 * Prints HTML with meta information for the categories, tags.
 *
 * @since Cakifo 1.7.0
 */
function cakifo_entry_meta() {

	$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'cakifo' ) );

	$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'cakifo' ) );


	// Turn on output buffering so the whole string have apply_filters at the end
	ob_start();


	if ( $categories_list && cakifo_categorized_blog() ) {
		printf( __( 'Posted in %s', 'cakifo' ), $categories_list );

		if ( $tags_list ) {
			echo apply_filters( 'cakifo_entry_meta_separator', '<span class="sep"> | </span>' );
		}
	}

	if ( $tags_list ) {
		printf( __( 'Tagged %s', 'cakifo' ), $tags_list );
	}

	$output = ob_get_clean();


	// Get the post format name
	if ( has_post_format( get_post_format() ) ) {
		$post_format = get_post_format();
	} else {
		$post_format = 'standard';
	}

	/**
	 * Filter the taxonomy meta information.
	 *
	 * The filter name is based on the post format:
	 * 	- Standard posts:    `cakifo_entry_meta_standard`
	 *  - Post formats:      `cakifo_entry_meta_$format`
	 *
	 * This filter provides backward compatibility with earlier versions of Cakifo
	 * that used shortcodes in the string. A compatibility plugin with the shortcodes
	 * will be released soon.
	 *
	 * @since Cakifo 1.0
	 * @since Cakifo 1.7.0 Added the $post_id parameter
	 *
	 * @param string $output  The output string
	 * @param int    $post_id ID of the current post
	 */
	echo do_shortcode( apply_filters( "cakifo_entry_meta_{$post_format}", $output, get_the_ID() ) );
}


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


/**
 * Outputs link to the post format archive.
 *
 * @since Cakifo 1.7.0
 *
 * @return void
 */
function cakifo_post_format_link() {
	echo cakifo_get_post_format_link();
}

/**
 * Generated a link to the current post format's archive.
 *
 * @since Cakifo 1.7.0
 *
 * @return string
 */
function cakifo_get_post_format_link() {
	$format = get_post_format();

	if ( has_post_format( $format ) ) {
		return sprintf( '<a href="%s" class="post-format-link">%s</a>',
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}
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


/**
 * Determine whether blog/site has more than one category.
 *
 * @since Cakifo 1.7.0
 *
 * @return bool True of there is more than one category, false otherwise.
 */
function cakifo_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'cakifo_categories' ) ) ) {

		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			'number'     => 2, // We only need to know if there is more than one category.
		) );

		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'cakifo_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Flush out the transients used in {@see cakifo_categorized_blog()}.
 *
 * @since Cakifo 1.7.0
 */
function cakifo_category_transient_flusher() {
	delete_transient( 'cakifo_categories' );
}

add_action( 'edit_category', 'cakifo_category_transient_flusher' );
add_action( 'save_post',     'cakifo_category_transient_flusher' );