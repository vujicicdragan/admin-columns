<?php
namespace AC\Admin\Request;

use AC\Admin\Preferences\LastVisitedSceen;
use AC\ListScreenFactory;
use AC\ListScreens;

class ListScreen {

	/**
	 * @return \AC\ListScreen
	 */
	public static function get_list_screen() {
		$last_visited = new LastVisitedSceen();

		// User selected
		$list_screen = ListScreenFactory::create( filter_input( INPUT_GET, 'list_screen' ) );

		// Last visited screen
		if ( ! $list_screen ) {
			$list_screen = ListScreenFactory::create( $last_visited->get_list_screen() );
		}

		// First one
		if ( ! $list_screen ) {
			$list_screen = ListScreenFactory::create( key( ListScreens::get_list_screens() ) );
		}

		// Load table headers
		// todo
		if ( ! $list_screen->get_original_columns() ) {
			$list_screen->set_original_columns( $list_screen->get_default_column_headers() );
		}

		if ( $last_visited->get_list_screen() !== $list_screen->get_key() ) {
			$last_visited->set_list_screen( $list_screen->get_key() );
		}

		do_action( 'ac/settings/list_screen', $list_screen );

		return $list_screen;
	}

}