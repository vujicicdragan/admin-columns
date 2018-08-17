<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;
use AC\View;

class Sorting extends Settings\Column\Pro
	implements Settings\Header{

	public function create_header_view() {
		$view = new View( array(
			'title'    => __( 'Enable Sorting', 'codepress-admin-columns' ),
			'dashicon' => 'dashicons-sort',
			'state'    => 'off',
		) );

		$view->set_template( 'settings/header-icon' );

		return $view;
	}

	protected function get_label() {
		return __( 'Sorting', 'codepress-admin-columns' );
	}

	protected function get_tooltip() {
		return __( "This will make the column sortable.", 'codepress-admin-columns' );
	}

	protected function define_options() {
		return array( 'sort' );
	}

}