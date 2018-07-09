<?php

namespace AC\Asset;

interface Enqueueable {

	/**
	 * Get the handle that is being used for enqueueing
	 *
	 * @return string Handle under which to enqueue
	 */
	public function get_handle();

	/**
	 * Register an enqueueable object
	 *
	 * @return void
	 */
	public function register();

	/**
	 * Enqueue an enqueueable object
	 *
	 * @return void
	 */
	public function enqueue();
}