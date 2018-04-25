<?php

namespace AC;

abstract class ListScreenStore {

	const SETTINGS_KEY = 'cpac_layouts';

	const COLUMNS_KEY = 'cpac_options_';

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return bool
	 */
	public static function update( ListScreen $list_screen ) {

		$data = array(
			'id'    => $list_screen->get_id(),
			'name'  => $list_screen->get_custom_label(),
			'roles' => $list_screen->get_roles(),
			'users' => $list_screen->get_users(),
		);

		update_option( self::SETTINGS_KEY . $list_screen->get_storage_key(), (object) $data );
		$result = update_option( self::COLUMNS_KEY . $list_screen->get_storage_key(), (array) $list_screen->get_settings() );

		/**
		 * Fires after a new column setup is stored in the database
		 * Primarily used when columns are saved through the Admin Columns settings screen
		 *
		 * @since 3.0
		 *
		 * @param ListScreen $list_screen
		 */
		do_action( 'ac/columns_stored', $list_screen );

		return $result;
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return bool
	 */
	public static function create( ListScreen $list_screen ) {
		$list_screen->set_id( uniqid() );

		return self::update( $list_screen );
	}

	/**
	 * @param ListScreen $list_screen
	 */
	public static function delete( ListScreen $list_screen ) {
		delete_option( self::COLUMNS_KEY . $list_screen->get_storage_key() );

		do_action( 'ac/columns_delete', $list_screen );

		delete_option( self::SETTINGS_KEY . $list_screen->get_storage_key() );

		do_action( 'ac/layout/delete', $list_screen );
	}

	/**
	 * Delete all layouts from DB
	 */
	public static function delete_all() {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like( self::SETTINGS_KEY ) . '%' ) );
	}

	/**
	 * @param ListScreen $list_screen
	 * @param array      $headings
	 */
	public static function update_default_headings( ListScreen $list_screen, $headings ) {
		update_option( self::COLUMNS_KEY . $list_screen->get_type() . "__default", $headings );
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return array
	 */
	public static function get_default_headings( ListScreen $list_screen ) {
		return get_option( self::COLUMNS_KEY . $list_screen->get_type() . "__default" );
	}

	//------------------------------------------------------------------------------------------------------------------------------

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return \stdClass|false
	 */
	public static function get_layout_data( ListScreen $list_screen ) {
		return get_option( self::SETTINGS_KEY . $list_screen->get_storage_key() );
	}

	/**
	 * @param ListScreen $list_screen
	 */
	public static function get_column_data( ListScreen $list_screen ) {
		return get_option( self::COLUMNS_KEY . $list_screen->get_storage_key(), array() );
	}

	/**
	 * @param string $type
	 *
	 * @return string[] ID's
	 */
	public static function get_ids( $type ) {
		global $wpdb;

		$ids = wp_cache_get( 'list-screen-ids', $type );

		if ( empty( $ids ) ) {

			$ids = array();

			$key = self::SETTINGS_KEY . $type;

			$results = $wpdb->get_results( $wpdb->prepare( "SELECT {$wpdb->options}.option_name, {$wpdb->options}.option_value FROM {$wpdb->options} WHERE option_name LIKE %s ORDER BY option_id DESC", $wpdb->esc_like( $key ) . '%' ) );

			if ( $results ) {
				foreach ( $results as $result ) {

					// Removes incorrect layouts.
					// For example when a list screen is called "Car" and one called "Carrot"
					if ( strlen( $result->option_name ) !== ( strlen( $key ) + 13 ) && $result->option_name != $key ) {
						continue;
					}

					$data = unserialize( $result->option_value );

					$ids[] = $data->id;
				}
			}

			wp_cache_add( 'list-screen-ids', $ids, $type );
		}

		return $ids;
	}

}