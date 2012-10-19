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
 * Multiple select customize control class.
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
