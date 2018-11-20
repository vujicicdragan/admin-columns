<?php
namespace AC\Admin\Page;

use AC\Admin\Page;
use AC\ColumnTypes;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenTypes;
use AC\Message\Notice;
use AC\StoreFactory;
use AC\Stores;

class Columns extends Page {

	/** @var ListScreen */
	private $list_screen;

	public function __construct() {
		$factory = new ListScreenFactory();

		try {
			$this->list_screen = $factory->create_by_id( filter_input( INPUT_GET, 'id' ), filter_input( INPUT_GET, 'store_type' ) );
		} catch ( \Exception $e ) {

			$notice = new Notice( $e->getMessage() );
			$notice->register();
		}
	}

	public function display() {
		if ( ! $this->list_screen ) {
			return;
		}

		$this->submenu();
		$this->columns();

		//new Page\Columns\Submenu( $this->list_screen );

		//		echo "<h1>Editor - :{$this->list_screen->get_label()}</h1>";
		//
		//		echo "<h2>Menu</h2>";
		//		$this->render_menu();
		//
		//		echo "<h2>Submenu</h2>";
		//		$this->render_submenu();
		//
		//		echo "<h2>Columns</h2>";
		//		$this->render_columns();
		//
		//		echo "<h2>Select</h2>";
		//		$this->render_select();
		//
		//		echo "<p><hr></p>";
	}

	public function columns() {
		try {
			$columns = $this->list_screen->get_columns();
		} catch ( \Exception $e ) {
			ac_view( 'message', array( 'message' => 'Visit list page' ) );

			return;
		}

		foreach ( $columns as $column ) {
			ac_view( 'editor/column', compact( 'column' ) );
		}
	}

	/**
	 * @throws \Exception
	 */
	private function submenu() {

		$stores = new Stores();

		$factory = new StoreFactory();

		$data = $this->store_factory->create( $this->request->store_type )->query( [ 'type' => $this->list_screen->get_type(), 'subtype' => $this->list_screen->get_subtype() ] );

		$items = array();
		foreach ( $data as $id => $object ) {
			$list_screen = $this->list_factory->create( $object->type, $object->subtype, new DataObject( $object->settings ) );

			$items[ $id ] = $list_screen->get_settings()->label;
		}

		if ( empty( $items ) ) {
			ac_view( 'message', [ 'message' => 'Empty' ] );
		}

		ac_view( 'list', compact( 'items' ) );
	}

	private function render_select() {
		$title = 'Type Select';

		$options = array();
		foreach ( ColumnTypes::instance()->get_registered_types( $this->list_screen->get_type() ) as $column ) {
			if ( ! $column->is_valid( $this->list_screen->get_subtype() ) ) {
				continue;
			}

			$options[ $column->get_type() ] = $column->get_label();
		}

		ac_view( 'select', compact( 'title', 'options' ) );
	}

	private function render_menu() {
		$items = array();

		foreach ( ListScreenTypes::instance()->get_list_screens() as $list_screen ) {
			$items[ $list_screen->get_group() ][ $list_screen->get_url() ] = $list_screen->get_label();
		}

		ac_view( 'menu', array( 'menu' => $items, 'current' => $this->list_screen->get_url() ) );
	}

}