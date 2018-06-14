<?php

namespace AC\Form\Element;

class MultiSelect extends Select {

	public function __construct( $name, array $options = array() ) {
		parent::__construct( $name, $options );

		$this->set_name( $name . '[]' );
		$this->set_attribute( 'multiple', 'multiple' );
	}

	protected function selected( $key ) {
		return in_array( $key, (array) $this->get_value() );
	}

}