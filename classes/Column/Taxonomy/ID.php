<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Taxonomy_ID extends AC_Column_Pro {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-termid' );
		$this->set_label( __( 'ID', 'codepress-admin-columns' ) );
	}

}