<?php

namespace AC\Column\Taxonomy;

use AC;

/**
 * @since NEWVERSION
 */
class ID extends AC\Column\Pro {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-termid' );
		$this->set_label( __( 'ID', 'codepress-admin-columns' ) );
	}

}