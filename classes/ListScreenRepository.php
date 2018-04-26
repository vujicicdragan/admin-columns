<?php

namespace AC;

/**
 * @since NEWVERSION
 */
class ListScreenRepository {

	/**
	 * @param string $type
	 *
	 * @return ListScreen[]
	 */
	public function fetch_all( $type ) {
		$list_screens = array();

		foreach ( $this->get_ids( $type ) as $id ) {
			$list_screens[] = ListScreenFactory::create( $type, $id );
		}

		return $list_screens;
	}

	/**
	 * @param string $type
	 *
	 * @return ListScreen|false
	 */
	public function first( $type ) {
		return ListScreenFactory::create( $type, current( $this->get_ids( $type ) ) );
	}

	/**
	 * @return string[]
	 */
	private function get_ids( $type ) {
		global $wpdb;

		$ids = wp_cache_get( 'list-screen-ids', $type );

		if ( empty( $ids ) ) {

			$ids = array();

			// TODO
			$key = ListScreenStore::SETTINGS_KEY . $type;

			$results = $wpdb->get_results( $wpdb->prepare( "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s AND option_value != '' ORDER BY option_id DESC", $wpdb->esc_like( $key ) . '%' ) );

			if ( $results ) {
				foreach ( $results as $result ) {

					$data = maybe_unserialize( $result->option_value );

					if ( ! $data ) {
						continue;
					}

					// Removes incorrect layouts.
					// For example when a list screen is called "Car" and one called "Carrot"
					if ( strlen( $result->option_name ) !== strlen( $key . $data->id ) ) {
						continue;
					}

					$ids[] = $data->id;
				}
			}

			wp_cache_add( 'list-screen-ids', $ids, $type );
		}

		return $ids;
	}

}