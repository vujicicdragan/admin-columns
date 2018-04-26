<?php

namespace AC;

interface ListScreenLoader {

	/**
	 * ListScreen data. Contains settings for columns and general
	 * listscreen properties, e.g. users, roles and name.
	 *
	 * @return array
	 */
	public function read();

	/**
	 * @param array $data
	 *
	 * @return bool
	 */
	public function update( $data );

	/**
	 * @return bool
	 */
	public function delete();

}