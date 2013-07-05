<?php
/**
 * Creates additional theme settings
 *
 * @since Cakifo 1.0.0
 * @package Cakifo
 * @subpackage Functions
 * @link http://themehybrid.com/hybrid-core/features/theme-settings
 */

add_action( 'admin_menu', 'cakifo_theme_admin_setup' );

/**
 * Add the theme options to the Hybrid Core options page
 *
 * @since Cakifo 1.0.0
 * @return void
 */
function cakifo_theme_admin_setup() {

	/* Get the theme prefix */
	$prefix = hybrid_get_prefix();

	/* Create a settings meta box only on the theme settings page */
	add_action( 'load-appearance_page_theme-settings', 'cakifo_theme_settings_meta_boxes' );

	/* Add script and styles */
	add_action( 'admin_enqueue_scripts', 'cakifo_theme_settings_enqueue_scripts' );

	/* Add a filter to validate/sanitize the settings */
	add_filter( "sanitize_option_{$prefix}_theme_settings", 'cakifo_theme_validate_settings' );
}

/**
 * Loads the JavaScript and CSS files required for using the color picker on the theme settings
 * page, which allows users to change the link color
 *
 * @since Cakifo 1.4.0
 * @param string $hook_suffix The current page being viewed.
 * @return void
 */
function cakifo_theme_settings_enqueue_scripts( $hook_suffix ) {
	if ( $hook_suffix != hybrid_get_settings_page_name() )
		return;

	wp_enqueue_script( 'cakifo-theme-settings-multi-select', get_template_directory_uri() . '/functions/admin/multi-select.js', array( 'jquery' ), '1.6' );
	wp_enqueue_style( 'cakifo-theme-settings-multi-select', get_template_directory_uri() . '/functions/admin/multi-select.css', array(), '1.6' );

	wp_localize_script( 'cakifo-theme-settings-multi-select', 'cakifo_admin', array(
		'selectableHeader' => __( 'Selectable terms by taxonomy', 'cakifo' ),
		'selectionHeader'  => __( 'Selected terms', 'cakifo' ),
	) );
}

/**
 * Adds custom meta boxes to the theme settings page
 *
 * @since Cakifo 1.0.0
 * @return void
 */
function cakifo_theme_settings_meta_boxes() {

	/* Add a Featured content box */
	add_meta_box(
		'cakifo-theme-meta-box',// Custom meta box ID
		__( 'Cakifo settings', 'cakifo' ),
		'cakifo_theme_meta_box', // Custom callback function
		'appearance_page_theme-settings', // Page to load on, leave as is
		'normal',
		'low'
	);
}

/**
 * Function for displaying the meta box
 *
 * @since Cakifo 1.0.0
 * @return void
 */
