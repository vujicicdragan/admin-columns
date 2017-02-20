<?php

/**
 * Used to register, display and handle a setting on the settings page
 *
 */
abstract class AC_Admin_Settings {

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $description;

	public function __construct() {
		add_action( 'admin_init', array( $this, 'request' ) );
	}

	/**
	 * Render this settings group
	 *
	 */
	public function display() {
		?>

		<tr class="<?php echo $this->get_id(); ?>">
			<th scope="row">
				<h2><?php echo esc_html( $this->get_label() ); ?></h2>

				<?php if ( $this->get_description() ) : ?>
					<p><?php echo $this->get_description(); ?></p>
				<?php endif; ?>
			</th>
			<td class="padding-22">
				<?php $this->display_fields(); ?>
			</td>
		</tr>

		<?php
	}

	/**
	 * Display the
	 *
	 * @return AC_View
	 */
	protected abstract function display_fields();

	/**
	 * Handle request for this setting
	 *
	 * @return void
	 */
	public abstract function request();

	/**
	 * Validate request
	 *
	 * @return bool
	 */
	protected function validate_request() {
		return $this->verify_nonce() && $this->verify_action() && AC()->user_can_manage_admin_columns();
	}

	/**
	 * Verify nonce field based on the id
	 *
	 * @return bool
	 */
	protected function verify_nonce() {
		return wp_verify_nonce( filter_input( INPUT_POST, '_ac_nonce' ), $this->id );
	}

	/**
	 * Display nonce field based on the id
	 *
	 */
	protected function nonce_field() {
		wp_nonce_field( $this->id, '_ac_nonce', false );
	}

	/**
	 * Verify action field based on the id
	 *
	 * @return bool
	 */
	protected function verify_action() {
		return $this->id !== filter_input( INPUT_POST, 'ac_action' );
	}

	/**
	 * Set an action field based on the id
	 *
	 * Nonce Field
	 */
	protected function action_field() {
		?>

		<input type="hidden" name="ac_action" value="<?php echo $this->id; ?>">

		<?php
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	protected function set_id( $id ) {
		$this->id = sanitize_key( $id );

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	protected function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param string $description
	 *
	 * @return $this
	 */
	protected function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

}