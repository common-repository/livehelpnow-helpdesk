<?php
/**
 * Plugin settings class file
 *
 * @package LHNChat
 */

namespace LHNChat;

/**
 * Plugin settings
 */
class SettingsField {

	/**
	 * Option object (from LHN API)
	 *
	 * @var stdObj
	 */
	protected $option;

	/**
	 * Option name
	 *
	 * @var string
	 */
	protected $option_name;

	/**
	 * Class constructor
	 *
	 * @param stdObj $option option object (from LHN API).
	 * @param string $type   option type: option / dictionary.
	 */
	public function __construct( $option, $type = 'option' ) {

		$this->option = $option;

		if ( $this->option->group_name ) {
			$this->option_name = "chat_${type}[" . esc_html( $this->option->group_name ) . '][' . esc_html( $this->option->key ) . ']';
		} else {
			$this->option_name = "chat_${type}[" . esc_html( $this->option->key ) . ']';
		}

	}

	/**
	 * Get field label code
	 *
	 * @return string label html code.
	 */
	public function field_label() {

		return sprintf(
			'<label class="post-attributes-label" for="%1$s">%2$s</label>',
			esc_attr( $this->option_name ),
			esc_html( $this->option->description )
		);

	}

	/**
	 * Get hidden field code.
	 *
	 * @return string field html code.
	 */
	public function field_hidden_key() {

		return sprintf(
			'<input type="hidden" name="%1$s" value="%2$s" />',
			esc_attr( "key_$this->option_name" ),
			esc_html( $this->option->key )
		);

	}

	/**
	 * Get integer type field code.
	 *
	 * @param  array $widgets_meta widget post meta.
	 * @return string              field html code.
	 */
	public function integer_field( $widgets_meta ) {

		return sprintf(
			'%1$s <input class="chat-option-field" type="number" name="%2$s" id="%2$s" value="%3$s" />',
			$this->field_label() . $this->field_hidden_key(),
			esc_attr( $this->option_name ),
			$this->get_field_value( $widgets_meta )
		);

	}

	/**
	 * Get boolean type field code.
	 *
	 * @param  array $widgets_meta widget post meta.
	 * @return string              field html code.
	 */
	public function boolean_field( $widgets_meta ) {

		return sprintf(
			'%1$s <select name="%2$s" id="%2$s">%3$s</select>',
			$this->field_label() . $this->field_hidden_key(),
			esc_attr( $this->option_name ),
			$this->get_select_options([
				[
					'value' => true,
					'label' => esc_html__( 'Yes', 'lhnchat' ),
				],
				[
					'value' => false,
					'label' => esc_html__( 'No', 'lhnchat' ),
				],
			], $this->get_field_value( $widgets_meta ) )
		);

	}

	/**
	 * Get select field options.
	 *
	 * @param  array $options select options.
	 * @param  array $value   selected value.
	 * @return string         list html code.
	 */
	public function get_select_options( $options, $value ) {

		$_options = '';

		foreach ( $options as $option ) {
			$_options .= sprintf(
				'<option value="true" %s>%s</option>',
				selected( $value, $option['value'], false ),
				$option['label']
			);
		}

		return $_options;

	}

	/**
	 * Get string type field code.
	 *
	 * @param  array $widgets_meta widget post meta.
	 * @return string              field html code.
	 */
	public function string_field( $widgets_meta ) {

		return sprintf(
			'%1$s <textarea class="chat-option-field" type="number" name="%2$s" id="%2$s">%3$s</textarea>',
			$this->field_label() . $this->field_hidden_key(),
			esc_attr( $this->option_name ),
			$this->get_field_value( $widgets_meta )
		);

	}

	/**
	 * Check if group or field and get proper value
	 *
	 * @param  array $widgets_meta meta array.
	 * @return mixed               field value.
	 */
	protected function get_field_value( $widgets_meta ) {

		if ( $this->option->group_name ) {
			return isset( $widgets_meta[ $this->option->group_name ][ $this->option->key ] ) ? esc_attr( $widgets_meta[ $this->option->group_name ][ $this->option->key ] ) : '';
		}

		return isset( $widgets_meta[ $this->option->key ] ) ? esc_attr( $widgets_meta[ $this->option->key ] ) : '';

	}

	/**
	 * Get group type field code.
	 *
	 * @param  array $widgets_meta widget post meta.
	 * @return string              field html code.
	 */
	public function group_field( $widgets_meta ) {

		$group = '';

		$group .= sprintf(
			'<h4 class="group-header">%1$s</h4><hr>',
			esc_html( $this->option->description )
		);

		foreach ( $this->option->fields as $option ) {
			$option->group_name = $this->option->key;
			$group .= sprintf(
				'<div class="post-attributes-label-wrapper">%s</div>',
				call_user_func_array(
					[
						new $this( $option ),
						$option->type . '_field',
					],
					[
						$widgets_meta,
					]
				)
			);
		}

		return $group . '<hr style="margin-top: 15px;">';

	}

}
