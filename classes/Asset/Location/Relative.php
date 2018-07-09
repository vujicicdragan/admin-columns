<?php

namespace AC\Asset\Location;

use AC\Asset\Location;

class Relative
	implements Location {

	/**
	 * @var string
	 */
	private $location;

	/**
	 * @param string $location
	 */
	public function __construct( $location ) {
		$this->location = $location;
	}

	/**
	 * @return string
	 */
	protected function get_dirname() {
		return dirname( __FILE__, 6 );
	}

	/**
	 * @param $location
	 *
	 * @return string
	 */
	protected function build( $location ) {
		return trailingslashit( $location ) . ltrim( $this->location, '/' );
	}

	/**
	 * @return string
	 */
	public function get_url() {
		$url = plugins_url( null, $this->get_dirname() );

		return $this->build( $url );
	}

	/**
	 * @return string
	 */
	public function get_path() {
		return $this->build( $this->get_dirname() );
	}

}