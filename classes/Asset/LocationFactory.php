<?php

namespace AC\Asset;

final class LocationFactory {

	/**
	 * @var Location
	 */
	static $locations = array();

	/**
	 * @param string   $key
	 * @param Location $location
	 */
	public static function register_location( $key, Location $location ) {
		self::$locations[ $key ] = $location;
	}

	/**
	 * @param string $key
	 *
	 * @return Location|false
	 */
	public static function get_location( $key ) {
		if ( ! isset( self::$locations[ $key ] ) ) {
			throw new \LogicException( sprintf( 'Location `%s` is not registered.', $key ) );
		}

		return self::$locations[ $key ];
	}

}