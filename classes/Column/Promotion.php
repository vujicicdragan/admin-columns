<?php
namespace AC\Column;

interface Promotion {

	/**
	 * Return a message that will be displayed when selecting a column that has this interface.
	 * @return string
	 */
	public function get_message();

}