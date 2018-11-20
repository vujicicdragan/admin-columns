<?php
namespace AC;

use AC\Store\DB;
use AC\Store\JSON;

class Stores extends ArrayIterator {

	public function __construct() {
		$stores = array(
			'db'     => new DB(),
			'native' => new JSON( 'storage/native.json' ),
			'test'   => new JSON( 'storage/database.json' ),
		);

		parent::__construct( $stores );
	}

}