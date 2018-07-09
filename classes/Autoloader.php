<?php

namespace AC;

/**
 * PSR-4 autoloader
 */
final class Autoloader {

	/**
	 * @var array
	 */
	private $class_map = array();

	/**
	 * Register prefixes and their path
	 *
	 * @var string[]
	 */
	private $prefixes = array();

	/**
	 * Register the SPL autoload
	 */
	public function register() {
		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * @return string[]
	 */
	public function get_prefixes() {
		return $this->prefixes;
	}

	/**
	 * @param array $prefixes
	 *
	 * @return $this
	 */
	public function set_prefixes( array $prefixes ) {
		foreach ( $prefixes as $prefix => $path ) {
			$this->add_prefix( $prefix, $path );
		}

		return $this;
	}

	/**
	 * Register a prefix that should autoload
	 *
	 * @param string $prefix
	 * @param string $path
	 *
	 * @return $this
	 */
	public function add_prefix( $prefix, $path ) {
		$prefix = rtrim( $prefix, '\\' ) . '\\';
		$path = rtrim( $path . '/' ) . '/';

		$this->prefixes[ $prefix ] = $path;

		// make sure that more specific prefixes are checked first
		krsort( $this->prefixes );

		return $this;
	}

	/**
	 * @param array $class_map
	 *
	 * @return $this
	 */
	public function set_class_map( array $class_map ) {
		$this->class_map = $class_map;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_class_map() {
		return $this->class_map;
	}

	/**
	 * @param string $class
	 *
	 * @return bool
	 */
	public function autoload( $class ) {
		if ( isset( $this->class_map[ $class ] ) ) {
			return ac_include_file( $this->class_map[ $class ] );
		}

		$prefix = $this->get_prefix( $class );

		if ( ! $prefix ) {
			return false;
		}

		$file = $this->prefixes[ $prefix ] . substr( $class, strlen( $prefix ) );
		$file = str_replace( '\\', '/', $file );
		$file = realpath( $file . '.php' );

		if ( ! $file ) {
			return false;
		}

		return ac_include_file( $file );
	}

	/**
	 * @param string $class
	 *
	 * @return false|string
	 */
	private function get_prefix( $namespace ) {
		foreach ( $this->prefixes as $prefix => $path ) {
			if ( 0 === strpos( $namespace, $prefix ) ) {
				return $prefix;
			}
		}

		return false;
	}

}

/**
 * Scoped include file to prevent access to autoloader itself
 *
 * @param string $file
 */
function ac_include_file( $file ) {
	include $file;

	return true;
}