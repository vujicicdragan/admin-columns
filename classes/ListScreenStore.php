<?php

namespace AC;

abstract class ListScreenStore {

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