<?php

namespace AC\Column\Meta;

use AC\Column;

class Text extends Column\Meta {

	public function __construct( $meta_type, $settings = null ) {
		parent::__construct( 'Text', 'meta-text', $meta_type, $settings );
	}

}