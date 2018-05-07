<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Taxonomy_Parent extends AC_Column_Pro {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-term_parent' );
		$this->set_label( __( 'Parent', 'codepress-admin-columns' ) );
	}

}