<?php

namespace AC;

use Exception;
use WP_Screen;

/**
 * List Screen
 * @since 2.0
 */
abstract class ListScreen {

	/** @var string */
	private $type;

	/** @var string */
	private $group;

	/** @var string */
	private $label;

	/** @var string e.g. post_type or taxonomy */
	private $subtype = false;

	/** @var DataObject */
	private $settings;

	public function __construct( $type, $group, $label, $settings = null, $subtype = false ) {
		if ( null === $settings ) {
			$settings = new DataObject;
		}

		$this->type = $type;
		$this->group = $group;
		$this->label = $label;
		$this->subtype = $subtype;
		$this->settings = $settings;
	}

	/**
	 * @return void
	 */
	abstract public function set_value_callback();

	/** @var string */
	abstract public function get_url();

	/**
	 * @param WP_Screen $wp_screen
	 *
	 * @return bool
	 */
	abstract public function is_valid( WP_Screen $wp_screen );

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
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
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function get_subtype() {
		return $this->subtype;
	}

	/**
	 * @return DataObject()
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * @return Column[]
	 * @throws Exception
	 */
	public function get_columns() {
		if ( ! $this->settings->offsetExists( 'columns' ) ) {
			return array();
		}

		$columns = array();

		foreach ( $this->settings->columns as $data ) {
			$factory = new ColumnFactory( ColumnTypes::instance() );

			if ( ! isset( $data['type'] ) ) {
				throw new Exception( 'Missing column data type.' );
			}

			if ( ! isset( $data['settings'] ) ) {
				throw new Exception( 'Missing column settings.' );
			}

			try {
				$column = $factory->create( $this->type, $data['type'], new DataObject( $data['settings'] ) );
			} catch ( Exception $e ) {
				continue;
			}

			$columns[] = $column;
		}

		return $columns;
	}

}