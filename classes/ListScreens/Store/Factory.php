<?php

namespace AC\ListScreens\Store;

use AC\ListScreens\Store;

class Factory {

	/**
	 * @param string $type
	 * @param string $list_screen_type
	 *
	 * @return Store
	 */
	public static function create( $type, $list_screen_type, $list_screen_id ) {
		switch ( $type ) {

			case 'php' :
				return new PHP( $list_screen_type, $list_screen_id );

			default :
				return new DB( $list_screen_type, $list_screen_id );

		}
	}

}