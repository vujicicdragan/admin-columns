<?php

namespace AC\Column\Taxonomy;

use AC;

/**
 * @since NEWVERSION
 */
class Excerpt extends AC\Column\Pro {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-excerpt' );
		$this->set_label( __( 'Excerpt', 'codepress-admin-columns' ) );
	}

}