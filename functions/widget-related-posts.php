<?php
/**
 * The related posts widget is created to give users the ability to show related posts after each posts
 *
 * @package Cakifo
 * @subpackage Classes
 * @since Cakifo 1.3
 * @version 1.1
 * @author Jesper Johansen <kontakt@jayj.dk>
 * @copyright Copyright (c) 2011-2012, Jesper Johansen
 * @link http://wpthemes.jayj.dk/cakifo
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 */

class Cakifo_Widget_Related_Posts extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 *
	 * @since Cakifo 1.3
	 */
	function __construct() {

		/* Set up the widget options. */
		$widget_options = array(
			'classname' => 'related-posts',
			'description' => esc_html__( 'Use this widget to list related posts to the current viewed post, based on taxonomies.', 'cakifo' )
		);

		/* Create the widget. */
		$this->WP_Widget( 'cakifo-related-posts', __( 'Cakifo: Related Posts', 'cakifo' ), $widget_options );

		/* Flush the cache when a post is updated or deleted */
		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @param array $sidebar
	 * @param array $instance
	 * @since Cakifo 1.3
	 */
	function widget( $sidebar, $instance ) {

		extract( $sidebar );

		$post_id = get_the_ID();

		/* Get related posts from post meta. */
		$related_posts = get_post_meta( $post_id, 'related', false );

		/* No related posts found, get them! */
		if ( empty( $related_posts ) ) :
			$this->_get_related_posts( $post_id, $instance );

			// Get the post meta again
			$related_posts = get_post_meta( $post_id, 'related', false );
		endif;

		/* Output the theme's $before_widget wrapper. */
		echo $before_widget;

		/* If a title was input by the user, display it. */
		if ( ! empty( $instance['title'] ) )
			echo $before_title . apply_filters( 'widget_title', sprintf( $instance['title'], get_the_title( $post_id ) ), $instance, $this->id_base ) . $after_title;

		/* Are there any related posts? */
		if ( ! empty( $related_posts ) ) :

			$show_thumbnails = (boolean) $instance['show_thumbnail'];

			// CSS class
			$class = ( $show_thumbnails ) ? 'with-thumbnails clearfix' : 'clearfix';

			do_atomic( 'before_related_posts_list' ); // cakifo_before_related_posts_list

			echo '<ul class="' . esc_attr( $class ) . '">';

			$i = 0;

			// Run through each post
			foreach( $related_posts as $post ) : ?>

				<?php $title = $post['title']; ?>

				<?php do_atomic( 'before_related_posts_list_item' ); // cakifo_before_related_posts_list_item ?>

				<li class="related-post">
					<?php do_atomic( 'open_related_posts_list_item' ); // cakifo_open_related_posts_list_item ?>

					<a href="<?php echo get_permalink( $post['post_id'] ); ?>" title="<?php printf( esc_attr__( 'Read %s', 'cakifo' ), esc_attr( $title ) ); ?>">
						<?php
							// Show post thumbnail
							if ( $show_thumbnails && ! empty( $post['thumbnail'] ) ) {
								echo wp_get_attachment_image( $post['thumbnail'], 'small', false, array( 'title' => esc_attr( $title ) ) );

							// Show post title
							} else {
								echo ( empty( $title ) ) ? '<span>' . _x( 'Untitled', 'untitled post', 'cakifo' ) . '</span>' : '<span>' . $title . '</span>';
							}
						?>
					</a>

					<?php do_atomic( 'close_related_posts_list_item' ); // cakifo_close_related_posts_list_item ?>
				</li> <!-- .related-post -->

				<?php do_atomic( 'after_related_posts_list_item' ); // cakifo_after_related_posts_list_item ?>

				<?php
					// Stop at the limit. This is used when there's more than one Related Posts widget at a page.
					$i++;
					if ( $i == $instance['limit'] ) break;
				?>
		<?php
			endforeach;

			echo '</ul>';

			do_atomic( 'after_related_posts_list' ); // cakifo_after_related_posts_list

		/* Nope, no related posts */
		else :

			_e( 'No related posts.', 'cakifo' );

		endif;

		/* Close the theme's widget wrapper. */
		echo $after_widget;
	}

	/**
	* Gets the related posts based on the choosen taxonomies
	* and puts them in the post meta
	*
	* @access private
	* @param int $post_id
	* @param array $args
	* @since Cakifo 1.3
	*/
	private function _get_related_posts( $post_id, $args ) {

		/* Set up the query */
		$related_query = array(
			'posts_per_page'      => $args['limit'],
			'orderby'             => $args['orderby'],
			'ignore_sticky_posts' => true,
			'post__not_in'        => array( $post_id ),
			'tax_query'           => array( 'relation' => 'OR' )
		);

		// Make sure the taxonomies are set. They won't be if an user updates the theme and doesn't resave the widget settings
		$taxonomies = ( isset( $args['taxonomies'] ) ) ? $args['taxonomies'] : array();

		/**
		 * Loop through each selected taxonomy
		 */
		foreach ( $taxonomies as $taxonomy ) :

			// Skip post formats
			if ( 'post_format' == $taxonomy )
				continue;

			$terms = get_the_terms( $post_id, $taxonomy );

			// No terms in the current taxonomy
			if ( ! $terms )
				continue;

			$term__in = array();

			foreach ( $terms as $term )
				$term__in[] = $term->slug;

			$related_query['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'terms'    => $term__in,
				'field'    => 'slug',
			);

		endforeach;

		/**
		 * Post formats query
		 */
		if ( in_array( 'post_format', $taxonomies ) )
			$format = ( get_post_format() ) ? 'post-format-' . get_post_format() : '';

			$related_query['tax_query'][] = array(
				'taxonomy' => 'post_format',
				'terms'    => array( $format ),
				'field'    => 'slug',
			);
		endif;

		/* Fire up the query */
		$related = new WP_Query( $related_query );

		/**
		 * Find all related posts and put them in a post meta field called 'related'
		 */
		if ( $related->have_posts() ) while ( $related->have_posts() ) : $related->the_post();

			add_post_meta( $post_id, 'related', array(
				'title'     => get_the_title(),
				'post_id'   => get_the_ID(),
				'thumbnail' => get_post_thumbnail_id()
			), false );

		endwhile;

		wp_reset_query();
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @param array $new_instance The new setting
	 * @param array $old_instance The old setting
	 * @since Cakifo 1.3
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $new_instance;

		$instance['title']          = strip_tags( $new_instance['title'] );
		$instance['limit']          = intval( $new_instance['limit'] );
		$instance['taxonomies']     = (array) $new_instance['taxonomies'];
		$instance['orderby']        = strip_tags( $new_instance['orderby'] );
		$instance['show_thumbnail'] = ( isset( $new_instance['show_thumbnail'] ) ? 1 : 0 );

		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * Flush the related posts meta when a post is updated, deleted or the settings has been changed
	 *
	 * @param int|null $post_ID
	 * @since Cakifo 1.3
	 */
	function flush_widget_cache( $post_ID = null ) {

		$related_meta = get_post_meta( $post_ID, 'related', false );

		/* A post is being updated or deleted */
		if ( isset( $post_ID ) ) :
			// Delete the related post meta for all the related posts
			if ( isset( $related_meta ) ) {

				$related_posts = array( $post_ID );

				foreach ( $related_meta as $related )
					$related_posts[] = $related['post_id'];

				foreach( get_posts( array( 'include' => $related_posts, 'post_type' => 'post' ) ) as $postinfo )
					delete_post_meta( $postinfo->ID, 'related' );
			}

		// The widget settings has been updated: delete the post meta for all posts
		else :

			 delete_metadata( 'post', null, 'related', '', true );

		endif;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @param array $instance The widget settings in the database
	 * @since Cakifo 1.3
	 */
	function form( $instance ) {

		/* Set up the default form values. */
		$defaults = array(
			'title'          => esc_attr__( 'Related Posts', 'cakifo' ),
			'limit'          => 5,
			'taxonomies'     => array( 'category', 'post_format' ),
			'orderby'        => 'random',
			'show_thumbnail' => true
		);

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );

		/* Create an array of orderby types. */
		$orderby = array(
			'random'        => _x( 'Random', 'order by', 'cakifo' ),
			'date'          => _x( 'Date', 'order by', 'cakifo' ),
			'comment_count' => _x( 'Comment count', 'order by', 'cakifo' ),
		);
	?>

		<div class="hybrid-widget-controls columns-1">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'cakifo' ); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
				<span class="description"><?php _e( 'Use %s as a placeholder for the title', 'cakifo' ); ?>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Number of posts to show:', 'cakifo' ); ?></label>
				<input type="number" min="1" class="smallfat code" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo esc_attr( $instance['limit'] ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'taxonomies' ); ?>"><?php _e( 'Taxonomies:', 'cakifo' ); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'taxonomies' ); ?>" name="<?php echo $this->get_field_name( 'taxonomies' ); ?>[]" multiple>
					<?php foreach ( get_object_taxonomies( 'post', 'objects' ) as $option_value => $option_label ) { ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php if ( in_array( $option_value, $instance['taxonomies'] ) ) selected( 1 ); ?>><?php echo esc_html( $option_label->label ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order by:', 'cakifo' ); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
					<?php foreach ( $orderby as $option_value => $option_label ) { ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['orderby'], $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p>
			<label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_thumbnail'], true ); ?> id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" /> <?php _e( 'Show post thumbnails?', 'cakifo' ); ?></label>
			</p>
		</div>
	<?php
	}
}

?>
