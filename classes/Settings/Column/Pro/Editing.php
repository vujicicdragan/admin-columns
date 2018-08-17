<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;
use AC\View;

class Editing extends Settings\Column\Pro
	implements Settings\Header {

	public function create_header_view() {
		$view = new View( array(
			'title'    => __( 'Enable Editing', 'codepress-admin-columns' ),
			'dashicon' => 'dashicons-edit',
			'state'    => 'off',
		) );

		$view->set_template( 'settings/header-icon' );

		return $view;
	}

	protected function get_label() {
		return __( 'Editing', 'codepress-admin-columns' );
	}

	protected function get_tooltip() {
		return __( 'Edit your content directly from the overview.', 'codepress-admin-columns' );
	}

	protected function define_options() {
		return array( 'edit' );
	}

}