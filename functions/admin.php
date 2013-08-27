<?php
/**
 * Creates additional theme settings
 *
 * @since      Cakifo 1.0.0
 * @package    Cakifo
 * @subpackage Functions
 */

add_action( 'admin_menu', 'cakifo_theme_admin_setup' );

/**
 * Add the theme options to the Hybrid Core options page
 *
 * @since Cakifo 1.0.0
 */
function cakifo_theme_admin_setup() {

	$prefix = hybrid_get_prefix();

	// Create a settings meta box only on the theme settings page.
	add_action( 'load-appearance_page_theme-settings', 'cakifo_theme_settings_meta_boxes' );

	// Add script and styles.
	add_action( 'admin_enqueue_scripts', 'cakifo_theme_settings_enqueue_scripts' );

	// Add a filter to validate/sanitize the settings.
	add_filter( "sanitize_option_{$prefix}_theme_settings", 'cakifo_theme_validate_settings' );
}

/**
 * Loads the JavaScript and CSS files required for the
 * Theme Settings page.
 *
 * @since Cakifo 1.4.0
 * @param string $hook_suffix The current page being viewed.
 * @return void
 */

/**
 * Loads the JavaScript and CSS files required for the
 * Theme Settings page.
 *
 * @since  Cakifo 1.4.0
 * @param  string  $hook_suffix The current page.
 */
function cakifo_theme_settings_enqueue_scripts( $hook_suffix ) {
	if ( $hook_suffix != hybrid_get_settings_page_name() )
		return;

	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'cakifo-theme-settings-chosen', get_template_directory_uri() . '/functions/admin/chosen.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0' );
	wp_enqueue_style( 'cakifo-theme-settings-chosen', get_template_directory_uri() . '/functions/admin/chosen.css', array(), '1.0' );
}

/**
 * Adds custom meta boxes to the theme settings page
 *
 * @since Cakifo 1.0.0
 */
