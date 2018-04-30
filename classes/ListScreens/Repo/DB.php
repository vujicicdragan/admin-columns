<?php

namespace AC\ListScreens\Repo;

use AC\ListScreens\Repo;
use AC\ListScreens\Store;

/**
 * @since NEWVERSION
 */
class DB extends Repo {

	public function get_ids() {
		global $wpdb;

		$ids = wp_cache_get( 'list-screen-ids', $this->get_type() );

		if ( empty( $ids ) ) {
			$ids = array();

			$key = Store\DB::SETTINGS_KEY . $this->get_type();

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

			wp_cache_add( 'list-screen-ids', $ids, $this->get_type() );
		}

		return $ids;
	}

}