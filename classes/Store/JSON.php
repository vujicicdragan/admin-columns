<?php
namespace AC\Store;

use AC\DataObject;
use AC\Store;
use AC\Writable;

class JSON implements Writable, Store {

	/** @var string */
	private $file;

	public function __construct( $file ) {
		$this->file = $file;
	}

	/**
	 * @param DataObject $settings
	 *
	 * @return false|int
	 */
	public function create( DataObject $settings ) {
		$data = $this->data();

		$data[] = $settings->getArrayCopy();

		return $this->write( $data );
	}

	/**
	 * @param int $id
	 *
	 * @return DataObject
	 */
	public function read( $id ) {
		foreach ( $this->data() as $_id => $item ) {
			if ( $_id === $id ) {
				return new DataObject( $item );
			}
		}

		return new DataObject();
	}

	/**
	 * @param int        $id
	 * @param DataObject $settings
	 *
	 * @return false|int
	 */
	public function update( $id, DataObject $settings ) {
		$data = $this->data();

		if ( isset( $data[ $id ] ) ) {
			$data[ $id ] = $settings->getArrayCopy();
		} else {
			$this->create( $settings );
		}

		return $this->write( $data );
	}

	/**
	 * @param int $id
	 *
	 * @return false|int
	 */
	public function delete( $id ) {
		$data = $this->data();

		unset( $data[ $id ] );

		return $this->write( $data );
	}

	/**
	 * @param array $args
	 *
	 * @return DataObject[]
	 */
	public function query( array $args = array() ) {
		$data = $this->data();

		if ( isset( $args['type'] ) ) {
			$data = $this->filter_data_by( $data, 'type', $args['type'] );
		}

		if ( isset( $args['subtype'] ) ) {
			$data = $this->filter_data_by( $data, 'subtype', $args['subtype'] );
		}

		return array_map( array( $this, 'create_data_object' ), $data );
	}

	/**
	 * @param array  $data
	 * @param string $field
	 * @param string $value
	 *
	 * @return array
	 */
	private function filter_data_by( $data, $field, $value ) {
		$filtered = array();
		foreach ( $data as $id => $item ) {
			if ( $item[ $field ] === $value ) {
				$filtered[] = $item;
			}
		}

		return $filtered;
	}

	/**
	 * @param array $array
	 *
	 * @return DataObject
	 */
	public function create_data_object( array $array ) {
		return new DataObject( $array );
	}

	/**
	 * @param array $array
	 *
	 * @return false|int
	 */
	private function write( array $array ) {
		return file_put_contents( $this->file, json_encode( $array, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @return array
	 */
	private function data() {
		if ( ! file_exists( $this->file ) ) {
			return array();
		}

		return json_decode( file_get_contents( $this->file ), true );
	}

}