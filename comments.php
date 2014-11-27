<?php
/**
 * Comments Template
 *
 * Lists comments and calls the comment form.  Individual comments have their own templates.  The
 * hierarchy for these templates is `$comment_type.php, comment.php`.
 *
 * @package Cakifo
 * @subpackage Template
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

		<h2 class="comments-title">
			<?php
				printf( _n( '1 comment', '%1$s comments', get_comments_number(), 'cakifo' ),
				number_format_i18n( get_comments_number() ) );
			?>
		</h2>

		<?php do_atomic( 'before_comment_list' ); ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				) );
			?>
		</ol> <!-- .comment-list -->

		<?php do_atomic( 'after_comment_list' ); ?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="pagination comment-pagination" role="navigation">
				<h3 class="screen-reader-text"><?php _e( 'Comment navigation', 'cakifo' ); ?></h3>
				<?php paginate_comments_links(); ?>
			</nav> <!-- .comment-navigation -->
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>

		<p class="no-comments"><?php _e( 'Comments are closed.', 'cakifo' ); ?></p>

	<?php endif; ?>

	<?php comment_form(); ?>

</section> <!-- .comments-area -->
