<?php

namespace AC;

/**
 * List Screen
 *
 * @since 2.0
 */
abstract class ListScreen {

	const SETTINGS_KEY = 'cpac_layouts';

	const COLUMNS_KEY = 'cpac_options_';

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
	private $original_columns;

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
		$this->set_settings( get_option( self::COLUMNS_KEY . $this->get_storage_key(), array() ) );

		if ( $layout_data = get_option( self::SETTINGS_KEY . $this->get_storage_key() ) ) {

			$this->set_custom_label( $layout_data->name );
			$this->set_users( $layout_data->users );
			$this->set_roles( $layout_data->roles );
		}
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
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	protected function set_label( $label ) {
		$this->label = $label;
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
	 */
	protected function set_singular_label( $label ) {
		$this->singular_label = $label;
	}

	/**
	 * @return string
	 */
	public function get_meta_type() {
		return $this->meta_type;
	}

	/**
	 * @param string $meta_type
	 */
	protected function set_meta_type( $meta_type ) {
		$this->meta_type = $meta_type;
	}

	/**
	 * @return string
	 */
	public function get_screen_base() {
		return $this->screen_base;
	}

	/**
	 * @param string $screen_base
	 */
	protected function set_screen_base( $screen_base ) {
		$this->screen_base = $screen_base;
	}

	/**
	 * @return string
	 */
	public function get_screen_id() {
		return $this->screen_id;
	}

	/**
	 * @param string $screen_base
	 */
	protected function set_screen_id( $screen_id ) {
		$this->screen_id = $screen_id;
	}

	/**
	 * @return string
	 */
	public function get_page() {
		return $this->page;
	}

	/**
	 * @param string $screen_base
	 */
	protected function set_page( $page ) {
		$this->page = $page;
	}

	/**
	 * @return string
	 */
	public function get_group() {
		return $this->group;
	}

	/**
	 * @param string $screen_base
	 */
	public function set_group( $group ) {
		$this->group = $group;
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
	 */
	public function set_read_only( $read_only ) {
		$this->read_only = (bool) $read_only;
	}

	/**
	 * Settings can not be overwritten
	 */
	public function is_network_only() {
		return $this->network_only;
	}

	/**
	 * @param bool $network_only
	 */
	public function set_network_only( $network_only ) {
		$this->network_only = (bool) $network_only;
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
		$this->roles = $roles;

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
		$this->users = $users;

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
	 * @param string $type
	 *
	 * @return false|string
	 */
	private function get_class_by_type( $type ) {
		$column = $this->get_column_by_type( $type );

		if ( ! $column ) {
			return false;
		}

		return get_class( $column );
	}

	/**
	 * @param string $type Column type
	 */
	public function deregister_column_type( $type ) {
		if ( isset( $this->column_types[ $type ] ) ) {
			unset( $this->column_types[ $type ] );
		}
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
	 * @return array
	 */
	public function get_original_columns() {
		if ( null === $this->original_columns ) {

			// TODO: add to update
			$this->set_original_columns( ListScreenStore::get_default_headings( $this ) );
		}

		return $this->original_columns;
	}

	/**
	 * @param array $columns
	 */
	public function set_original_columns( $columns ) {
		$this->original_columns = (array) $columns;
	}

	/**
	 * Available column types
	 */
	private function set_column_types() {
		$this->column_types = array();

		// Register default columns
		foreach ( $this->get_original_columns() as $type => $label ) {

			// Ignore the mandatory checkbox column
			if ( 'cb' === $type ) {
				continue;
			}

			$column = new Column();

			$column
				->set_type( $type )
				->set_original( true );

			$this->register_column_type( $column );
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
	 * @param string $dir Absolute path to the column directory
	 */
	public function register_column_types_from_dir( $dir ) {
		$classes = Autoloader::instance()->get_class_names_from_dir( $dir );

		foreach ( $classes as $class ) {
			$this->register_column_type( new $class );
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
	 * @param array $settings Column options
	 *
	 * @return Column|false
	 */
	public function create_column( array $settings ) {
		if ( ! isset( $settings['type'] ) ) {
			return false;
		}

		$class = $this->get_class_by_type( $settings['type'] );

		if ( ! $class ) {
			return false;
		}

		/* @var Column $column */
		$column = new $class();

		$column->set_list_screen( $this )
		       ->set_type( $settings['type'] );

		if ( isset( $settings['name'] ) ) {
			$column->set_name( $settings['name'] );
		}

		// Mark as original
		if ( $this->is_original_column( $settings['type'] ) ) {

			$column->set_original( true );
			$column->set_name( $settings['type'] );
		}

		$column->set_options( $settings );

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
		// TODO: only on columns settings page?
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
	// TODO: only on columns page?
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
		return add_query_arg( array( 'list_screen' => $this->get_type(), 'layout_id' => $this->get_id() ), AC()->admin_columns_screen()->get_link() );
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
	 * Delete stored data
	 */
	public function delete() {
		delete_option( self::COLUMNS_KEY . $this->get_storage_key() );
		delete_option( self::SETTINGS_KEY . $this->get_storage_key() );

		do_action_deprecated( 'ac/columns_delete', array( $this ), 'NEWVERSION' );
		do_action_deprecated( 'ac/layout/delete', array( $this ), 'NEWVERSION' );

		do_action( 'ac/list_screen/delete', $this );
	}

	/**
	 * @return bool
	 */
	private function update() {

		$data = array(
			'id'    => $this->get_id(),
			'name'  => $this->get_custom_label(),
			'roles' => $this->get_roles(),
			'users' => $this->get_users(),
		);

		update_option( self::SETTINGS_KEY . $this->get_storage_key(), (object) $data );
		$result = update_option( self::COLUMNS_KEY . $this->get_storage_key(), (array) $this->get_settings() );

		do_action_deprecated( 'ac/columns_stored', array( $this ), 'NEWVERSION' );

		do_action( 'ac/list_screen/update', $this, $result );

		return $result;
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
	}

	/**
	 * @deprecated NEWVERSION
	 * @return array
	 */
	public function get_stored_default_headings() {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'ListScreenStore::get_default_headings()' );

		return array();
	}

}