<?php

namespace AC\Column\Placeholder;

use AC\Admin\Addon;
use AC\Column;

class BuddyPress extends Column\Placeholder {

	protected function get_addon() {
		return new Addon\BuddyPress();
	}

}