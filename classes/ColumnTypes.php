<?php
namespace AC;

use AC\Column\Composite;

class ColumnTypes {

	/** @var ColumnTypes */
	private static $instance = null;

	/** @var array */
	private $columns;

	/** @var array */
	private $types;

	/**
	 * @return ColumnTypes
	 */
	static public function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param string    $list_type
	 * @param Composite $composite
	 *
	 * @return ColumnTypes
	 */
	public function register_composite( $list_type, Composite $composite ) {
		foreach ( $composite->get_columns() as $column ) {
			$this->add_column( $list_type, $column );
		}

		$this->add_type( $list_type, $composite );

		return $this;
	}

	/**
	 * @param string $list_type
	 * @param Column $column
	 *
	 * @return $this
	 */
	public function register_column( $list_type, Column $column ) {
		$this->add_column( $list_type, $column );
		$this->add_type( $list_type, $column );

		return $this;
	}

	/**
	 * @param string $list_type
	 * @param Column $column
	 */
	private function add_column( $list_type, Column $column ) {
		$this->columns[ $list_type ][] = $column;
	}

	/**
	 * @param string     $list_type
	 * @param ColumnType $column
	 */
	private function add_type( $list_type, ColumnType $column ) {
		$this->types[ $list_type ][] = $column;
	}

	/**
	 * @param string $list_type
	 *
	 * @return Column[]
	 */
	public function get_columns( $list_type ) {
		if ( ! array_key_exists( $list_type, $this->columns ) ) {
			return array();
		}

		return $this->columns[ $list_type ];
	}

	/**
	 * @param string $list_type
	 *
	 * @return ColumnType[]
	 */
	public function get_registered_types( $list_type ) {
		if ( ! array_key_exists( $list_type, $this->types ) ) {
			return array();
		}

		return $this->types[ $list_type ];
	}

}