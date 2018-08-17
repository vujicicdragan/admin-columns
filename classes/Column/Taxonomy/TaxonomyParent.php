<?php

namespace AC\Column\Taxonomy;

use AC;

/**
 * @since NEWVERSION
 */
class TaxonomyParent extends AC\Column\Pro {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-term_parent' );
		$this->set_label( __( 'Parent', 'codepress-admin-columns' ) );
	}

}