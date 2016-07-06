<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_Excerpt extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-excerpt';
		$this->properties['label'] = __( 'Excerpt', 'codepress-admin-columns' );
		$this->properties['object_property'] = 'post_excerpt';

		$this->options['excerpt_length'] = 30;
	}

	public function get_value( $post_id ) {
		$value = ac_helper()->post->excerpt( $post_id, $this->get_option( 'excerpt_length' ) );
		if ( ! has_excerpt( $post_id ) && $value ) {
			$value = '<span class="cpac-inline-info">' . __( 'Excerpt from content', 'codepress-admin-columns' ) . '</span> ' . $value;
		}

		return $value;
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_excerpt', $post_id, 'raw' );
	}

	public function display_settings() {
		$this->display_field_word_limit();
	}

}