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

		// Since Version 3.8 - March 16th, 2016
		$data = $this->convert_format_v1_to_v2( $data );

		// Since Version NEWVERSION
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
		foreach ( $data as $k => $_data ) {
			$data[ $k ]['read_only'] = true;
		}

		return $data;
	}

	/**
	 * Converts older formats to Version NEWVERSION
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	private function convert_format_v2_to_v3( $data ) {
		if ( empty( $data ) ) {
			return array();
		}

		foreach ( $data as $k => $_data ) {
			if ( ! isset( $_data['layout'] ) ) {
				continue;
			}

			$settings = $_data['layout'];

			unset( $_data['layout'] );

			$data[ $k ] = array_merge( $_data, $settings );
		}

		return $data;
	}

	/**
	 * Converts older formats to Version 3.8 (March 16th, 2016)
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	private function convert_format_v1_to_v2( $data ) {
		if ( empty( $data ) ) {
			return array();
		}

		if ( ! is_string( key( $data ) ) ) {
			return $data;
		}

		return array(
			array(
				'columns' => $data,
				'layout'  => array(
					'name' => __( 'Imported' ),
					'id'   => sanitize_key( substr( md5( serialize( $data ) ), 0, 16 ) ),
				),
			),
		);
	}

}