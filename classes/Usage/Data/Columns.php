<?php
namespace AC\Usage\Data;

use AC\Column\CustomField;
use AC\ListScreenFactory;
use AC\Usage\Data;
use ACA\ACF;
use ACP\Editing\Editable;
use ACP\Filtering\Filterable;
use ACP\Layouts;
use ACP\Sorting\Sortable;

class Columns extends Data {

	public function __construct() {
		parent::__construct( 'columns' );
	}

	public function get_data() {
		$data = array();

		foreach ( $this->get_columns() as $column ) {
			$data[ $column->get_list_screen()->get_key() ][ $column->get_list_screen()->get_layout_id() ][] = $this->get_column_data( $column );;
		}

		return $data;
	}

	/**
	 * @return array
	 */
	private function get_columns() {
		$columns = array();

		foreach ( $this->get_list_screens() as $list_screen ) {
			if ( ! $list_screen->get_settings() ) {
				continue;
			}

			$columns = array_merge( $columns, $list_screen->get_columns() );
		}

		return $columns;
	}

	/**
	 * @return \AC\ListScreen[]
	 */
	private function get_list_screens() {
		if ( ac_is_pro_active() ) {
			foreach ( AC()->get_list_screens() as $list_screen ) {
				$layouts = new Layouts( $list_screen );

				foreach ( $layouts->get_layouts() as $layout ) {
					$list_screens[] = ListScreenFactory::create( $list_screen->get_key(), $layout->get_id() );
				}
			}
		} else {
			$list_screens = AC()->get_list_screens();
		}

		return $list_screens;
	}

	/**
	 * @param \AC\Column $column
	 *
	 * @return array
	 */
	private function get_column_data( \AC\Column $column ) {
		$settings = array(
			'type' => $column->get_type(),
		);

		if ( $column instanceof CustomField ) {
			$settings['field'] = $column->get_field_type();
		}

		if ( $column instanceof ACF\Column ) {
			$settings['field'] = get_class( $column->get_field() );
		}

		if ( $column instanceof Sortable ) {
			$settings['sort'] = $column->sorting()->is_active() ? '1' : '0';
		}

		if ( $column instanceof Filterable ) {
			$settings['filter'] = $column->filtering()->is_active() ? '1' : '0';
		}

		if ( $column instanceof Editable ) {
			$settings['edit'] = $column->editing()->is_active() ? '1' : '0';
		}

		return $settings;
	}

}