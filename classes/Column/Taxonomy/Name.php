<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Taxonomy_Name extends AC_Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'name' );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_Pro_Editing( $this ) );
		$this->add_setting( new AC_Settings_Column_Pro_Export( $this ) );
	}
}