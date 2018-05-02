<?php

namespace AC;

use AC\ListScreens\Store;

class ListScreenFactory {

	/**
	 * @param string $type
	 * @param int    $id         Optional (layout) ID
	 * @param string $store_type 'db' or 'php'
	 *
	 * @return ListScreen|false
	 */
	public static function create( $type, $id = null, $store_type = 'db' ) {
		$list_screens = AC()->get_list_screens();

		if ( ! isset( $list_screens[ $type ] ) ) {
			return false;
		}

		$list_screen = clone $list_screens[ $type ];

		$list_screen->set_id( $id );

		$store_object = Store\Factory::create( $store_type, $list_screen );

		$list_screen->set_store_object( $store_object )
		            ->load();

		return $list_screen;
	}

}