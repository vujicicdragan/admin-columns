<?php
namespace AC;

interface Store {

	/**
	 * Query data by 'type' and/or 'subfield'
	 * @return DataObject[]
	 */
	public function query( array $args );

	/**
	 * @param int $id
	 *
	 * @return DataObject
	 */
	public function read( $id );

}