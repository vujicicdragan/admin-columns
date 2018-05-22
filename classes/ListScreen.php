<?php

namespace AC;

use AC\ListScreens\Store;

/**
 * List Screen
 *
 * @since 2.0
 */
abstract class ListScreen {

	/**
	 * @var string ID
	 */
	private $id;

	/**
	 * @var string $type
	 */
	private $type;

	/**
	 * @since 2.0
	 * @var string
	 */
	private $label;

	/**
	 * @since 2.3.5
	 * @var string
	 */
	private $singular_label;

	/**
	 * @var string
	 */
	private $custom_label;

	/**
	 * @var array
	 */
	private $users = array();

	/**
	 * @var array
	 */
	private $roles = array();

	/**
	 * Meta type of list screen; post, user, comment. Mostly used for fetching meta data.
	 *
	 * @since 3.0
	 * @var string
	 */
	private $meta_type;

	/**
	 * Page menu slug. Applies only when a menu page is used.
	 *
	 * @since 2.4.10
	 * @var string
	 */
	private $page;

	/**
	 * Group slug. Used for menu.
	 * @var string
	 */
	private $group;

	/**
	 * Name of the base PHP file (without extension).
	 *
	 * @see   \WP_Screen::base
	 *
	 * @since 2.0
	 * @var string
	 */
	private $screen_base;

	/**
	 * The unique ID of the screen.
	 *
	 * @see   \WP_Screen::id
	 *
	 * @since 2.5
	 * @var string
	 */
	private $screen_id;

	/**
	 * @since 2.0.1
	 * @var Column[]
	 */
	private $columns;

	/**
	 * @since 2.2
	 * @var Column[]
	 */
	private $column_types;

	/**
	 * @var array [ Column name => Label ]
	 */
	private $original_columns = array();

	/**
	 * @var array Column settings data
	 */
	private $settings = array();

	/**
	 * @var bool True when column settings can not be overwritten
	 */
	private $read_only = false;

	/**
	 * @var bool
	 */
	private $network_only = false;

	/**
	 * @var Store
	 */
	private $store_object;

	/**
	 * Contains the hook that contains the manage_value callback
	 *
	 * @return void
	 */
	abstract public function set_manage_value_callback();

	/**
	 * Register column types
	 * @return void
	 */
	abstract protected function register_column_types();

