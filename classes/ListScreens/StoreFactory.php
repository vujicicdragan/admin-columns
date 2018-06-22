<?php

namespace AC\ListScreens;

use AC\ListScreens\Store\DB;
use AC\ListScreens\Store\PHP;

class StoreFactory {

	/**
	 * @param string $store_type
	 * @param \AC\ListScreen $list_screen
	 *
	 * @return Store
	 */
	public static function create( $store_type, $list_screen ) {
		switch ( $store_type ) {

			case 'php' :
				return new PHP( $list_screen->get_type(), $list_screen->get_id() );

			default :
				return new DB( $list_screen->get_type(), $list_screen->get_id() );

		}
	}

}