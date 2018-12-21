<?php
namespace AC\Admin\Request\ListScreen;

use AC\Admin\Preferences\LastVisitedSceen;
use AC\ListScreenFactory;
use AC\ListScreens;

class Select {

	/**
	 * @return \AC\ListScreen
	 */
	public static function get_list_screen() {
		$last_visited = new LastVisitedSceen();

		// User selected
		$list_screen = ListScreenFactory::create( filter_input( INPUT_GET, 'list_screen' ), filter_input( INPUT_GET, 'layout_id' ) );

		// Last visited screen
		if ( ! $list_screen ) {
			$list_screen = ListScreenFactory::create( $last_visited->get_type(), $last_visited->get_id() );
		}

		// First one
		if ( ! $list_screen ) {
			// todo: id
			$list_screen = ListScreenFactory::create( key( ListScreens::get_list_screens() ) );
		}

		// Load table headers
		// todo
		if ( ! $list_screen->get_original_columns() ) {
			$list_screen->set_original_columns( $list_screen->get_default_column_headers() );
		}

		if ( $last_visited->get_type() !== $list_screen->get_key() || $last_visited->get_id() !== $list_screen->get_layout_id() ) {
			$last_visited
				->set_type( $list_screen->get_key() )
				->set_id( $list_screen->get_layout_id() );
		}

		// todo: remove
		//do_action( 'ac/settings/list_screen', $list_screen );

		return $list_screen;
	}

}