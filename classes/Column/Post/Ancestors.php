<?php

/**
 * @since 3.0
 */
class AC_Column_Post_Ancestors extends AC_Column_Pro {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-ancestors' );
		$this->set_label( __( 'Ancestors', 'codepress-admin-columns' ) );
	}

	public function is_valid() {
		return is_post_type_hierarchical( $this->get_post_type() );
	}

}