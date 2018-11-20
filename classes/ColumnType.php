<?php
namespace AC;

interface ColumnType {

	/**
	 * @return string
	 */
	public function get_label();

	/**
	 * @return string
	 */
	public function get_type();

	/**
	 * @param string $list_subtype
	 *
	 * @return bool
	 */
	public function is_valid( $list_subtype );

}