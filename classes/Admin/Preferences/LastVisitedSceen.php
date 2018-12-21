<?php

namespace AC\Admin\Preferences;

use AC\Preferences;
use AC\Preferences\Site;

class LastVisitedSceen {

	/** @var Preferences */
	private $preferences;

	public function __construct() {
		$this->preferences = new Site( 'last-visited-settings' );
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->preferences->get( 'list_screen_type' );
	}

	/**
	 * @param string $key
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		$this->preferences->set( 'list_screen_type', $type );

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->preferences->get( 'list_screen_id' );
	}

	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function set_id( $id ) {
		$this->preferences->set( 'list_screen_id', $id );

		return $this;
	}

}