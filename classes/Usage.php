<?php
namespace AC;

use AC\Usage\Data;

class Usage implements Registrable {

	/** @var Data[] */
	private $data = array();

	/** @var API */
	private $api;

	public function __construct( API $api ) {
		$this->api = $api;
	}

	/**
	 * @param Data $data
	 *
	 * @return $this
	 */
	public function register_data( Data $data ) {
		$this->data[] = $data;

		return $this;
	}

	public function register() {
		// todo: opt-in/consent
		// todo: collect data
		// todo: send report
	}

	public function get_report() {
		$report = array();

		foreach ( $this->data as $data ) {
			$report[ $data->get_name() ] = $data->get_data();
		}

		return $report;
	}

	public function send_report() {
		$request = new API\Request();

		$request->set_body( array(
			'api'  => 'usage-report',
			'data' => $this->get_report(),
		) );

		return $this->api->request( $request );
	}

}