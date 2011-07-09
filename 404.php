<?php
/**
 * 404 Template
 *
 * The 404 template is used when a reader visits an invalid URL on your site. By default, the template will 
 * display a generic message.
 *
 * @package Cakifo
 * @subpackage Template
 * @link http://codex.wordpress.org/Creating_an_Error_404_Page
 */

get_header(); // Loads the header.php template ?>

	<?php do_atomic( 'before_main' ); // cakifo_before_main ?>

    <div id="main">

		<?php do_atomic( 'open_main' ); // cakifo_open_main ?>

            <article id="post-0" class="<?php hybrid_entry_class(); ?>">

                <h1 class="error-404-title entry-title"><?php _e( "Oops! 404! 404!.. Help!.. We can't find the page!", hybrid_get_textdomain() ); ?></h1>

                <div class="entry-content">
                    <p><?php printf( __( "You tried going to %1$s, and it doesn't exist. All is not lost! You can search for what you're looking for.", hybrid_get_textdomain() ), '<code>' . site_url( esc_url( $_SERVER['REQUEST_URI'] ) ) . '</code>' ); ?></p>

                    <?php get_search_form(); // Loads the searchform.php template ?>

                    <?php do_atomic( '404_content' ); // You can add more content here by using the 'cakifo_404_content' actino ?>
                </div> <!-- .entry-content -->

            </article> <!-- .hentry -->

            <?php
				/**
				 * Widget ready 404 page
				 *
				 * You can use widgets to put content here
				 */
				if ( is_active_sidebar( 'error-page' ) ) :

					echo '<div class="not-found-widgets clearfix">';
						dynamic_sidebar( 'error-page' );
					echo '</div>';

				endif;
            ?>

        <?php do_atomic( 'close_main' ); // cakifo_close_main ?>

    </div> <!-- #main -->

    <?php do_atomic( 'after_main' ); // cakifo_after_main ?>

<?php get_footer(); // Loads the footer.php template ?>