<?php

namespace AC\ListScreens\Store;

use AC\ListScreens\Store;

// TODO: add interface for Read/Write

class DB extends Store {

	const SETTINGS_KEY = 'cpac_layouts';

	const COLUMNS_KEY = 'cpac_options_';

	public function get_store_type() {
		return 'db';
	}

	/**
	 * @return string $key
	 */
	private function settings_key() {
		return self::SETTINGS_KEY . $this->type;
	}

	/**
	 * @return string $key
	 */
	private function columns_key() {
		return self::COLUMNS_KEY . $this->type;
	}

	/**
	 * @param string $id
	 *
	 * @return array|false
	 */
	public function read( $id ) {
		$columns = get_option( $this->columns_key() . $id, array() );
		$settings = (array) get_option( $this->settings_key() . $id, array() ); // stored as object

		if ( empty( $settings ) && empty( $columns ) ) {
			return false;
		}

		$data['columns'] = $columns;

		if ( $settings ) {
			$data = array_merge( $data, $settings );
		}

		return $data;
	}

	/**
	 * @param string $id
	 * @param array  $data
	 *
	 * @return bool
	 */
	public function update( $id, $data ) {
		if ( empty( $data ) ) {
			return false;
		}

		$columns = isset( $data['columns'] ) ? $data['columns'] : array();
		unset( $data['columns'] );

		update_option( $this->settings_key() . $id, (object) $data );

		return update_option( $this->columns_key() . $id, (array) $columns );
	}

	/**
	 * Delete stored data
	 */
	public function delete( $id ) {
		delete_option( $this->columns_key() . $id );
		delete_option( $this->settings_key() . $id );

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