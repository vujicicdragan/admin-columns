<?php

namespace AC\Store;

use AC\DataObject;
use AC\Store;
use AC\Writable;

class DB implements Writable, Store {

	public function query( array $args ) {
		// TODO: Implement query() method.
	}

	public function create( DataObject $settings ) {
		// TODO: Implement create() method.
	}

	public function read( $id ) {
		// TODO: Implement read() method.
	}

	public function update( $id, DataObject $settings ) {
		// TODO: Implement update() method.
	}

	public function delete( $id ) {
		// TODO: Implement delete() method.
	}

}