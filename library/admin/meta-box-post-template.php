<?php
/**
 * Adds the template meta box to the post editing screen for public post types.  This feature allows users and 
 * devs to create custom templates for any post type, not just pages as default in WordPress core.  The 
 * functions in this file create the template meta box and save the template chosen by the user when the 
 * post is saved.  This file is only used if the theme supports the 'hybrid-core-template-hierarchy' feature.
 *
 * @package HybridCore
 * @subpackage Admin
 */

/* Add the post template meta box on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'hybrid_meta_box_post_add_template', 10, 2 );

/* Save the post template meta box data on the 'save_post' hook. */
add_action( 'save_post', 'hybrid_meta_box_post_save_template', 10, 2 );

/**
 * Adds the post template meta box for all public post types, excluding the 'page' post type since WordPress 
 * core already handles page templates.
 *
 * @since 1.2.0
 * @return void
 */
function hybrid_meta_box_post_add_template( $post_type, $post ) {

	$post_type_object = get_post_type_object( $post_type );

	/* Only add meta box if current user can edit, add, or delete meta for the post. */
	if ( ( 'page' !== $post_type ) && ( true === $post_type_object->public ) && ( current_user_can( 'edit_post_meta', $post->ID ) || current_user_can( 'add_post_meta', $post->ID ) || current_user_can( 'delete_post_meta', $post->ID ) ) )
		add_meta_box( 'hybrid-core-post-template', __( 'Template', 'hybrid-core' ), 'hybrid_meta_box_post_display_template', $post_type, 'side', 'default' );
}

/**
 * Displays the post template meta box.
 *
 * @since 1.2.0
 * @return void
 */
function hybrid_meta_box_post_display_template( $object, $box ) {

	/* Get the post type object. */
	$post_type_object = get_post_type_object( $object->post_type );

	/* Get a list of available custom templates for the post type. */
	$templates = hybrid_get_post_templates( array( 'label' => array( "{$post_type_object->labels->singular_name} Template", "{$post_type_object->name} Template" ) ) );

	wp_nonce_field( basename( __FILE__ ), 'hybrid-core-post-meta-box-template' ); ?>

	<p>
		<?php if ( 0 != count( $templates ) ) { ?>
			<select name="hybrid-post-template" id="hybrid-post-template" class="widefat">
				<option value=""></option>
				<?php foreach ( $templates as $label => $template ) { ?>
					<option value="<?php echo esc_attr( $template ); ?>" <?php selected( esc_attr( get_post_meta( $object->ID, "_wp_{$post_type_object->name}_template", true ) ), esc_attr( $template ) ); ?>><?php echo esc_html( $label ); ?></option>
				<?php } ?>
			</select>
		<?php } else { ?>
			<?php _e( 'No templates exist for this post type.', 'hybrid-core' ); ?>
		<?php } ?>
	</p>
<?php
}

/**
 * Saves the post template meta box settings as post metadata.
 *
 * @since 1.2.0
 * @param int $post_id The ID of the current post being saved.
 * @param int $post The post object currently being saved.
 * @return void|int
 */
function hybrid_meta_box_post_save_template( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['hybrid-core-post-meta-box-template'] ) || !wp_verify_nonce( $_POST['hybrid-core-post-meta-box-template'], basename( __FILE__ ) ) )
		return $post_id;

	/* Check if the current user has permission to edit the post meta. */
	if ( !current_user_can( 'edit_post_meta', $post_id ) )
		return $post_id;

	/* Return here if the template is not set. There's a chance it won't be if the post type doesn't have any templates. */
	if ( !isset( $_POST['hybrid-post-template'] ) )
		return $post_id;

	/* Get the posted meta value. */
	$new_meta_value = strip_tags( $_POST['hybrid-post-template'] );

	/* Set the $meta_key variable based off the post type name. */
	$meta_key = "_wp_{$post->post_type}_template";

	/* Get the meta value of the meta key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If there is no new meta value but an old value exists, delete it. */
	if ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );
}

?>