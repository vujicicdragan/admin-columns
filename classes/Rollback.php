<?php

class AC_Rollback {

	/**
	 * @since NEWVERSION
	 */
	public function __construct() {
		// Settings screen
		add_action( 'ac/admin_scripts/columns', array( $this, 'settings_scripts' ) );
		add_action( 'ac/column/settings', array( $this, 'settings' ) );

		add_action( 'ac/settings/general', array( $this, 'add_settings' ) );
		add_filter( 'ac/settings/groups', array( $this, 'settings_group' ), 99 );
		add_action( 'ac/settings/group/rollback', array( $this, 'settings_display' ) );

		add_action( 'admin_init', array( $this, 'handle_settings_request' ) );
	}

	/**
	 * Returns the version of this addon
	 *
	 * @since NEWVERSION
	 * @return string Version
	 */
	public function get_version() {
		return ACP()->get_version();
	}

	/**
	 * Returns the url of this addon
	 *
	 * @since NEWVERSION
	 * @return string
	 */
	public function get_url() {
		return plugin_dir_url( __FILE__ );
	}

	/**
	 * @since NEWVERSION
	 */
	public function settings_scripts() {
		//wp_enqueue_style( 'cac-addon-sorting-columns-css', plugin_dir_url( __FILE__ ) . 'assets/css/sorting.css', array(), $this->get_version(), 'all' );
	}

	/**
	 * @param AC_Admin_Page_Settings $settings
	 */
	public function add_settings( $settings ) {
		$settings->single_checkbox_field( array(
			'name'  => 'show_all_results',
			'label' => __( "Show all results when sorting. Default is <code>off</code>.", 'codepress-admin-columns' ),
		) );
	}

	/**
	 * Callback for the settings page to add settings for sorting
	 *
	 */
	public function settings_group( $groups ) {
		if ( isset( $groups['rollback'] ) ) {
			return $groups;
		}

		$groups['rollback'] = array(
			'title'       => __( 'Rollback', 'codepress-admin-columns' ),
			'description' => __( 'Go to back to version 3', 'codepress-admin-columns' ),
		);

		return $groups;
	}

	/**
	 * Display rollback form
	 *
	 */
	public function display_settings() {
		?>
		<form action="" method="post">
			<?php wp_nonce_field( 'rollback-to-version-3' ); ?>

			<input type="hidden" name="rollback-to-version-3" value="1">
			<input type="submit" class="button" value="<?php _e( 'Rollback to version 3', 'codepress-admin-columns' ); ?>">
		</form>
		<?php
	}

	/**
	 * Handle rollback action
	 *
	 */
	public function handle_settings_request() {

		if ( isset( $_POST['rollback-to-version-3'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'rollback-to-version-3' ) && AC()->user_can_manage_admin_columns() ) {

		}
	}

}