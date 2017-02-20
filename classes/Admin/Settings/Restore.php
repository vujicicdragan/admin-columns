<?php

class AC_Admin_Settings_Restore extends AC_Admin_Settings {

	public function __construct() {
		$this->set_id( 'restore-all' );
		$this->set_label( __( 'Restore Settings', 'codepress-admin-columns' ) );
		$this->set_description( __( 'This will delete all column settings and restore the default settings.', 'codepress-admin-columns' ) );

		parent::__construct();
	}

	protected function display_fields() {
		?>

		<form method="post">
			<?php

			$this->nonce_field();
			$this->action_field();

			?>

			<input type="submit" class="button" value="<?php echo esc_attr( __( 'Restore default settings', 'codepress-admin-columns' ) ); ?>" onclick="return confirm('<?php echo esc_js( __( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ) ); ?>');">
		</form>

		<?php
	}

	public function request() {
		global $wpdb;

		if ( ! $this->validate_request() ) {
			return;
		}

		$sql = "
			DELETE
			FROM $wpdb->options
			WHERE option_name LIKE %s
		";

		// TODO: Add notice if query fails?
		$wpdb->query( $wpdb->prepare( $sql, AC_ListScreen::OPTIONS_KEY . '%' ) );

		// @since NEWVERSION
		do_action( 'ac/restore_all_columns' );

		AC()->notice( __( 'Default settings successfully restored.', 'codepress-admin-columns' ), 'updated' );
	}

}