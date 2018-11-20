<?php
namespace AC\Column;

use AC\Column;

abstract class Meta extends Column {

	public function __construct( $label, $type, $meta_type, $settings = null ) {
		parent::__construct( $label, $type, $meta_type, 'meta', $settings );
	}

	public function get_value( $id ) {

		// todo
		return get_metadata( $this->get_meta_type(), $id, $this->get_settings()->meta_key, true );
	}

}