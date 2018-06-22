<?php

namespace AC\ListScreens;

use AC\ListScreens\Store\DB;
use AC\ListScreens\Store\PHP;

class StoreFactory {

	/**
	 * @param string $type
	 * @param string $list_screen_type
	 *
	 * @return Store
	 */
	public static function create( $type, $list_screen ) {
		switch ( $type ) {

			case 'php' :
				return new PHP( $list_screen );

			default :
				return new DB( $list_screen );

		}
	}

}