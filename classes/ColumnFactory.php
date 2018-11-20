<?php
namespace AC;

use Exception;

class ColumnFactory {

	/**
	 * @var ColumnTypes[]
	 */
	private $registrar;

	public function __construct( ColumnTypes $column_types ) {
		$this->registrar = $column_types;
	}

	/**
	 * @param string     $list_type
	 * @param string     $type
	 * @param DataObject $settings
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function create( $list_type, $type, DataObject $settings ) {
		$column_type = $this->get_column_type( $type, $list_type );

		if ( false === $column_type ) {
			throw new Exception( 'Column not found.' );
		}

		$class_name = get_class( $column_type );

		switch ( true ) {

			case $column_type instanceof Column\Meta :
				return new $class_name( $column_type->get_meta_type(), $settings );

			default :
				return new $class_name( $settings );
		}
	}

	/**
	 * @param string $type
	 * @param string $list_type
	 *
	 * @return Column|false
	 */
	private function get_column_type( $type, $list_type ) {
		foreach ( $this->registrar->get_columns( $list_type ) as $column ) {
			if ( $column->get_type() === $type ) {
				return $column;
			}
		}

		return false;
	}

}