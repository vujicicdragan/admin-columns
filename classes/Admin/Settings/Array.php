<?php

abstract class AC_Admin_Settings_Array extends AC_Admin_Settings {

	/**
	 * @var array
	 */
	private $options;

	/**
	 * Option key where the fields are stored
	 *
	 * @var string
	 */
	private $key;

	public function __construct() {
		$this->options = (array) get_option( $this->key );

		parent::__construct();
	}

	public function request() {
		if ( ! $this->validate_request() ) {
			return;
		}

		$this->options = filter_input( INPUT_POST, $this->get_key(), FILTER_REQUIRE_ARRAY );

		if ( $this->options ) {
			$this->save();

			AC()->notice( __( 'Settings saved.' ), 'updated' );
		}
	}

	public function single_checkbox_field( array $args = array() ) {
		$args = (object) wp_parse_args( $args, array(
			'name'          => '',
			'label'         => '',
			'instructions'  => '',
			'default_value' => false,
		) );

		$value = $this->get_option( $args->name );

		if ( null === $value ) {
			$value = $args->default_value;
		}

		?>
		<p>
			<label for="<?php echo $args->name; ?>">
				<input name="<?php $this->attr_name( $args->name ); ?>" id="<?php echo $args->name; ?>" type="checkbox" value="1" <?php checked( $value, 1 ); ?>>
				<?php echo $args->label; ?>
			</label>
			<?php if ( $args->instructions ) : ?>
				<a class="cpac-pointer" rel="pointer-<?php echo $args->name; ?>" data-pos="right">
					<?php _e( 'Instructions', 'codepress-admin-columns' ); ?>
				</a>
			<?php endif; ?>
		</p>
		<?php if ( $args->instructions ) : ?>
			<div id="pointer-<?php echo $args->name; ?>" style="display:none;">
				<h3><?php _e( 'Notice', 'codepress-admin-columns' ); ?></h3>
				<?php echo $args->instructions; ?>
			</div>
		<?php endif;
	}

	/**
	 * @param $key
	 *
	 * @return false|string
	 */
	protected function get_option( $key ) {
		return isset( $this->options[ $key ] ) ? $this->options[ $key ] : null;
	}

	/**
	 * @param $key
	 * @param $value
	 *
	 * @return $this
	 */
	protected function set_option( $key, $value ) {
		$this->options[ $key ] = $value;

		return $this;
	}

	/**
	 * @param $key
	 * @param $default
	 */
	protected function set_option_default( $key, $default ) {
		$option = $this->get_option( $key );

		if ( null === $option ) {
			$this->set_option( $key, $default );
			$this->save();
		}

		return $this;
	}

	/**
	 * Save options
	 *
	 * @param array $options
	 *
	 * @return bool
	 */
	protected function save() {
		return update_option( $this->get_key(), $this->options, false );
	}

	/**
	 * Format the name attribute for usage in the Settings API
	 *
	 * @param string $key
	 */
	public function attr_name( $option ) {
		echo esc_attr( $this->get_key() . '[' . sanitize_key( $option ) . ']' );
	}

	/**
	 * @return string
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * @param string $key
	 *
	 * @return $this
	 */
	public function set_key( $key ) {
		$this->key = sanitize_key( $key );

		return $this;
	}

}