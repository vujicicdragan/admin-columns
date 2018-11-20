<?php

namespace AC\Column\Meta;

use AC\Column;

class Numeric extends Column\Meta {

	public function __construct( $meta_type, $settings = null ) {
		parent::__construct( 'Numeric', 'meta-numeric', $meta_type, $settings );
	}

}