<?php
namespace AC;

abstract class Column implements ColumnType {

	/** @var string */
	private $label;

	/** @var string */
	private $group;

	/** @var string */
	private $type;

	/** @var string */
	private $meta_type;

	/** @var DataObject */
	private $settings;

	public function __construct( $label, $type, $meta_type, $group = 'custom', $settings = null ) {
		if ( null === $settings ) {
			$settings = new DataObject;
		}

		$this->label = $label;
		$this->type = $type;
		$this->group = $group;
		$this->settings = $settings;
		$this->meta_type = $meta_type;
	}

	abstract public function get_value( $id );

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function get_group() {
		return $this->group;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function get_meta_type() {
		return $this->meta_type;
	}

	/**
	 * @return DataObject
	 */
	public function get_settings() {
		return $this->settings;
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