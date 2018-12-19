<?php
namespace AC;

class ListScreens {

	/** @var ListScreen[] */
	private static $list_screens = array();

	public static function register_list_screen( ListScreen $list_screen ) {
		self::$list_screens[ $list_screen->get_key() ] = $list_screen;
	}

	/**
	 * @return ListScreen[]
	 */
	public static function get_list_screens() {
		return self::$list_screens;
	}

}