function cakifo_theme_settings_meta_boxes() {

	/* Add a Featured content box */
	add_meta_box(
		'cakifo-theme-meta-box',// Custom meta box ID
		__( 'Front Page settings', 'cakifo' ),
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
 */
function cakifo_theme_meta_box() { ?>

	<h2><?php _e( 'How to use the front page template: (Edit Page) Page Attributes > Template > Front Page', 'cakifo' ); ?></h2>

<table class="form-table">

	<!-- Slider -->
	<tr>
		<th>
			<label for="<?php echo hybrid_settings_field_id( 'featured_show' ); ?>"><?php _e( 'Show "Featured Content" slider?', 'cakifo' ); ?></label>
		</th>

		<td>
			<label>
				<input id="featured_show" name="<?php echo hybrid_settings_field_name( 'featured_show' ); ?>" type="checkbox" <?php checked( hybrid_get_setting( 'featured_show' ) ); ?> />&nbsp;
			 	<?php _e( 'Check to display the "Featured Content" slider', 'cakifo' ); ?>
			 </label>
		</td>
	</tr>

	<!-- Featured Category -->
	<tr>
		<th>
			<label for="<?php echo hybrid_settings_field_id( 'featured_category' ); ?>"><?php _e( 'Featured Category', 'cakifo' ); ?></label>
		</th>

		<td>
			<p>
				<select name="<?php echo hybrid_settings_field_name( 'featured_category' ); ?>"
					id="<?php echo hybrid_settings_field_id( 'featured_category' ); ?>"
					data-placeholder="<?php esc_attr_e( 'Select a category', 'cakifo' ); ?>"
					class="<?php if ( is_rtl() ) echo 'chosen-rtl'; ?>">

					<option value=""<?php selected( hybrid_get_setting( 'featured_category' ), '' ); ?>></option>

					<?php foreach ( get_categories() as $category ) { ?>
						<option value="<?php echo $category->term_id; ?>"<?php selected( hybrid_get_setting( 'featured_category' ), $category->term_id ); ?>><?php echo esc_attr( $category->name ); ?></option>
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
			<label for="<?php echo hybrid_settings_field_id( 'headlines_category' ); ?>"><?php _e( 'Headline Terms', 'cakifo' ); ?></label>
		</th>

		<td>
			<?php
				$get_selected_terms = hybrid_get_setting( 'headlines_category' );
				$exclude_term_ids   = array();

				// Get all the selected terms IDs in an array
				foreach( $get_selected_terms as $term ) :

					// Back-compat when only an ID is used.
					if ( is_string( $term ) || is_int( $term ) )
						$exclude_term_ids[] = $term;
					else
						$exclude_term_ids[] = $term[1];

				endforeach;

				$exclude_term_ids = wp_parse_id_list( $exclude_term_ids );
			?>

			<select name="<?php echo hybrid_settings_field_name( 'headlines_category' ); ?>[]"
				id="<?php echo hybrid_settings_field_id( 'headlines_category' ); ?>"
				data-placeholder="<?php esc_attr_e( 'Select terms by taxonomy', 'cakifo' ); ?>"
				multiple="multiple"
				class="chosen-sortable<?php if ( is_rtl() ) echo ' chosen-rtl'; ?>">

				<?php
					// First loop through each selected term.
					foreach ( $get_selected_terms as $selected_term ) :

						// Back-compat when only an ID is used.
						if ( is_string( $selected_term ) || is_int( $selected_term ) ) {
							$tax_slug = 'category';
							$term_id = $selected_term;
						} else {
							$tax_slug = $selected_term[0];
							$term_id  = $selected_term[1];
						}

						// Generate the value containing the taxonomy and term ID.
						$id = $tax_slug . ':' . $term_id;

						// Get term and taxonomy information.
						$term = get_term_by( 'id', $term_id, $tax_slug );
						$tax  = get_taxonomy( $term->taxonomy );
				?>

						<option value="<?php echo esc_attr( $id ); ?>" selected="selected">
							<?php printf( '%s: %s', esc_attr( $tax->labels->singular_name ), esc_html( $term->name ) ); ?>
						</option>

				<?php endforeach; ?>

				<?php foreach ( get_object_taxonomies( 'post', 'objects' ) as $tax_slug => $taxonomy ) : ?>

					<optgroup label="<?php echo esc_attr( $taxonomy->label ); ?>">

						<?php
							// Loop through the rest of the terms.
							foreach ( get_terms( $tax_slug, array( 'exclude' => $exclude_term_ids ) ) as $term ) :

								// Generate the value containing taxonomy and term ID.
								$id = $tax_slug . ':' . $term->term_id;
							?>

							<option value="<?php echo esc_attr( $id ); ?>">
								<?php printf( '%s: %s', esc_attr( $taxonomy->labels->singular_name ), esc_html( $term->name )  ); ?>
							</option>

						<?php endforeach; ?>
					</optgroup>

				<?php endforeach; ?>
			</select>

			<p><?php _e( 'Click to select a term. You can type the name to easier find the term.', 'cakifo' ); ?></p>
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

</table> <!-- .form-table --> <?php
}

/**
 * Validate the saved theme settings.
 *
 * @since  Cakifo 1.0.0
 * @param  array  $input The entered theme options.
 * @return array         The validated theme options.
 */
function cakifo_theme_validate_settings( $input ) {

	// Validate and/or sanitize the options.
	$input['featured_show']     = ( ! empty( $input['featured_show'] ) ? 1 : 0 );
	$input['featured_category'] = absint( $input['featured_category'] );
	$input['featured_posts']    = ( $input['featured_posts'] ? intval( $input['featured_posts'] ) : 5 ); // 5 is the default number of featured posts

	// Save the headline terms in an array containing the taxonomy and term ID.
	$headlines = array();

	foreach( $input['headlines_category'] as $headline ) {
		$headlines[] = explode( ':', $headline );
	}

	$input['headlines_category'] = $headlines;

	// Return the array of theme setting.
	return $input;
}

?>
