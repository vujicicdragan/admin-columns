<?php

namespace AC\Usage;

abstract class Data {

	/** @var string */
	private $name;

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * @return array
	 */
	abstract public function get_data();

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

}