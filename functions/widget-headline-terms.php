<?php
/**
 * Headline Terms widget
 *
 * Gives users the ability to show the latest posts from a taxonomy term.
 *
 * @package    Cakifo
 * @subpackage Classes
 * @since      Cakifo 1.7.0
 * @version    1.0.0
 * @author     Jesper Johansen <kontakt@jayj.dk>
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 */
class Cakifo_Widget_Headline_Terms extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 */
	function __construct() {

		/* Set up the widget options. */
		$widget_options = array(
			'description' => esc_html__( 'List recent posts from a taxonomy term.', 'cakifo' )
		);

		/* Create the widget. */
		$this->WP_Widget( 'cakifo-headline-terms', __( 'Cakifo: Recent Posts by Term', 'cakifo' ), $widget_options );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @param array $sidebar
	 * @param array $instance
	 */
	function widget( $sidebar, $instance ) {

		$term = cakifo_get_headline_term( $instance['term'] );

		/* No term selected, abort. */
		if ( ! $term ) {
			return;
		}

		/**
		 * Creates the query to get the latest posts.
		 */
		$headlines = new WP_Query(
			array(
				'posts_per_page' => $instance['limit'],
				'no_found_rows'  => true,
				'tax_query'      => array(
					array(
						'terms'    => $term->term_id,
						'taxonomy' => $term->taxonomy,
						'field'    => 'id',
					)
				),
			)
		);

		/* No posts found, abort. */
		if ( ! $headlines->have_posts() ) {
			return;
		}

		echo $sidebar['before_widget'];

		do_atomic( 'open_headline_list' );

		/*
		 * Get the term name
		 */
		if ( 'post_format' == $term->taxonomy ) {
			$title = hybrid_get_plural_post_format_string( $term->slug );
			$icon = strtolower( $term->name );
		} else {
			$title = $term->name;
			$icon = 'standard';
		}

		$title = sprintf( '<a href="%s"><span class="widget-title-icon genericon genericon-%s"></span>%s</a>', esc_url( get_term_link( $term ) ), esc_attr( $icon ), apply_filters( 'widget_title', $title, $instance, $this->id_base ) );

		echo $sidebar['before_title'] . $title . $sidebar['after_title'];

		echo '<ol>';

		while ( $headlines->have_posts() ) : $headlines->the_post(); ?>

			<li class="headline-item clearfix">
				<?php do_atomic( 'open_headline_list_item' ); ?>

				<?php
					if ( $instance['show_thumbnail'] && current_theme_supports( 'get-the-image' ) ) {
						get_the_image( array(
							'size'          => 'small',
							'image_class'   => 'thumbnail',
							'meta_key'      => false,
							'default_image' => THEME_URI . '/images/default-thumb-mini.png'
						) );
					}
				?>

				<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

				<?php
					if ( $instance['show_meta'] ) {
						echo apply_atomic_shortcode( 'headline_meta', '<span class="headline-meta">' . __( '[entry-published] by [entry-author]', 'cakifo' ) . '</span>' );
					}
				?>

				<?php do_atomic( 'close_headline_list_item' ); ?>
			</li> <?php

		endwhile;

		echo '</ol>';

		do_atomic( 'close_headline_list' );

		echo $sidebar['after_widget'];
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @param array $instance
	 * @param array $old_instance
	 */
	function update( $instance, $old_instance ) {
		$instance['limit']          = intval( $instance['limit'] );
		$instance['term']           = strip_tags( $instance['term'] );
		$instance['show_thumbnail'] = ( isset( $instance['show_thumbnail'] ) ? 1 : 0 );
		$instance['show_meta']      = ( isset( $instance['show_meta'] ) ? 1 : 0 );

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @param array $instance The widget settings in the database.
	 */
	function form( $instance ) {

		/* Set up the default form values. */
		$defaults = array(
			'term'           => '',
			'limit'          => hybrid_get_setting( 'headlines_num_posts' ),
			'show_thumbnail' => true,
			'show_meta'      => true
		);

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );
	?>

		<p>
			<label for="<?php echo $this->get_field_id( 'term' ); ?>">
				<?php _e( 'Term:', 'cakifo' ); ?>
			</label>

			<select
				name="<?php echo $this->get_field_name( 'term' ); ?>"
				id="<?php echo $this->get_field_id( 'term' ); ?>"
				class="widefat">

				<option><?php _e( '&mdash; Select &mdash;', 'cakifo' ); ?></option>

				<?php foreach ( get_object_taxonomies( 'post', 'objects' ) as $tax_slug => $taxonomy ) : ?>

					<optgroup label="<?php echo esc_attr( $taxonomy->label ); ?>">
						<?php
							// Loop the taxonomy's terms
							foreach ( get_terms( $tax_slug ) as $term ) :

								// Generate the value containing taxonomy and term ID.
								$id = $tax_slug . ':' . $term->term_id;
							?>

							<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $id, $instance['term'] ); ?>>
								<?php printf( '%s: %s', esc_attr( $taxonomy->labels->singular_name ), esc_attr( $term->name )  ); ?>
							</option>

						<?php endforeach; ?>
					</optgroup>

				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>">
				<?php _e( 'Number of posts to show:', 'cakifo' ); ?>
			</label>

			<input
				type="number" min="1" max="99"
				id="<?php echo $this->get_field_id( 'limit' ); ?>"
				name="<?php echo $this->get_field_name( 'limit' ); ?>"
				value="<?php echo esc_attr( $instance['limit'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>">
				<input
					class="checkbox"
					type="checkbox"
					<?php checked( $instance['show_thumbnail'], true ); ?>
					id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>"
					name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" />

				<?php _e( 'Display post thumbnail?', 'cakifo' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_meta' ); ?>">
				<input
					class="checkbox"
					type="checkbox"
					<?php checked( $instance['show_meta'], true ); ?>
					id="<?php echo $this->get_field_id( 'show_meta' ); ?>"
					name="<?php echo $this->get_field_name( 'show_meta' ); ?>" />

				<?php _e( 'Display post date and author?', 'cakifo' ); ?>
			</label>
		</p>

	<?php
	}
}

?>
