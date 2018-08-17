<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;
use AC\View;

class Filtering extends Settings\Column\Pro
	implements Settings\Header {

	public function create_header_view() {
		$view = new View( array(
			'title'    => __( 'Enable Filtering', 'codepress-admin-columns' ),
			'dashicon' => 'dashicons-filter',
			'state'    => 'off',
		) );

		$view->set_template( 'settings/header-icon' );

		return $view;
	}

	protected function get_label() {
		return __( 'Filtering', 'codepress-admin-columns' );
	}

	protected function get_tooltip() {
		return __( "This will make the column filterable.", 'codepress-admin-columns' );
	}

	protected function define_options() {
		return array( 'filter' );
	}

}