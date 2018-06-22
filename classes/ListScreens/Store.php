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
	 * @param string $id
	 *
	 * @return array|false False when no data can be retrieved
	 */
	abstract public function read( $id );

	/**
	 * @param string $id
	 * @param array  $data
	 *
	 * @return bool
	 */
	abstract public function update( $id, $data );

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	abstract public function delete( $id );

	/**
	 * @return string
	 */
	abstract public function get_store_type();

	/**
	 * @param string $type
	 */
	public function __construct( $type ) {
		$this->type = $type;
	}

}