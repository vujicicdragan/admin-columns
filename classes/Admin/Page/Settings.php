<?php

class AC_Admin_Page_Settings extends AC_Admin_Page {

	/**
	 * @var AC_Admin_Settings[]
	 */
	private $settings;

	public function __construct() {
		$this
			->set_slug( 'settings' )
			->set_label( __( 'Settings', 'codepress-admin-columns' ) );

		$this->register_settings( new AC_Admin_Settings_General() );

		// TODO: maybe target this class active instead of using an action?
		do_action( 'ac/admin/register_settings', $this );
	}

	/**
	 * Register a settings section
	 *
	 * @param AC_Admin_Settings $settings
	 *
	 * @return $this
	 */
	public function register_settings( AC_Admin_Settings $settings ) {
		$this->settings[] = $settings;

		return $this;
	}

	// TODO: ADD	//ac/settings/group/ //ac/settings/groups
	public function display() { ?>

		<table class="form-table cpac-form-table settings">
			<tbody>

			<?php

			foreach ( $this->settings as $setting ) {
				$setting->display();
			}

			?>

			</tbody>
		</table>

		<?php
	}

}