<?php

namespace AC\Asset\Location;

use AC\Asset\Location;

class Suffix
	implements Location {

	/**
	 * @var string
	 */
	private $location;

	/**
	 * @var string
	 */
	private $suffix;

	/**
	 * @param string   $suffix
	 * @param Location $location
	 */
	public function __construct( $suffix, Location $location ) {
		$this->suffix = $suffix;
		$this->location = $location;
	}

	/**
	 * @param string $location
	 */
	protected function build( $location ) {
		return trailingslashit( $location ) . ltrim( $this->suffix, '/' );
	}

	/**
	 * @return string
	 */
	public function get_url() {
		$url = $this->location->get_url();

		return $this->build( $url );
	}

	/**
	 * @return string
	 */
	public function get_path() {
		$path = $this->location->get_path();

		return $this->build( $path );
	}

}