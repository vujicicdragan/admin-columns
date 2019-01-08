<?php
namespace AC\Usage\Data;

class MUPlugins extends AbstractPlugins {

	public function __construct() {
		parent::__construct( 'mu-plugins' );
	}

	/**
	 * @return array
	 */
	public function get_plugins() {
		return wp_get_mu_plugins();
	}

}