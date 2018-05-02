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
	public static function create( $type, $list_screen ) {
		switch ( $type ) {

			case 'php' :
				return new PHP( $list_screen );

			default :
				return new DB( $list_screen );

		}
	}

}