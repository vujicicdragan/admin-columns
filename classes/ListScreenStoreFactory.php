<?php

namespace AC;

class ListScreenStoreFactory {

	/**
	 * @param string $type
	 * @param string $list_screen_type
	 *
	 * @return ListScreenStore
	 */
	public static function create( $type, $list_screen_type, $list_screen_id ) {
		switch ( $type ) {

			case 'php' :
				return new ListScreenStorePHP( $list_screen_type, $list_screen_id );

			default :
				return new ListScreenStoreDB( $list_screen_type, $list_screen_id );

		}
	}

}