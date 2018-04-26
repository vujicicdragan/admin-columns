<?php

namespace AC;

/**
 * @since NEWVERSION
 */
class ListScreenRepoPHP extends ListScreenRepo {

	public function get_ids() {
		$ids = array();

		foreach ( AC()->api()->get_data_per_type( $this->get_type() ) as $list_screen ) {
			$ids[] = $list_screen['id'];
		}

		return $ids;
	}

}