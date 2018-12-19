<?php

namespace AC\Admin\Preferences;

use AC\Preferences;
use AC\Preferences\Site;

class LastVisitedSceen {

	/** @var Preferences */
	private $preferences;

	public function __construct() {
		$this->preferences = new Site( 'settings' );
	}

	/**
	 * @return string
	 */
	public function get_list_screen() {
		return $this->preferences->get( 'list_screen' );
	}

	/**
	 * @param string $key
	 */
	public function set_list_screen( $key ) {
		$this->preferences->set( 'list_screen', $key );
	}

}