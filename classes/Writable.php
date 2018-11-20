<?php
namespace AC;

interface Writable {

	/**
	 * @param DataObject $settings
	 *
	 * @return mixed
	 */
	public function create( DataObject $settings );

	/**
	 * @param int        $id
	 * @param DataObject $settings
	 *
	 * @return bool
	 */
	public function update( $id, DataObject $settings );

	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	public function delete( $id );

}