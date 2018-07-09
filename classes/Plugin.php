<?php

namespace AC;

use AC\Asset\Location;

final class Plugin {

	/**
	 * @var string
	 */
	protected $file;

	/**
	 * @var Location[]
	 */
	protected $locations;

	/**
	 * @param string $file
	 */
	public function __construct( $file ) {
		$this->file = $file;
	}

	/**
	 * @return string
	 */
	public function get_file() {
		return $this->file;
	}

	/**
	 * @return string
	 */
	public function get_basename() {
		return plugin_basename( $this->get_file() );
	}

	/**
	 * @return string
	 */
	public function get_dir() {
		return plugin_dir_path( $this->get_file() );
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return plugin_dir_url( $this->get_file() );
	}

	/**
	 * Check if plugin is network activated
	 *
	 * @return bool
	 */
	public function is_network_active() {
		return is_plugin_active_for_network( $this->get_basename() );
	}

	/**
	 * Calls get_plugin_data() for this plugin
	 *
	 * @see get_plugin_data()
	 * @return array
	 */
	protected function get_data() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		return get_plugin_data( $this->get_file(), false, false );
	}

	/**
	 * @since 3.2
	 * @return false|string
	 */
	public function get_name() {
		return $this->get_header( 'Name' );
	}

	/**
	 * Return a plugin header from the plugin data
	 *
	 * @param $key
	 *
	 * @return false|string
	 */
	public function get_header( $key ) {
		$data = $this->get_data();

		if ( ! isset( $data[ $key ] ) ) {
			return false;
		}

		return $data[ $key ];
	}

	/**
	 * @return string
	 */
	public function get_version() {
		return $this->get_header( 'Version' );
	}

	/**
	 * @param string $version
	 *
	 * @return bool
	 */
	public function is_version_gte( $version ) {
		return version_compare( $this->get_version(), $version, '>=' );
	}

	/**
	 * Check if a plugin is in beta
	 *
	 * @since 3.2
	 * @return bool
	 */
	public function is_beta() {
		return false !== strpos( $this->get_version(), 'beta' );
	}

	/**
	 * @param string   $key
	 * @param Location $location
	 *
	 * @return $this
	 */
	public function add_location( $key, Location $location ) {
		$this->locations[ $key ] = $location;

		return $this;
	}

	/**
	 * @param string $key
	 *
	 * @return Location|false
	 */
	public function get_location( $key ) {
		if ( ! isset( $this->locations[ $key ] ) ) {
			return false;
		}

		return $this->locations[ $key ];
	}

	// TODO: cut of keep/ loose

	/**
	 * Apply updates to the database
	 *
	 * @param null|string $updates_dir
	 */
	public function install() {
		if ( 0 === version_compare( $this->get_version(), $this->get_stored_version() ) ) {
			return;
		}

		$updater = new Plugin\Updater( $this );

		if ( ! $updater->check_update_conditions() ) {
			return;
		}

		$reflection = new \ReflectionObject( $this );
		$classes = Autoloader::instance()->get_class_names_from_dir( $reflection->getNamespaceName() . '\Plugin\Update' );

		foreach ( $classes as $class ) {
			$updater->add_update( new $class( $this->get_stored_version() ) );
		}

		$updater->parse_updates();
	}

	// TODO: Stored version object or something, not this!

	/**
	 * @return string
	 */
	//abstract protected function get_version_key();

	// TODO: mv

	/**
	 * @return string
	 */
	public function get_stored_version() {
		return get_option( $this->get_version_key() );
	}

	// TODO: mv

	/**
	 * Update the stored version to match the (current) version
	 */
	public function update_stored_version( $version = null ) {
		if ( null === $version ) {
			$version = $this->get_version();
		}

		return update_option( $this->get_version_key(), $version );
	}

	/**
	 * Check if the plugin was updated or is a new install
	 */

	// TODO: mv
	public function is_new_install() {
		global $wpdb;

		$sql = "
			SELECT option_id
			FROM {$wpdb->options}
			WHERE option_name LIKE 'cpac_options_%'
			LIMIT 1
		";

		$results = $wpdb->get_results( $sql );

		return empty( $results );
	}

	/**
	 * Return a plugin header from the plugin data
	 *
	 * @param $key
	 *
	 * @deprecated
	 * @return false|string
	 */
	protected function get_plugin_header( $key ) {
		_deprecated_function( __METHOD__, '3.2', 'AC\Plugin::get_header()' );

		return $this->get_header( $key );
	}

	/**
	 * Calls get_plugin_data() for this plugin
	 *
	 * @deprecated
	 * @see get_plugin_data()
	 * @return array
	 */
	protected function get_plugin_data() {
		_deprecated_function( __METHOD__, '3.2', 'AC\Plugin::get_data()' );

		return $this->get_data();
	}

	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function get_plugin_url() {
		_deprecated_function( __METHOD__, '3.2.', 'AC\Plugin::get_url()' );

		return $this->get_url();
	}

	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function get_plugin_dir() {
		_deprecated_function( __METHOD__, '3.2', 'AC\Plugin::get_dir()' );

		return $this->get_dir();
	}

}