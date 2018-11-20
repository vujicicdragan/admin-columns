<?php
namespace AC\Column;

use AC\Column;
use AC\ColumnType;

class Composite implements ColumnType {

	/** @var string */
	private $label;

	/** @var string */
	private $type;

	/** @var Column[] */
	private $columns;

	public function __construct( $label, $type ) {
		$this->label = $label;
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param Column $column
	 *
	 * @return Composite
	 */
	public function add_column( Column $column ) {
		$this->columns[] = $column;

		return $this;
	}

	/**
	 * @return Column[]
	 */
	public function get_columns() {
		return $this->columns;
	}

	/**
	 * @param string $list_subtype
	 *
	 * @return bool
	 */
	public function is_valid( $list_subtype ) {
		return true;
	}

}