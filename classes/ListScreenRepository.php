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

		foreach ( $this->get_repositories() as $repo ) {
			foreach ( $repo->get_ids() as $id ) {
				$list_screens[] = ListScreenFactory::create( $this->type, $id );
			}
		}

		return $list_screens;
	}

	/**
	 * @return ListScreen|false
	 */
	public function first() {
		return current( $this->fetch_all() );
	}

	/**
	 * @param $type
	 *
	 * @return ListScreenRepo[]
	 */
	private function get_repositories() {
		return array(
			new ListScreenRepoDB( $this->type ),
			new ListScreenRepoPHP( $this->type ),
		);
	}

}