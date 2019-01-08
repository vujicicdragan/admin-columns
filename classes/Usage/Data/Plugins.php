<?php
namespace AC\Usage\Data;

class Plugins extends AbstractPlugins {

	public function __construct() {
		parent::__construct( 'plugins' );
	}

	/**
	 * @param string $name
	 *
	 * @return string
	 */
	public function remove_wp_plugin_dir( $name ) {
		$plugin = str_replace( WP_PLUGIN_DIR, '', $name );

		return substr( $plugin, 1 );
	}

	protected function get_plugins() {
		$active_plugins = wp_get_active_and_valid_plugins();

		if ( is_multisite() ) {
			$network_active_plugins = wp_get_active_network_plugins();
			$active_plugins = array_map( array( $this, 'remove_wp_plugin_dir' ), $network_active_plugins );
		}

		return $active_plugins;
	}

}