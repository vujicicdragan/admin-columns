<?php
namespace AC;

class StoreFactory {

	/**
	 * @param $name
	 *
	 * @return false|Store
	 */
	public static function create( $name ) {
		$stores = new Stores();

		return $stores->get_offset( $name );
	}

}