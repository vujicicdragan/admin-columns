<?php
namespace AC\ListScreen;

use AC\DataObject;
use AC\ListScreen;
use WP_Screen;

class User extends ListScreen {

	const TYPE = 'user';

	public function __construct( DataObject $settings = null ) {
		parent::__construct( self::TYPE, 'user', 'User', $settings );
	}

	public function set_value_callback() {
		add_filter( 'manage_users_custom_column', function ( $value, $column_name, $user_id ) {

			// todo
			return 'value: ' . $value . $column_name . $user_id;
		}, 100, 3 );
	}

	public function is_valid( WP_Screen $wp_screen ) {
		return 'profile' === $wp_screen->id;
	}

	public function get_url() {
		return 'users.php';
	}

}