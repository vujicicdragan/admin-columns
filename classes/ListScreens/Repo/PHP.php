<?php

namespace AC\ListScreens\Repo;

use AC\ListScreens\Repo;

/**
 * @since NEWVERSION
 */
class PHP extends Repo {

	public function get_ids() {
		$ids = array();

		foreach ( AC()->api()->get_data_per_type( $this->get_type() ) as $list_screen ) {
			$ids[] = $list_screen['id'];
		}

		return $ids;
	}

}