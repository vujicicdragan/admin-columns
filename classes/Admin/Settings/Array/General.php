<?php

class AC_Admin_Settings_Array_General extends AC_Admin_Settings_Array {

	public function __construct() {
		$this->set_id( 'general' );
		$this->set_label( __( 'General Settings', 'codepress-admin-columns' ) );
		$this->set_description( __( 'Customize your Admin Columns settings.', 'codepress-admin-columns' ) );
		$this->set_key( 'cpac_general_options' );

		parent::__construct();
	}

	/**
	 * @return bool
	 */
	public function show_edit_button() {
		return $this->get_option( 'show_edit_button' );
	}

	protected function display_fields() {
		?>

		<form method="post" action="">

			<?php

			$this->single_checkbox_field( array(
				'name'          => 'show_edit_button',
				'label'         => __( 'Show "Edit Columns" button on admin screens. Default is <code>on</code>.', 'codepress-admin-columns' ),
				'default_value' => 1,
			) );

			?>

			<?php do_action( 'ac/settings/general', $this ); ?>

			<p>
				<input type="submit" class="button" value="<?php _e( 'Save' ); ?>"/>
			</p>
		</form>

		<?php
	}

}