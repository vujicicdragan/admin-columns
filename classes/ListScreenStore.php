<?php

namespace AC;

// TODO: move to ListScreen
abstract class ListScreenStore {

	const OPTION_LAYOUT = 'cpac_layouts';

	/**
	 * @param ListScreen $list_screen
	 */
	public static function update( ListScreen $list_screen ) {
		$data = array(
			'id'    => $list_screen->get_id(),
			'name'  => $list_screen->get_custom_label(),
			'roles' => $list_screen->get_roles(),
			'users' => $list_screen->get_users(),
		);

		update_option( self::OPTION_LAYOUT . $list_screen->get_storage_key(), (object) $data );
	}

	/**
	 * @param ListScreen $list_screen
	 */
	public static function delete( ListScreen $list_screen ) {
		delete_option( self::OPTION_LAYOUT . $list_screen->get_storage_key() );

		do_action( 'ac/layout/delete', $list_screen );
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return string[] ID's
	 */
	public static function get_layouts_ids( ListScreen $list_screen ) {
		global $wpdb;

		$ids = array();

		$key = self::OPTION_LAYOUT . $list_screen->get_key();

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

		return $ids;
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return \stdClass|false
	 */
	public static function get_layout_data( ListScreen $list_screen ) {
		global $wpdb;

		$data = $wpdb->get_var( $wpdb->prepare( "SELECT {$wpdb->options}.option_value FROM {$wpdb->options} WHERE option_name = %s ORDER BY option_id DESC", self::OPTION_LAYOUT . $list_screen->get_storage_key() ) );

		if ( ! $data ) {
			return false;
		}

		return unserialize( $data );
	}

	/**
	 * Delete all layouts from DB
	 */
	public static function delete_all() {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like( self::OPTION_LAYOUT ) . '%' ) );
	}

}