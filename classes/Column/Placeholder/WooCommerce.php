<?php

namespace AC\Column\Placeholder;

use AC\Admin\Addon;
use AC\Column;

/**
 * @since 2.4.7
 */
class WooCommerce extends Column\Placeholder {

	protected function get_addon() {
		return new Addon\WooCommerce();
	}

	public function is_valid() {
		return parent::is_valid() && in_array( $this->get_post_type(), array( 'product', 'shop_order', 'shop_coupon' ) );
	}

}