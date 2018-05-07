<?php

/**
 * Pro only column
 *
 * @since NEWVERSION
 */
class AC_Column_Pro extends AC_Column
	implements AC_Column_PlaceholderInterface {

	public function __construct() {
		$this->set_group( 'pro' );
	}

	public function get_label() {
		$label = parent::get_label();
		$label .= ' (PRO)';

		return $label;
	}

	public function get_message() {
		ob_start();
		?>

		<p>
			<strong><?php printf( __( "This column is only available in Admin Columns Pro.", 'codepress-admin-columns' ), $this->get_label() ); ?></strong>
		</p>

		<p>
			<a href="<?php echo esc_url( ac_get_site_utm_url( 'admin-columns-pro', 'pro-column-message' ) ); ?>"><?php _e( 'Learn more about Pro', 'codepress-admin-columns' ); ?></a>
		</p>

		<?php

		return ob_get_clean();
	}

}