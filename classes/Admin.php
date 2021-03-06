<?php
namespace AC;

use AC\Admin\Helpable;
use AC\Admin\MenuItem;
use AC\Admin\Page;
use AC\Admin\PageFactory;
use AC\Admin\PageFactoryInterface;

class Admin {

	const PLUGIN_PAGE = 'codepress-admin-columns';

	/** @var string */
	private $hook_suffix;

	/** @var string */
	private $parent_page;

	/** @var Page */
	private $page;

	/** @var PageFactory */
	protected $page_factory;

	public function __construct( $parent_page ) {
		$this->parent_page = $parent_page;
	}

	/**
	 * @param PageFactoryInterface $page_factory
	 *
	 * @return Admin
	 */
	public function register_page_factory( PageFactoryInterface $page_factory ) {
		$this->page_factory = $page_factory;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_parent_page() {
		return $this->parent_page;
	}

	/**
	 * @return void
	 */
	public function settings_menu() {
		$this->hook_suffix = add_submenu_page(
			$this->parent_page,
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			self::PLUGIN_PAGE,
			function () {
			}
		);

		add_action( "load-" . $this->hook_suffix, array( $this, 'on_load' ) );
		add_action( "admin_print_scripts-" . $this->hook_suffix, array( $this, 'admin_scripts' ) );
	}

	/**
	 * @return void
	 */
	public function on_load() {
		$tab = filter_input( INPUT_GET, 'tab' );

		if ( ! $tab ) {
			$tab = current( $this->get_menu_items() )->get_slug();
		}

		$page = $this->page_factory->create( $tab );

		if ( $page instanceof Registrable ) {
			$page->register();
		}

		if ( $page instanceof Helpable ) {
			foreach ( $page->get_help_tabs() as $help ) {
				get_current_screen()->add_help_tab( array(
					'id'      => $help->get_id(),
					'content' => $help->get_content(),
					'title'   => $help->get_title(),
				) );
			}
		}

		if ( ! $page ) {
			return;
		}

		$this->page = $page;

		add_action( $this->hook_suffix, array( $this, 'render' ) );
	}

	/**
	 * @param string $tab
	 *
	 * @return string
	 */
	public function get_url( $tab ) {
		$args = array(
			'tab'  => $tab,
			'page' => self::PLUGIN_PAGE,
		);

		return add_query_arg( $args, $this->get_parent_page() );
	}

	/**
	 * @return MenuItem[]
	 */
	private function get_menu_items() {
		$items = array();

		foreach ( $this->page_factory->get_slugs() as $slug ) {
			$page = $this->page_factory->create( $slug );

			if ( $page && $page->show_in_menu() ) {
				$items[] = new MenuItem( $page->get_slug(), $page->get_label(), $this->get_url( $page->get_slug() ) );
			}
		}

		return $items;
	}

	/**
	 * @return void
	 */
	public function render() {
		?>
		<div id="cpac" class="wrap">
			<?php

			$menu = new View( array(
				'items'   => $this->get_menu_items(),
				'current' => $this->page->get_slug(),
			) );

			echo $menu->set_template( 'admin/edit-tabmenu' );

			$this->page->render();
			?>
		</div>
		<?php

		do_action( 'ac/admin/render', $this );
	}

	/**
	 * Scripts
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_script( 'ac-admin-general', AC()->get_url() . "assets/js/admin-general.js", array( 'jquery', 'wp-pointer' ), AC()->get_version() );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'ac-admin', AC()->get_url() . "assets/css/admin-general.css", array(), AC()->get_version() );

		do_action( 'ac/admin_scripts' );
	}

}