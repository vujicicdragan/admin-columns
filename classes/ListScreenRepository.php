<?php

namespace AC;

/**
 * @since NEWVERSION
 */
class ListScreenRepository {

	/**
	 * @var string ListScreen::type
	 */
	private $type;

	public function __construct( $type ) {
		$this->type = $type;
	}

	/**
	 * @param string $type
	 *
	 * @return ListScreen[]
	 */
	public function fetch_all() {
		$list_screens = array();

		$repo = new ListScreenRepoDB( $this->type );

		foreach ( $repo->get_ids() as $id ) {
			$list_screens[] = ListScreenFactory::create( $this->type, $id, 'db' );
		}

		$repo = new ListScreenRepoPHP( $this->type );

		foreach ( $repo->get_ids() as $id ) {
			$list_screens[] = ListScreenFactory::create( $this->type, $id, 'php' );
		}

		return $list_screens;
	}

	/**
	 * @return ListScreen|false
	 */
	public function first() {
		return current( $this->fetch_all() );
	}

}