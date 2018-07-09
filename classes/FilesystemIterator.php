<?php

namespace AC;

class FilesystemIterator extends \FilesystemIterator {

	/**
	 * @return FileObject
	 */
	public function current() {
		return new FileObject( $this->key() );
	}

}