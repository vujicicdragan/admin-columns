<?php

namespace AC\ListScreens;

use AC\ListScreen;

abstract class Store {

	/**
	 * @var ListScreen
	 */
	protected $list_screen;

	/**
	 * ListScreen data. Contains settings for columns and general
	 * listscreen properties, e.g. users, roles and name.
	 *
	 * @return array|false False when no data can be retrieved
	 */
	abstract public function read();

	/**
	 * @param array $data
	 *
	 * @return bool
	 */
	abstract public function update( $data );

	/**
	 * @return bool
	 */
	abstract public function delete();

	/**
	 * @return string
	 */
	abstract public function get_store_type();

	public function __construct( ListScreen $list_screen ) {
		$this->list_screen = $list_screen;
	}

}