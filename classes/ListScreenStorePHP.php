<?php

namespace AC;

class ListScreenStorePHP extends ListScreenStore {

	public function get_store_type() {
		return 'php';
	}

	public function read() {
		$data = array();

		foreach ( AC()->api()->get_data_per_type( $this->get_type() ) as $list_screen ) {
			if ( $list_screen['id'] == $this->get_id() ) {
				$data = $list_screen;
				break;
			}
		}

		return $data;
	}

	public function update( $data ) {
		return true;
	}

	public function delete() {
		return true;
	}

}