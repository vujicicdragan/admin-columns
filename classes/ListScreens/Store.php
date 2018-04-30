<?php

namespace AC\ListScreens;

use AC\ListScreen;

abstract class Store {

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * ListScreen data. Contains settings for columns and general
	 * listscreen properties, e.g. users, roles and name.
	 *
	 * @return array
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

	/**
	 * @param ListScreen $list_screen
	 */
	public function __construct( $type, $id ) {
		$this->type = $type;
		$this->id = $id;
	}

	protected function get_type() {
		return $this->type;
	}

	protected function get_id() {
		return $this->id;
	}

}