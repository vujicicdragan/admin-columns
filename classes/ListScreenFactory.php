<?php
namespace AC;

use Exception;

class ListScreenFactory {

	/**
	 * @param string     $type
	 * @param string     $subtype
	 * @param DataObject $settings
	 *
	 * @return ListScreen
	 * @throws Exception
	 */
	public function create( $type, $subtype = null, DataObject $settings = null ) {
		if ( null === $settings ) {
			$settings = new DataObject;
		}

		$list_screen_type = $this->get_list_screen_type( $type, $subtype );

		if ( false === $list_screen_type ) {
			throw new Exception( sprintf( 'Invalid list screen type: %s.', $type ) );
		}

		$class_name = get_class( $list_screen_type );

		switch ( true ) {

			case $list_screen_type instanceof ListScreen\Post :
				return new $class_name( $subtype, ucfirst( $subtype ), $settings );

			default :
				return new $class_name( $settings );
		}
	}

	/**
	 * @param string $type
	 * @param string $subtype
	 *
	 * @return ListScreen|false
	 */
	private function get_list_screen_type( $type, $subtype ) {
		foreach ( ListScreenTypes::instance()->get_list_screens() as $list_screen ) {
			if ( $type === $list_screen->get_type() && $subtype === $list_screen->get_subtype() ) {
				return $list_screen;
			}
		}

		return false;
	}

	/**
	 * @param int    $id
	 * @param string $store_type
	 *
	 * @return ListScreen
	 * @throws Exception
	 */
	public function create_by_id( $id, $store_type ) {
		$factory = new StoreFactory();

		$store = $factory->create( $store_type );

		if ( ! $store ) {
			throw new Exception( sprintf( 'Listscreen Store type:%s not found.', $store_type ) );
		}

		$data = $store->read( $id );

		if ( $data->is_empty() ) {
			throw new Exception( sprintf( 'Listscreen ID:%s not found.', $id ) );
		}

		return $this->create( $data->type, $data->subtype, new DataObject( $data->settings ) );
	}

}