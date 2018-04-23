<?php

namespace AC\Table;

use AC\Admin;
use AC\Capabilities;
use AC\Column;
use AC\Layout;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\Preferences;
use AC\Settings;

final class Screen {

	/**
	 * @var ListScreen;
	 */
	private $list_screen;

	public function __construct() {
		add_action( 'current_screen', array( $this, 'load_list_screen' ) );
		add_action( 'admin_init', array( $this, 'load_list_screen_doing_quick_edit' ) );
		add_filter( 'list_table_primary_column', array( $this, 'set_primary_column' ), 20 );
		add_action( 'wp_ajax_ac_get_column_value', array( $this, 'ajax_get_column_value' ) );

		// Layout
		add_action( 'admin_footer', array( $this, 'screen_switcher' ) );
		add_action( 'admin_init', array( $this, 'handle_request' ) );

		// Scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer_scripts' ) );
		add_action( 'admin_head', array( $this, 'admin_head_scripts' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
	}

	/**
	 * Load current list screen
	 *
	 * @param \WP_Screen $wp_screen
	 */
	public function load_list_screen( $wp_screen ) {
		if ( ! $wp_screen instanceof \WP_Screen ) {
			return;
		}

		$list_screen = ListScreenFactory::create_by_screen( $wp_screen );

		if ( ! $list_screen ) {
			return;
		}

		$this->set_list_screen( $list_screen );
	}

	/**
	 * Runs when doing Quick Edit, a native WordPress ajax call
	 */
	public function load_list_screen_doing_quick_edit() {
		if ( ! AC()->is_doing_ajax() ) {
			return;
		}

		switch ( filter_input( INPUT_POST, 'action' ) ) {

			case 'inline-save' :
				// Quick edit post
				$this->set_list_screen( ListScreenFactory::create( filter_input( INPUT_POST, 'post_type' ) ) );

				break;
			case 'add-tag' :
			case 'inline-save-tax' :
				// Adding term & Quick edit term
				$this->set_list_screen( ListScreenFactory::create( 'wp-taxonomy_' . filter_input( INPUT_POST, 'taxonomy' ) ) );

				break;
			case 'edit-comment' :
			case 'replyto-comment' :
				// Quick edit comment & Inline reply on comment
				$this->set_list_screen( ListScreenFactory::create( 'wp-comments' ) );

				break;
		}
	}

	/**
	 * Handle Requests: Store visited layout as a preference
	 */
	public function handle_request() {
		$layout = filter_input( INPUT_GET, 'layout', FILTER_SANITIZE_STRING );

		if ( ! $layout ) {
			return;
		}

		$list_screen = filter_input( INPUT_GET, 'list_screen', FILTER_SANITIZE_STRING );

		if ( empty( $list_screen ) ) {
			return;
		}

		$list_screen = ListScreenFactory::create( filter_input( INPUT_GET, 'list_screen', FILTER_SANITIZE_STRING ) );

		if ( ! $list_screen ) {
			return;
		}

		$layouts = ac_layouts( $list_screen );

		$layout = $layouts->get_layout_by_id( $layout );

		if ( ! $layout ) {
			return;
		}

		$this->preferences()->set( $list_screen->get_key(), $layout->get_id() );
	}

	/**
	 * @since 4.0.12
	 * @return \AC\Preferences
	 */
	public function preferences() {
		return new Preferences\Site( 'layout_table' );
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return false|Layout
	 */
	public function get_user_preference( $list_screen ) {
		$layout_id = $this->preferences()->get( $list_screen->get_key() );

		$layout = ac_layouts( $list_screen )->get_layout_by_id( $layout_id );

		if ( ! $layout ) {
			return false;
		}

		if ( ! $layout->is_current_user_eligible() ) {
			return false;
		}

		return $layout;
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return string
	 */
	private function get_layout_id( ListScreen $list_screen ) {
		$layouts = ac_layouts( $list_screen );

		// Current user layouts
		if ( $layouts->get_layouts_for_current_user() ) {

			// TODO: simplify
			$layout = $this->get_user_preference( $list_screen );

			// when no longer available use the first user layout
			if ( ! $layout ) {
				$layout = $layouts->get_first_layout_for_current_user();
			}

			$id = $layout->get_id();
		} else if ( $layouts->get_layout_by_id( null ) ) {
			// User doesn't have eligible layouts.. but the current (null) layout does exists, then the WP default columns are loaded
			// _wp_default_ does not exists therefor will load WP default
			$id = '_wp_default_';
		}

		return $id;
	}

	/**
	 * Get column value by ajax.
	 */
	public function ajax_get_column_value() {
		check_ajax_referer( 'ac-ajax' );

		// Get ID of entry to edit
		$id = intval( filter_input( INPUT_POST, 'pk' ) );

		if ( ! $id ) {
			$this->ajax_error( __( 'Invalid item ID.', 'codepress-admin-columns' ) );
		}

		$list_screen = ListScreenFactory::create( filter_input( INPUT_POST, 'list_screen' ), filter_input( INPUT_POST, 'layout' ) );

		if ( ! $list_screen ) {
			$this->ajax_error( __( 'Invalid list screen.', 'codepress-admin-columns' ) );
		}

		$column = $list_screen->get_column_by_name( filter_input( INPUT_POST, 'column' ) );

		if ( ! $column ) {
			$this->ajax_error( __( 'Invalid column.', 'codepress-admin-columns' ) );
		}

		if ( ! $column instanceof Column\AjaxValue ) {
			$this->ajax_error( __( 'Invalid method.', 'codepress-admin-columns' ) );
		}

		// Trigger ajax callback
		echo $column->get_ajax_value( $id );
		exit;
	}

	/**
	 * @param string $message
	 */
	private function ajax_error( $message ) {
		wp_die( $message, null, 400 );
	}

	/**
	 * Set the primary columns for the Admin Columns columns. Used to place the actions bar.
	 *
	 * @since 2.5.5
	 */
	public function set_primary_column( $default ) {
		$list_screen = $this->get_list_screen();

		if ( $list_screen ) {

			if ( ! $list_screen->get_column_by_name( $default ) ) {
				$default = key( $list_screen->get_columns() );
			}

			// If actions column is present, set it as primary
			foreach ( $list_screen->get_columns() as $column ) {

				if ( 'column-actions' == $column->get_type() ) {
					$default = $column->get_name();

					if ( $list_screen instanceof ListScreen\Media ) {

						// Add download button to the actions column
						add_filter( 'media_row_actions', array( $this, 'set_media_row_actions' ), 10, 2 );
					}
				}
			};

			// Set inline edit data if the default column (title) is not present
			if ( $list_screen instanceof ListScreen\Post && 'title' !== $default ) {
				add_filter( 'page_row_actions', array( $this, 'set_inline_edit_data' ), 20, 2 );
				add_filter( 'post_row_actions', array( $this, 'set_inline_edit_data' ), 20, 2 );
			}

			// Remove inline edit action if the default column (author) is not present
			if ( $list_screen instanceof ListScreen\Comment && 'comment' !== $default ) {
				add_filter( 'comment_row_actions', array( $this, 'remove_quick_edit_from_actions' ), 20, 2 );
			}
		}

		return $default;
	}

	/**
	 * Add a download link to the table screen
	 *
	 * @param array    $actions
	 * @param \WP_Post $post
	 */
	public function set_media_row_actions( $actions, $post ) {
		$link_attributes = array(
			'download' => '',
			'title'    => __( 'Download', 'codepress-admin-columns' ),
		);
		$actions['download'] = ac_helper()->html->link( wp_get_attachment_url( $post->ID ), __( 'Download', 'codepress-admin-columns' ), $link_attributes );

		return $actions;
	}

	/**
	 * Sets the inline data when the title columns is not present on a AC_ListScreen_Post screen
	 *
	 * @param array    $actions
	 * @param \WP_Post $post
	 */
	public function set_inline_edit_data( $actions, $post ) {
		get_inline_data( $post );

		return $actions;
	}

	/**
	 * Remove quick edit from actions
	 *
	 * @param array $actions
	 */
	public function remove_quick_edit_from_actions( $actions ) {
		unset( $actions['quickedit'] );

		return $actions;
	}

	/**
	 * Adds a body class which is used to set individual column widths
	 *
	 * @since 1.4.0
	 *
	 * @param string $classes body classes
	 *
	 * @return string
	 */
	public function admin_class( $classes ) {
		$list_screen = $this->get_list_screen();

		if ( ! $list_screen ) {
			return $classes;
		}

		$classes .= " ac-" . $list_screen->get_key();

		return apply_filters( 'ac/table/body_class', $classes, $this );
	}

	public function init_list_screen() {

	}

	/**
	 * @param ListScreen $list_screen
	 */
	private function set_list_screen( ListScreen $list_screen ) {
		// Load layout
		$list_screen = ListScreenFactory::create( $list_screen->get_key(), $this->get_layout_id( $list_screen ) );

		// Load headings
		$this->load_headings( $list_screen );

		// Load values
		$list_screen->set_manage_value_callback();

		/**
		 * @since 3.0
		 *
		 * @param ListScreen
		 */
		do_action( 'ac/table/list_screen', $list_screen );

		$this->list_screen = $list_screen;
	}

	/**
	 * @param \WP_Screen $screen
	 *
	 * @return ListScreen|false
	 */
	public function get_list_screen() {
		return $this->list_screen;
	}

	/**
	 * @since 2.2.4
	 *
	 * @param ListScreen $list_screen
	 */
	public function admin_scripts() {
		$list_screen = $this->get_list_screen();

		if ( ! $list_screen ) {
			return;
		}

		// Tooltip
		wp_register_script( 'jquery-qtip2', AC()->get_plugin_url() . "external/qtip2/jquery.qtip.min.js", array( 'jquery' ), AC()->get_version() );
		wp_enqueue_style( 'jquery-qtip2', AC()->get_plugin_url() . "external/qtip2/jquery.qtip.min.css", array(), AC()->get_version() );

		// Main
		wp_enqueue_script( 'ac-table', AC()->get_plugin_url() . "assets/js/table.js", array( 'jquery', 'jquery-qtip2' ), AC()->get_version() );
		wp_enqueue_style( 'ac-table', AC()->get_plugin_url() . "assets/css/table.css", array(), AC()->get_version() );

		wp_localize_script( 'ac-table', 'AC', array(
				'list_screen'  => $list_screen->get_key(),
				'layout'       => $list_screen->get_layout_id(),
				'column_types' => $this->get_column_types_mapping( $list_screen ),
				'ajax_nonce'   => wp_create_nonce( 'ac-ajax' ),
				'table_id'     => $list_screen->get_table_attr_id(),
				'edit_link'    => $this->get_edit_link( $list_screen ),
				'screen'       => get_current_screen() ? get_current_screen()->id : false,
				'i18n'         => array(
					'edit_columns' => esc_html( __( 'Edit columns', 'codepress-admin-columns' ) ),
				),
			)
		);

		/**
		 * @param ListScreen $list_screen
		 */
		do_action( 'ac/table_scripts', $list_screen );

		// Column specific scripts
		foreach ( $list_screen->get_columns() as $column ) {
			$column->scripts();
		}
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return array
	 */
	private function get_column_types_mapping( ListScreen $list_screen ) {
		$types = array();
		foreach ( $list_screen->get_columns() as $column ) {
			$types[ $column->get_name() ] = $column->get_type();
		}

		return $types;
	}

	/**
	 * Applies the width setting to the table headers
	 */
	private function display_width_styles() {
		$list_screen = $this->get_list_screen();

		if ( ! $list_screen || ! $list_screen->get_settings() ) {
			return;
		}

		// CSS: columns width
		$css_column_width = false;

		foreach ( $list_screen->get_columns() as $column ) {
			/* @var Settings\Column\Width $setting */
			$setting = $column->get_setting( 'width' );

			if ( $width = $setting->get_display_width() ) {
				$css_column_width .= ".ac-" . esc_attr( $list_screen->get_key() ) . " .wrap table th.column-" . esc_attr( $column->get_name() ) . " { width: " . $width . " !important; }";
				$css_column_width .= "body.acp-overflow-table.ac-" . esc_attr( $list_screen->get_key() ) . " .wrap th.column-" . esc_attr( $column->get_name() ) . " { min-width: " . $width . " !important; }";
			}
		}

		if ( ! $css_column_width ) {
			return;
		}

		?>

		<style>
			@media screen and (min-width: 783px) {
			<?php echo $css_column_width; ?>
			}
		</style>

		<?php
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return string|false
	 */
	private function get_edit_link( ListScreen $list_screen ) {
		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return false;
		}

		/* @var Admin\Page\Settings $settings */
		$settings = AC()->admin()->get_page( 'settings' );

		if ( ! $settings->show_edit_button() ) {
			return false;
		}

		return $list_screen->get_edit_link();
	}

	/**
	 * Admin header scripts
	 *
	 * @since 3.1.4
	 */
	public function admin_head_scripts() {
		$list_screen = $this->get_list_screen();

		if ( ! $list_screen ) {
			return;
		}

		$this->display_width_styles();

		/**
		 * Add header scripts that only apply to column screens.
		 *
		 * @since 3.1.4
		 *
		 * @param ListScreen
		 * @param Screen
		 */
		do_action( 'ac/admin_head', $list_screen, $this );
	}

	/**
	 * Admin footer scripts
	 *
	 * @since 1.4.0
	 */
	public function admin_footer_scripts() {
		$list_screen = $this->get_list_screen();

		if ( ! $list_screen ) {
			return;
		}

		/**
		 * Add footer scripts that only apply to column screens.
		 *
		 * @since 2.3.5
		 *
		 * @param ListScreen
		 * @param Screen
		 */
		do_action( 'ac/admin_footer', $list_screen, $this );
	}

	/**
	 * Load column values and headings
	 */
	public function load_screen() {
		$list_screen = $this->get_list_screen();

		if ( ! $list_screen ) {
			return;
		}

		$list_screen->set_manage_value_callback();

		$this->load_headings( $list_screen );

		/**
		 * @since 3.0
		 *
		 * @param ListScreen
		 */
		do_action( 'ac/table/list_screen', $list_screen );
	}

	/**
	 * Load column headings & column values
	 *
	 * @param ListScreen $list_screen
	 */
	private function load_headings( ListScreen $list_screen ) {
		add_filter( "manage_" . $list_screen->get_screen_id() . "_columns", array( $this, 'add_headings' ), 200 );
	}

	/**
	 * @since 2.0
	 */
	public function add_headings( $columns ) {
		static $headings;

		if ( empty( $columns ) ) {
			return $columns;
		}

		$list_screen = $this->get_list_screen();

		if ( ! $list_screen ) {
			return $columns;
		}

		// Store default headings
		if ( ! AC()->is_doing_ajax() ) {
			$list_screen->save_default_headings( $columns );
		}

		// Run once
		if ( $headings ) {
			return $headings;
		}

		// Nothing stored. Show default columns on screen.
		if ( ! $list_screen->get_settings() ) {
			return $columns;
		}

		// Add mandatory checkbox
		if ( isset( $columns['cb'] ) ) {
			$headings['cb'] = $columns['cb'];
		}

		// On first visit 'columns' can be empty, because they were put in memory before 'default headings'
		// were stored. We force get_columns() to be re-populated.
		if ( ! $list_screen->get_columns() ) {
			$list_screen->reset();
			$list_screen->reset_original_columns();
		}

		foreach ( $list_screen->get_columns() as $column ) {

			/**
			 * @since 3.0
			 *
			 * @param string $label
			 * @param Column $column
			 */
			$label = apply_filters( 'ac/headings/label', $column->get_setting( 'label' )->get_value(), $column );

			$headings[ $column->get_name() ] = $label;
		}

		return apply_filters( 'ac/headings', $headings, $list_screen );
	}

	/**
	 * Switcher on listing screen
	 */
	public function screen_switcher() {
		$list_screen = $this->get_list_screen();

		if ( ! $list_screen ) {
			return;
		}

		$link = $list_screen->get_screen_link();

		if ( $post_status = filter_input( INPUT_GET, 'post_status', FILTER_SANITIZE_STRING ) ) {
			$link = add_query_arg( array( 'post_status' => $post_status ), $link );
		}

		if ( $author = filter_input( INPUT_GET, 'author', FILTER_SANITIZE_STRING ) ) {
			$link = add_query_arg( array( 'author' => $author ), $link );
		}

		$layouts = ac_layouts( $list_screen )->get_layouts_for_current_user();

		if ( count( $layouts ) > 1 ) : ?>
			<form class="layout-switcher">
				<label for="column-view-selector" class="label screen-reader-text">
					<?php _e( 'Switch View', 'codepress-admin-columns' ); ?>
				</label>
				<span class="spinner"></span>
				<select id="column-view-selector" name="layout" <?php echo ac_helper()->html->get_tooltip_attr( __( 'Switch View', 'codepress-admin-columns' ) ); ?>>
					<?php foreach ( $layouts as $layout ) : ?>
						<option value="<?php echo add_query_arg( array( 'layout' => $layout->get_id(), 'list_screen' => $list_screen->get_key() ), $link ); ?>"<?php selected( $layout->get_id(), $list_screen->get_layout_id() ); ?>><?php echo esc_html( $layout->get_name() ); ?></option>
					<?php endforeach; ?>
				</select>
				<script type="text/javascript">
					jQuery( document ).ready( function( $ ) {
						$( '.layout-switcher' ).change( function() {
							var _select = $( this ).addClass( 'loading' ).find( 'select' ).attr( 'disabled', 1 );
							window.location = _select.val();
						} );
					} );
				</script>
			</form>
		<?php
		endif;
	}

}