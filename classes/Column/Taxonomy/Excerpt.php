<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Taxonomy_Excerpt extends AC_Column_Pro {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-excerpt' );
		$this->set_label( __( 'Excerpt', 'codepress-admin-columns' ) );
	}

}