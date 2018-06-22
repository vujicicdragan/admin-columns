<?php

namespace AC\ListScreens\Store;

use AC\ListScreens\Store;

class PHP extends Store {

	public function get_store_type() {
		return 'php';
	}

	public function read( $id ) {
		$raw_data = AC()->api()->get_data_per_type( $this->type );

		if ( ! $raw_data ) {
			return false;
		}

		$data = array();

		foreach ( $raw_data as $list_screen ) {
			if ( $list_screen['id'] == $id ) {
				$data = $list_screen;
				break;
			}
		}

		return $data;
	}

	public function update( $id, $data ) {
		return true;
	}

	public function delete( $id ) {
		return true;
	}

}