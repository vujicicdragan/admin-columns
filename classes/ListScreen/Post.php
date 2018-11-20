<?php

namespace AC\ListScreen;

use AC\DataObject;
use AC\ListScreen;
use WP_Screen;

class Post extends ListScreen {

	const TYPE = 'post';

	public function __construct( $post_type, $label, DataObject $settings = null ) {
		if ( ! $post_type ) {
			throw new \Exception( 'Post type can not be empty.' );
		}

		parent::__construct( self::TYPE, 'post', $label, $settings, $post_type );
	}

	/**
	 * @return string
	 */
	public function get_post_type() {
		return $this->get_subtype();
	}

	public function set_value_callback() {
		add_action( "manage_" . $this->get_post_type() . "_posts_custom_column", function( $column_name, $id ) {

			// todo
			echo 'column value: ' . $column_name . $id;
		}, 100, 2 );
	}

	public function is_valid( WP_Screen $wp_screen ) {

		// todo
		return $this->get_post_type() === $wp_screen->post_type && 'edit-post' === $wp_screen->id;
	}

	public function get_url() {
		return sprintf( 'admin.php/post/%s', $this->get_subtype() );
	}

}