	/**
	 * Load all data
	 */
	public function load() {
		$data = $this->get_store_object()->read();

		if ( isset( $data['columns'] ) ) {
			$this->set_settings( $data['columns'] );
		}
		if ( isset( $data['name'] ) ) {
			$this->set_custom_label( $data['name'] );
		}
		if ( isset( $data['users'] ) ) {
			$this->set_users( $data['users'] );
		}
		if ( isset( $data['roles'] ) ) {
			$this->set_roles( $data['roles'] );
		}
		if ( isset( $data['read_only'] ) ) {
			$this->set_read_only( $data['read_only'] );
		}

		$this->set_original_columns( get_option( Store\DB::COLUMNS_KEY . $this->get_type() . "__default", array() ) );
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param string $id
	 *
	 * @return self
	 */
	public function set_id( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param string $type
	 *
	 * @return self
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return Store
	 */
	public function get_store_object() {
		return $this->store_object;
	}

	/**
	 * @param Store $store_object
	 *
	 * @return self
	 */
	public function set_store_object( $store_object ) {
		$this->store_object = $store_object;

		return $this;
	}

	/**
>>>>>>> develop
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return self
	 */
	protected function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_singular_label() {
		if ( null === $this->singular_label ) {
			$this->set_singular_label( $this->label );
		}

		return $this->singular_label;
	}

	/**
	 * @param string $label
	 *
	 * @return self
	 */
	protected function set_singular_label( $label ) {
		$this->singular_label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_meta_type() {
		return $this->meta_type;
	}

	/**
	 * @param string $meta_type
	 *
	 * @return self
	 */
	protected function set_meta_type( $meta_type ) {
		$this->meta_type = $meta_type;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_screen_base() {
		return $this->screen_base;
	}

	/**
	 * @param string $screen_base
	 *
	 * @return self
	 */
	protected function set_screen_base( $screen_base ) {
		$this->screen_base = $screen_base;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_screen_id() {
		return $this->screen_id;
	}

	/**
	 * @param string $screen_id
	 *
	 * @return self
	 */
	protected function set_screen_id( $screen_id ) {
		$this->screen_id = $screen_id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_page() {
		return $this->page;
	}

	/**
	 * @param string $page
	 *
	 * @return self
	 */
	protected function set_page( $page ) {
		$this->page = $page;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_group() {
		return $this->group;
	}

	/**
	 * @param string $group
	 *
	 * @return self
	 */
	public function set_group( $group ) {
		$this->group = $group;

		return $this;
	}

	/**
	 * Settings can not be overwritten
	 *
	 * @return bool
	 */
	public function is_read_only() {
		return $this->read_only;
	}

	/**
	 * @param bool $read_only
	 *
	 * @return self
	 */
	public function set_read_only( $read_only ) {
		$this->read_only = (bool) $read_only;

		return $this;
	}

	/**
	 * Settings can not be overwritten
	 */
	public function is_network_only() {
		return $this->network_only;
	}

	/**
	 * @param bool $network_only
	 *
	 * @return self
	 */
	public function set_network_only( $network_only ) {
		$this->network_only = (bool) $network_only;

		return $this;
	}

	/**
	 * @param array $roles
	 *
	 * @return self
	 */
	public function set_custom_label( $label ) {
		$this->custom_label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_custom_label() {
		return $this->custom_label;
	}

	/**
	 * @param array $roles
	 *
	 * @return self
	 */
	public function set_roles( $roles ) {
		if ( is_array( $roles ) ) {
			$this->roles = $roles;
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_roles() {
		return $this->roles;
	}

	/**
	 * @param array $users
	 *
	 * @return self
	 */
	public function set_users( $users ) {
		if ( is_array( $users ) ) {
			$this->users = $users;
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_users() {
		return $this->users;
	}

	/**
	 * @param array $settings Column settings
	 */
	public function set_settings( $settings ) {
		if ( is_array( $settings ) ) {
			$this->settings = $settings;
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * @return array
	 */
	public function get_original_columns() {
		return $this->original_columns;
	}

	/**
	 * @param array $columns
	 *
	 * @return self
	 */
	public function set_original_columns( $columns ) {
		$this->original_columns = (array) $columns;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_storage_key() {
		return $this->get_type() . $this->get_id();
	}

	/**
	 * ID attribute of targeted list table
	 *
	 * @since 3.0
	 * @return string
	 */
	public function get_table_attr_id() {
		return '#the-list';
	}

	/**
	 * @since 3.0
	 *
	 * @return Column[]
	 */
	public function get_columns() {
		if ( null === $this->columns ) {
			$this->set_columns();
		}

		return $this->columns;
	}

	/**
	 * @return Column[]
	 */
	public function get_column_types() {
		if ( null === $this->column_types ) {
			$this->set_column_types();
		}

		return $this->column_types;
	}

	/**
	 * @since 2.0
	 * @return false|Column
	 */
	public function get_column_by_name( $name ) {
		foreach ( $this->get_columns() as $column ) {
			if ( $column->get_name() == $name ) {
				return $column;
			}
		}

		return false;
	}

	/**
	 * @param string $type
	 *
	 * @return false|Column
	 */
	public function get_column_by_type( $type ) {
		$column_types = $this->get_column_types();

		if ( ! isset( $column_types[ $type ] ) ) {
			return false;
		}

		return $column_types[ $type ];
	}

	/**
	 * @param string $type Column type
	 */
	public function deregister_column_type( $type ) {
		unset( $this->column_types[ $type ] );
	}

	/**
	 * @param Column $column
	 */
	public function register_column_type( Column $column ) {
		if ( ! $column->get_type() ) {
			return;
		}

		$column->set_list_screen( $this );

		if ( ! $column->is_valid() ) {
			return;
		}

		// Skip the custom registered columns which are marked 'original' but are not available for this list screen
		if ( $column->is_original() && ! in_array( $column->get_type(), array_keys( $this->get_original_columns() ) ) ) {
			return;
		}

		$this->column_types[ $column->get_type() ] = $column;
	}

	/**
	 * Available column types
	 */
	private function set_column_types() {
		$this->column_types = array();

		// Register default columns
		foreach ( $this->get_original_columns() as $type => $label ) {
			if ( 'cb' === $type ) {
				continue;
			}

			// TODO: change to string class?
			$column = new Column();

			$column->set_original( true )
			       ->set_type( $type )
			       ->set_list_screen( $this );

			$this->column_types[ $type ] = $column;
		}

		// Load Custom columns
		$this->register_column_types();

		/**
		 * Register column types
		 *
		 * @param ListScreen $this
		 */
		do_action( 'ac/column_types', $this );
	}

	/**
	 * @param string $namespace Namespace from the current path
	 */
	public function register_column_types_from_dir( $namespace ) {
		$classes = Autoloader::instance()->get_class_names_from_dir( $namespace );

		foreach ( $classes as $class ) {
			$reflection = new \ReflectionClass( $class );

			if ( $reflection->isInstantiable() ) {
				$this->register_column_type( new $class );
			}
		}
	}

	/**
	 * @param string $type Column type
	 *
	 * @return bool
	 */
	private function is_original_column( $type ) {
		$column = $this->get_column_by_type( $type );

		if ( ! $column ) {
			return false;
		}

		return $column->is_original();
	}

	/**
	 * @param array $data Column data
	 *
	 * @return Column|false
	 */
	public function create_column( array $data ) {
		if ( ! isset( $data['type'] ) ) {
			return false;
		}

		$column = $this->get_column_by_type( $data['type'] );

		if ( ! $column ) {
			return false;
		}

		$class = get_class( $column );

		/* @var Column $column */
		$column = new $class();
		$column->set_list_screen( $this )
		       ->set_type( $data['type'] );

		if ( isset( $data['name'] ) ) {
			$column->set_name( $data['name'] );
		}

		// Mark as original
		if ( $this->is_original_column( $data['type'] ) ) {
			$column->set_original( true );
			$column->set_name( $data['type'] );
		}

		$column->set_options( $data );

		return $column;
	}

	/**
	 * @since 3.0
	 *
	 * @param string $column_name Column name
	 */
	public function deregister_column( $column_name ) {
		unset( $this->columns[ $column_name ] );
	}

	/**
	 * @param array $data Column options
	 */
	protected function register_column( Column $column ) {
		$this->columns[ $column->get_name() ] = $column;

		/**
		 * Fires when a column is registered to a list screen, i.e. when it is created. Can be used
		 * to attach additional functionality to a column, such as exporting, sorting or filtering
		 *
		 * @since 3.0.5
		 *
		 * @param Column     $column      Column type object
		 * @param ListScreen $list_screen List screen object to which the column was registered
		 */
		do_action( 'ac/list_screen/column_registered', $column, $this );
	}

	/**
	 * @since 3.0
	 */
	private function set_columns() {

		foreach ( $this->get_settings() as $name => $data ) {
			$data['name'] = $name;
			if ( $column = $this->create_column( $data ) ) {
				$this->register_column( $column );
			}
		}

		// Nothing stored. Use WP default columns.
		if ( null === $this->columns ) {
			foreach ( $this->get_original_columns() as $type => $label ) {
				if ( $column = $this->create_column( array( 'type' => $type, 'original' => true ) ) ) {
					$this->register_column( $column );
				}
			}
		}

		if ( null === $this->columns ) {
			$this->columns = array();
		}
	}

	/**
	 * @param string $column_name
	 * @param int    $id
	 * @param null   $original_value
	 *
	 * @return string
	 */
	public function get_display_value_by_column_name( $column_name, $id, $original_value = null ) {
		$column = $this->get_column_by_name( $column_name );

		if ( ! $column ) {
			return $original_value;
		}

		$value = $column->get_value( $id );

		// You can overwrite the display value for original columns by making sure get_value() does not return an empty string.
		if ( $column->is_original() && ac_helper()->string->is_empty( $value ) ) {
			return $original_value;
		}

		/**
		 * Column display value
		 *
		 * @since 3.0
		 *
		 * @param string $value  Column display value
		 * @param int    $id     Object ID
		 * @param Column $column Column object
		 */
		$value = apply_filters( 'ac/column/value', $value, $id, $column );

		return $value;
	}

	/**
	 * Get default column headers
	 *
	 * @return array
	 */
	public function get_default_column_headers() {
		return array();
	}

	/**
	 * @return string
	 */
	protected function get_admin_url() {
		return admin_url( $this->get_screen_base() . '.php' );
	}

	/**
	 * @since 2.0
	 * @return string Link
	 */
	public function get_screen_link() {
		return add_query_arg( array( 'page' => $this->get_page(), 'layout' => $this->get_id() ), $this->get_admin_url() );
	}

	/**
	 * @since 2.0
	 */
	public function get_edit_link() {
		return add_query_arg( array( 'list_screen' => $this->get_type(), 'layout_id' => $this->get_id(), 'store_type' => $this->get_store_object()->get_store_type() ), AC()->admin_columns_screen()->get_link() );
	}

	/**
	 * @since 2.0.3
	 *
	 * @param \WP_Screen $screen
	 *
	 * @return boolean
	 */
	public function is_current_screen( $wp_screen ) {
		return $wp_screen && $wp_screen->id === $this->get_screen_id() && $wp_screen->base === $this->get_screen_base();
	}

	/**
	 * @return bool
	 */
	private function update() {

		$data = array(
			'columns' => $this->get_settings(),
			'id'      => $this->get_id(),
			'name'    => $this->get_custom_label(),
			'roles'   => $this->get_roles(),
			'users'   => $this->get_users(),
		);

		$result = $this->get_store_object()->update( $data );

		do_action_deprecated( 'ac/columns_stored', array( $this ), 'NEWVERSION' );

		do_action( 'ac/list_screen/update', $this, $result );

		return $result;
	}

	/**
	 * Delete stored data
	 */
	public function delete() {
		$this->get_store_object()->delete();

		do_action_deprecated( 'ac/columns_delete', array( $this ), 'NEWVERSION' );
		do_action_deprecated( 'ac/layout/delete', array( $this ), 'NEWVERSION' );

		do_action( 'ac/list_screen/delete', $this );
	}

	/**
	 * Save data
	 *
	 * @return bool
	 */
	public function save() {
		if ( null === $this->get_id() ) {
			$this->set_id( uniqid() );
		}

		return $this->update();
	}

	/**
	 * @return bool List Screen contains data
	 */
	public function exists() {
		return false !== $this->get_store_object()->read();
	}

	/**
	 * @deprecated NEWVERSION
	 * @return string
	 */
	public function get_key() {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'AC\ListScreen::get_type()' );

		return $this->get_type();
	}

	/**
	 * @deprecated NEWVERSION
	 *
	 * @param string $key
	 */
	protected function set_key( $key ) {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'AC\ListScreen::set_type()' );

		$this->set_type( $key );

		return $this;
	}

	/**
	 * @deprecated NEWVERSION
	 * @return array
	 */
	public function get_stored_default_headings() {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'AC\ListScreen::get_original_columns()' );

		return array();
	}

}