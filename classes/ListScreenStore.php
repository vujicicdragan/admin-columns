<?php

namespace AC;

class ListScreenStore implements ListScreenLoader {

	const SETTINGS_KEY = 'cpac_layouts';

	const COLUMNS_KEY = 'cpac_options_';

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @param ListScreen $list_screen
	 */
	public function __construct( $type, $id ) {
		$this->type = $type;
		$this->id = $id;
	}

	/**
	 * @return string $key
	 */
	private function settings_key() {
		return self::SETTINGS_KEY . $this->type . $this->id;
	}

	/**
	 * @return string $key
	 */
	private function columns_key() {
		return self::COLUMNS_KEY . $this->type . $this->id;
	}

	/**
	 * @return array
	 */
	public function read() {
		$data['columns'] = get_option( $this->columns_key(), array() );

		if ( $settings = get_option( $this->settings_key() ) ) {
			$data = array_merge( $data, (array) $settings );
		}

		return $data;
	}

	/**
	 * @param array $data
	 *
	 * @return bool
	 */
	public function update( $data ) {
		if ( empty( $data ) ) {
			return false;
		}

		$defaults = array(
			'columns' => array(),
			'id'      => '',
			'name'    => '',
			'roles'   => array(),
			'users'   => array(),
		);

		$data = array_merge( $defaults, $data );

		$settings = $data;
		unset( $settings['columns'] );

		update_option( $this->settings_key(), (object) $settings );

		return update_option( $this->columns_key(), (array) $data['columns'] );
	}

	/**
	 * Delete stored data
	 */
	public function delete() {
		delete_option( $this->columns_key() );
		delete_option( $this->settings_key() );

		return true;
	}

	/**
	 * Delete all settings
	 */
	public static function delete_all() {
		global $wpdb;

		$sql = $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s", self::SETTINGS_KEY . '%' );
		$wpdb->query( $sql );

		$sql = $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s", self::COLUMNS_KEY . '%' );
		$wpdb->query( $sql );
	}

}