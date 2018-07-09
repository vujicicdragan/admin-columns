<?php

namespace AC;

final class PluginFactory {

	/**
	 * @var Plugin
	 */
	static $plugins = array();

	/**
	 * @param string $name
	 * @param Plugin $plugin
	 */
	public static function register_plugin( $name, Plugin $plugin ) {
		self::$plugins[ $name ] = $plugin;
	}

	/**
	 * @param string|null $name
	 *
	 * @return Plugin|false
	 */
	public function get_plugin( $name = null ) {
		if ( $name === null ) {
			$name = 'ac';
		}

		if ( ! isset( self::$plugins[ $name ] ) ) {
			throw new \LogicException( sprintf( 'Plugin `%s` is not registered.', $name ) );
		}

		return self::$plugins[ $name ];
	}

}