function cakifo_theme_meta_box() { ?>

	<?php submit_button( esc_attr__( 'Update Settings', 'cakifo' ) ); ?>

	<table class="form-table">

		<!-- Slider -->
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'featured_show' ); ?>"><?php _e( 'Show "Featured Content" slider?', 'cakifo' ); ?></label>
			</th>

			<td>
				<p><input id="featured_show" name="<?php echo hybrid_settings_field_name( 'featured_show' ); ?>" type="checkbox" value="1" <?php checked( hybrid_get_setting( 'featured_show' ), 1 ); ?> /></p>
				<p><?php _e( 'Check to display the "Featured Content" slider', 'cakifo' ); ?></p>
			</td>
		</tr>

		<!-- Featured Category -->
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'featured_category' ); ?>"><?php _e( 'Featured Category', 'cakifo' ); ?></label>
			</th>

			<td>
				<?php $categories = get_categories(); ?>

				<p>
					<select id="<?php echo hybrid_settings_field_id( 'featured_category' ); ?>" name="<?php echo hybrid_settings_field_name( 'featured_category' ); ?>">
					<option value="" <?php selected( hybrid_get_setting( 'featured_category' ), '' ); ?>></option>

						<?php foreach ( $categories as $cat ) { ?>
							<option value="<?php echo $cat->term_id; ?>" <?php selected( hybrid_get_setting( 'featured_category' ), $cat->term_id ); ?>><?php echo esc_attr( $cat->name ); ?></option>
						<?php } ?>

					</select>
				</p>

				<p><?php _e( 'Leave blank to use sticky posts', 'cakifo' ); ?></p>
			</td>
		</tr>

		<!-- Number of featured posts -->
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'featured_posts' ); ?>"><?php _e( 'Featured Posts', 'cakifo' ); ?></label>
			</th>
			<td>
				<p><input type="number" min="-1" id="<?php echo hybrid_settings_field_id( 'featured_posts' ); ?>" name="<?php echo hybrid_settings_field_name( 'featured_posts' ); ?>" value="<?php echo esc_attr( hybrid_get_setting( 'featured_posts' ) ); ?>" class="small-text" /></p>
				<p><?php _e( 'How many featured posts should be shown? <code>-1</code> will show all posts in the category.', 'cakifo' ); ?>
				<?php printf( __( '%s is the default', 'cakifo' ), '<code>5</code>' ); ?></p>
			</td>
		</tr>

		<!-- Headline taxonomies -->
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'headlines_category' ); ?>"><?php _e( 'Headline Taxonomies', 'cakifo' ); ?></label>
			</th>

			<td>
				<select name="<?php echo hybrid_settings_field_name( 'headlines_category' ); ?>[]" multiple="multiple" id="<?php echo hybrid_settings_field_id( 'headlines_category' ); ?>">

				<?php
					/* Get the setting. */
					$settings = (array) hybrid_get_setting( 'headlines_category' );
				?>

				<?php foreach ( get_object_taxonomies( 'post', 'objects' ) as $slug => $taxonomy ) : ?>

					<optgroup label="<?php echo esc_attr( $taxonomy->label ); ?>">
						<?php
							foreach ( get_terms( $slug ) as $term ) :
								/* Generate the value containing taxonomy and term ID. */
								$id = $slug . ':' . $term->term_id;

								/* Check if the current term is selected. */
								foreach( $settings as $selected ) {

									/* in_array() is used for back-compat. */
									if ( ( is_array( $selected ) && $term->term_id == $selected[1] ) || in_array( $term->term_id, $settings ) ) {
										$selected = true;
										break;
									} else {
										$selected = false;
										continue;
									}
								}
							?>

							<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $selected ); ?>>
								<?php printf( '%s: %s', esc_attr( $taxonomy->labels->singular_name ), esc_html( $term->name )  ); ?>
							</option>
						<?php endforeach; ?>
					</optgroup>

				<?php endforeach; ?>
				</select>

				<p><?php _e( 'Used on the Front Page template. Click on a term to add/remove it.', 'cakifo' ); ?>
			</p>
			<td>
		</tr>

		<!-- Number of Headline posts -->
		<tr>
			<th><label for="<?php echo hybrid_settings_field_id( 'headlines_num_posts' ); ?>"><?php _e( 'Headlines Posts', 'cakifo' ); ?></label></th>
			<td>
				<p><input type="number" min="1" id="<?php echo hybrid_settings_field_id( 'headlines_num_posts' ); ?>" name="<?php echo hybrid_settings_field_name( 'headlines_num_posts' ); ?>" value="<?php echo esc_attr( hybrid_get_setting( 'headlines_num_posts' ) ); ?>" class="small-text" /></p>

				<p><?php _e( 'How many posts should be shown per headline category?', 'cakifo' ); ?> <?php printf( __( '%s is the default', 'cakifo' ), '<code>4</code>' ); ?></p></p>
			</td>
		</tr>

		<!-- Twitter username -->
		<tr>
			<?php
				/**
				 * Get Twitter username
				 */
				$current_user         = wp_get_current_user();
				$twitter_current_user = $current_user->twitter;
				$twitter_setting      = hybrid_get_setting( 'twitter_username' );
				$twitter_username     = '';

				if ( isset( $twitter_setting ) )
					$twitter_username = $twitter_setting;
				elseif( isset( $twitter_current_user ) )
					$twitter_username = $current_user->twitter;
			?>

			<th>
				<label for="<?php echo hybrid_settings_field_id( 'twitter_username' ); ?>"><?php _e( 'Twitter username', 'cakifo' ); ?></label>
			</th>
			<td>
				<p><input type="text" id="<?php echo hybrid_settings_field_id( 'twitter_username' ); ?>" name="<?php echo hybrid_settings_field_name( 'twitter_username' ); ?>" value="<?php echo esc_attr( $twitter_username ); ?>" /></p>
				<p><?php _e( 'Your Twitter username (if you have one)', 'cakifo' ); ?>
			</td>
		</tr>

	</table> <!-- .form-table --> <?php
}

/**
 * Validates theme settings
 *
 * @since Cakifo 1.0.0
 * @param array $input The entered theme options
 * @return array       The validated theme options
 */
function cakifo_theme_validate_settings( $input ) {

	/* Validate and/or sanitize the options */
	$input['featured_show']     = ( isset( $input['featured_show'] ) ? 1 : 0 );
	$input['featured_category'] = absint( $input['featured_category'] );
	$input['twitter_username']  = wp_filter_nohtml_kses( $input['twitter_username'] );
	$input['featured_posts']    = ( $input['featured_posts'] ? intval( $input['featured_posts'] ) : 5 ); // 5 is the default number of featured posts

	/* Save the headline terms in an array containing the taxonomy and term ID. */
	$headlines = array();

	foreach( $input['headlines_category'] as $headline ) {
		$headlines[] = explode( ':', $headline );
	}

	$input['headlines_category'] = $headlines;

	/* Return the array of theme settings */
	return $input;
}

?>
