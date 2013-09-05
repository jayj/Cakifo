<?php
/**
 * The multiple select customize control extends the WP_Customize_Control class.  This class allows
 * developers to create select fields with the `multiple` attribute within the WordPress theme customizer.
 *
 * @package    Cakifo
 * @subpackage Classes
 * @author     Jesper Johansen <kontakt@jayj.dk>
 * @copyright  Copyright (c) 2012, Jesper Johansen
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Multiple Select customize control class.
 *
 * @since Cakifo 1.5.0
 */
class Cakifo_Customize_Control_Multiple_Select extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since Cakifo 1.5.0
	 */
	public $type = 'multiple-select';

	/**
	 * Displays the multiple select on the customize screen.
	 *
	 * @since Cakifo 1.5.0
	 */
	public function render_content() {

		if ( empty( $this->choices ) )
			return;
	?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<select <?php $this->link(); ?> multiple="multiple" style="height: 100%;">
				<?php
					foreach ( $this->choices as $value => $label ) {
						$selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
						echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
					}
				?>
			</select>
		</label>
	<?php }
}

/**
 * Custom Multiple Select for the headline terms.
 *
 * The terms is grouped in <optgroup> by taxonomy
 * and the select is enhanced by Chosen.js.
 *
 * @since Cakifo 1.6.0
 */
class Cakifo_Customize_Control_Multiple_Select_Headlines extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since Cakifo 1.6.0
	 */
	public $type = 'cakifo-headlines-multiple-select';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since Cakifo 1.6.0
	 */
	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'cakifo-theme-settings-chosen', get_template_directory_uri() . '/functions/admin/chosen.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0' );
		wp_enqueue_style( 'cakifo-theme-settings-chosen', get_template_directory_uri() . '/functions/admin/chosen.css', array(), '1.0' );
	}

	/**
	 * Displays the multiple select on the customize screen.
	 *
	 * @since Cakifo 1.6.0
	 */
	public function render_content() {

		$get_selected_terms = $this->value();
		$exclude_term_ids   = array();

		/* Get all the selected terms IDs in an array. */
		foreach( $get_selected_terms as $term ) :

			// The Customizer stores the terms in a string in the `taxonomy:id` format
			if ( is_string( $term ) && strpos( $term, ':' ) !== false ) {
				$term = explode( ':', $term );
				$exclude_term_ids[] = $term[1];

			// Back-compat when only an ID is used.
			} elseif ( is_string( $term ) || is_int( $term ) ) {
				$exclude_term_ids[] = $term;

			// Normal array
			} else {
				$exclude_term_ids[] = $term[1];
			}

		endforeach;

		$exclude_term_ids = wp_parse_id_list( $exclude_term_ids );
	?>

	<label>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

		<select <?php $this->link(); ?>
				data-placeholder="<?php esc_attr_e( 'Select terms by taxonomy', 'cakifo' ); ?>"
				multiple="multiple"
				class="chosen-sortable<?php if ( is_rtl() ) echo ' chosen-rtl'; ?>">

			<?php
				// First loop through each selected term.
				foreach ( $get_selected_terms as $selected_term ) :

					// Get term and taxonomy information.
					$term = cakifo_get_headline_term( $selected_term );
					$tax  = get_taxonomy( $term->taxonomy );

					// Generate the value containing the taxonomy and term ID.
					$id = $tax->name . ':' . $term->term_id;
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
	</label>

	<?php }
}
