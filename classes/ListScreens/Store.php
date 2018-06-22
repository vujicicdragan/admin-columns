<?php

namespace AC\ListScreens;

abstract class Store {

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $id;

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

	/**
	 * @param string $type
	 * @param string $id
	 */
	public function __construct( $type, $id ) {
		$this->type = $type;
		$this->id = $id;
	}

}