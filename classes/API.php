<?php

namespace AC;

class API {

	/**
	 * @var array [
	 *      $listscreen_key => [
	 *          [ 'columns' ][ array $column_settings ]
	 *          [ 'layout' ][ array $layout_settings ]
	 *      ]
	 * ]
	 */
	private $data = array();

	/**
	 * @param string $type ListScreen::type
	 * @param array  $column_data
	 */
	public function load_data( $type, $data ) {

		$data = $this->convert_format_v1_to_v2( $data );
		$data = $this->convert_format_v2_to_v3( $data );

		$data = $this->set_as_read_only( $data );

		$this->data[ $type ] = array_merge( $this->get_data_per_type( $type ), $data );
	}

	/**
	 * @return array
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * @param $type
	 *
	 * @return array
	 */
	public function get_data_per_type( $type ) {
		if ( ! isset( $this->data[ $type ] ) ) {
			return array();
		}

		return $this->data[ $type ];
	}

	/**
	 * @param array $columndata
	 *
	 * @return array
	 */
	private function set_as_read_only( $data ) {
		foreach ( $data as $k => $column ) {
			$data[ $k ]['read_only'] = true;
		}

		return $data;
	}

	/**
	 * @param array $v2
	 *
	 * @return array
	 */
	private function convert_format_v2_to_v3( $v2 ) {
		$data = array();

		// TODO: check old format
		// TODO: export to new format

		foreach ( $v2 as $list_screen_data ) {
			$v3 = $list_screen_data;

			if ( isset( $list_screen_data['layout'] ) ) {
				$v3 = array_merge( $v3, $list_screen_data['layout'] );
			}

			unset( $v3['layout'] );

			$data[] = $v3;
		}

		return $data;
	}

	/**
	 * @param array $v1
	 *
	 * @return array
	 */
	private function convert_format_v1_to_v2( $columndata ) {

		// TODO: test. break early

		// Convert old export formats to new layout format
		$old_format_columns = array();
		foreach ( $columndata as $k => $data ) {
			if ( ! isset( $data['columns'] ) ) {
				$old_format_columns[ $k ] = $data;
				unset( $columndata[ $k ] );
			}
		}

		if ( $old_format_columns ) {
			array_unshift( $columndata, array( 'columns' => $old_format_columns ) );
		}

		// Add layout if missing
		foreach ( $columndata as $k => $data ) {
			if ( ! isset( $data['layout'] ) ) {

				$columndata[ $k ] = array(
					'columns' => isset( $data['columns'] ) ? $data['columns'] : $data,
					'layout'  => array(
						// unique id based on settings
						'id'   => sanitize_key( substr( md5( serialize( $data ) ), 0, 16 ) ),
						'name' => __( 'Imported' ) . ( $k ? ' #' . $k : '' ),
					),
				);
			}
		}

		return $columndata;
	}

}