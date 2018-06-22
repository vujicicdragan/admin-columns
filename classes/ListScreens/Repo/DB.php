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

			$prefix = Store\DB::COLUMNS_KEY . $this->get_type();

			$sql = $wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s", $wpdb->esc_like( $prefix ) . '%' );
			$keys = $wpdb->get_col( $sql );

			if ( $keys ) {

				foreach ( $keys as $key ) {

					$id = str_replace( $prefix, '', $key );

					// TODO
					if ( '' === $id ) {
						$ids[] = '';
					}

					if ( '__default' === $id ) {
						$ids[] = '';
					}

					if ( 13 === strlen( $id ) ) {
						$ids[] = $id;
					}
				}
			}

			wp_cache_add( 'list-screen-ids', $ids, $this->get_type() );
		}

		return $ids;
	}

}