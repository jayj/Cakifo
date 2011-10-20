<?php

/**
 * Creates additional theme settings
 *
 * @link http://themehybrid.com/hybrid-core/features/theme-settings
 * @since 1.0
 */

add_action( 'admin_menu', 'cakifo_theme_admin_setup' );

function cakifo_theme_admin_setup() {

	/* Get the theme prefix */
	$prefix = hybrid_get_prefix();

	/* Create a settings meta box only on the theme settings page */
	add_action( 'load-appearance_page_theme-settings', 'cakifo_theme_settings_meta_boxes' );

	/* Add a filter to validate/sanitize the settings */
	add_filter( "sanitize_option_{$prefix}_theme_settings", 'cakifo_theme_validate_settings' );
}

/* Adds custom meta boxes to the theme settings page */
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

/* Function for displaying the meta box */
function cakifo_theme_meta_box() { ?>

	<table class="form-table">

		<tr>
        	<th>
            	<label for="<?php echo hybrid_settings_field_id( 'featured_show' ); ?>"><?php _e( 'Show Featured Content slider?', 'cakifo' ); ?></label>
            </th>

            <td>
            	<p><input id="featured_show" name="<?php echo hybrid_settings_field_name( 'featured_show' ); ?>" type="checkbox" value="1" <?php checked( hybrid_get_setting( 'featured_show' ), 1 ); ?> /></p>
				<p><?php _e( 'Check to display the "Featured Content" slider', 'cakifo' ); ?></p>
            </td>
        </tr>

		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'featured_category' ); ?>"><?php _e( 'Featured Category:', 'cakifo' ); ?></label>
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

		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'featured_posts' ); ?>"><?php _e( 'Featured Posts:', 'cakifo' ); ?></label>
			</th>
			<td>
				<p><input type="number" min="-1" id="<?php echo hybrid_settings_field_id( 'featured_posts' ); ?>" name="<?php echo hybrid_settings_field_name( 'featured_posts' ); ?>" value="<?php echo esc_attr( hybrid_get_setting( 'featured_posts' ) ); ?>" class="small-text" /></p>
				<p><?php _e( 'How many featured posts should be shown? <code>-1</code> will show all posts in the category.', 'cakifo' ); ?>
                <?php printf( __( '%s is the default', 'cakifo' ), '<code>5</code>' ); ?></p>
			</td>
		</tr>

		<tr>
			<th>
            	<label for="<?php echo hybrid_settings_field_id( 'headlines_category' ); ?>"><?php _e( 'Headline Categories:', 'cakifo' ); ?></label>
			</th>
			<td>
				<p>
                	<label for="<?php echo hybrid_settings_field_id( 'headlines_category' ); ?>"><?php _e( 'Multiple categories may be chosen by holding the <code>Ctrl</code> key and selecting.', 'cakifo' ); ?></label>
                    <br />
                    
                    <select id="<?php echo hybrid_settings_field_id( 'headlines_category' ); ?>" name="<?php echo hybrid_settings_field_name( 'headlines_category' ); ?>[]" multiple="multiple" style="height:150px;">
						<?php foreach( $categories as $cat ) { ?>
                            <option value="<?php echo $cat->term_id; ?>" <?php if ( is_array( hybrid_get_setting( 'headlines_category' ) ) && in_array( $cat->term_id, hybrid_get_setting( 'headlines_category' ) ) ) echo ' selected="selected"'; ?>><?php echo esc_html( $cat->name ); ?></option>
                        <?php } ?>
                    </select>
                </p>
			</td>
		</tr>
		<tr>
			<th><label for="<?php echo hybrid_settings_field_id( 'headlines_num_posts' ); ?>"><?php _e( 'Headlines Posts:', 'cakifo' ); ?></label></th>
			<td>
				<p><input type="number" min="1" id="<?php echo hybrid_settings_field_id( 'headlines_num_posts' ); ?>" name="<?php echo hybrid_settings_field_name( 'headlines_num_posts' ); ?>" value="<?php echo esc_attr( hybrid_get_setting( 'headlines_num_posts' ) ); ?>" class="small-text" /></p>
                
				<p><?php _e( 'How many posts should be shown per headline category?', 'cakifo' ); ?> <?php printf( __( '%s is the default', 'cakifo' ), '<code>4</code>' ); ?></p></p>
			</td>
		</tr>

		<tr>
        	<?php $current_user = wp_get_current_user(); ?>

			<th>
				<label for="<?php echo hybrid_settings_field_id( 'twitter_username' ); ?>"><?php _e( 'Twitter username:', 'cakifo' ); ?></label>
			</th>
			<td>
				<p><input type="text" id="<?php echo hybrid_settings_field_id( 'twitter_username' ); ?>" name="<?php echo hybrid_settings_field_name( 'twitter_username' ); ?>" value="<?php if ( hybrid_get_setting( 'twitter_username' ) != '' ) echo esc_attr( hybrid_get_setting( 'twitter_username' ) ); elseif( isset( $current_user->twitter ) ) echo esc_attr( $current_user->twitter ); ?>" /></p>
				<p><?php _e( 'Your Twitter username (if you have one)', 'cakifo' ); ?>
			</td>
		</tr>

	</table> <!-- .form-table --> <?php
}

/* Validates theme settings */
function cakifo_theme_validate_settings( $input ) {

	/* Validate and/or sanitize the options */
	$input['featured_show'] = ( isset( $input['featured_show'] ) ? 1 : 0 );
	$input['featured_category'] = absint( $input['featured_category'] );
	$input['twitter_username'] = wp_filter_nohtml_kses( $input['twitter_username'] );
	$input['featured_posts'] = ( $input['featured_posts'] ? intval( $input['featured_posts'] ) : '5' ); // 5 is the default number of featured posts

	/* Return the array of theme settings */
	return $input;
}

?>