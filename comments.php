<?php
/**
 * Comments Template
 *
 * Lists comments and calls the comment form.  Individual comments have their own templates.  The 
 * hierarchy for these templates is $comment_type.php, comment.php.
 *
 * @package Cakifo
 * @subpackage Template
 */

/* Kill the page if trying to access this template directly. */
if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
	die( __( 'Please do not load this page directly. Thanks!', 'cakifo' ) );

/* If a post password is required or no comments are given and comments/pings are closed, return */
if ( post_password_required() || ( ! have_comments() && ! comments_open() && ! pings_open() ) )
	return;
?>

<section id="comments">

	<?php if ( have_comments() ) : ?>

        <h2 id="comments-number" class="comments-header"><?php comments_number( __( 'No Responses', 'cakifo' ), __( 'One Response', 'cakifo' ), __( '% Responses', 'cakifo' ) ); ?></h2>

        <?php do_atomic( 'before_comment_list' ); // cakifo_before_comment_list ?>

            <ol class="comment-list">
            	<?php wp_list_comments( hybrid_list_comments_args() ); ?>
            </ol> <!-- .comment-list -->

        <?php do_atomic( 'after_comment_list' ); // cakifo_after_comment_list ?>

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
            <nav class="pagination comment-pagination">
            	<h3 class="assistive-text"><?php _e( 'Comment navigation', 'cakifo' ); ?></h3>
            	<?php paginate_comments_links(); ?>
            </nav> <!-- .comment-navigation -->
        <?php endif; ?>

    <?php endif; // have_comments() ?>

    <?php if ( pings_open() && ! comments_open() ) : ?>

        <p class="comments-closed pings-open warning">
        	<?php printf( __( 'Comments are closed, but <a href="%1$s" title="Trackback URL for this post">trackbacks</a> and pingbacks are open.', 'cakifo' ), get_trackback_url() ); ?>
        </p> <!-- .comments-closed .pings-open .warning -->

    <?php elseif ( ! comments_open() ) : ?>

        <p class="comments-closed warning">
        	<?php _e( 'Comments are closed.', 'cakifo' ); ?>
        </p> <!-- .comments-closed .warning -->

    <?php endif; ?>

    <?php comment_form(); // Loads the comment form ?>

</section> <!-- #comments -->