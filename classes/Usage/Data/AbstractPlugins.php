<?php

namespace AC\Usage\Data;

use AC\Usage\Data;

abstract class AbstractPlugins extends Data {

	/**
	 * @return array
	 */
	abstract protected function get_plugins();

	/**
	 * @param string $plugin_path
	 *
	 * @return false|string
	 */
	protected function get_details( $plugin_path ) {
		$plugin_data = get_plugin_data( $plugin_path );
		$plugin_name = strlen( $plugin_data['Name'] ) ? $plugin_data['Name'] : basename( $plugin_path );

		if ( empty( $plugin_name ) ) {
			return false;
		}

		$version = '';
		if ( $plugin_data['Version'] ) {
			$version = sprintf( " (v%s)", $plugin_data['Version'] );
		}

		$author = '';
		if ( $plugin_data['AuthorName'] ) {
			$author = sprintf( " by %s", $plugin_data['AuthorName'] );
		}

		return sprintf( "%s%s%s", $plugin_name, $version, $author );
	}

	/**
	 * @return array
	 */
	public function get_data() {
		$plugins = array();

		foreach ( $this->get_plugins() as $plugin_path ) {
			$plugins[ basename( $plugin_path ) ] = $this->get_details( $plugin_path );
		}

		asort( $plugins );

		return $plugins;
	}

}