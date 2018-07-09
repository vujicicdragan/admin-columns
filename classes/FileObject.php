<?php

namespace AC;

class FileObject extends \SplFileObject {

	public function isPhpFile() {
		return 'php' === $this->getExtension();
	}

	public function getBasenameWithoutExtension() {
		return $this->getBasename( '.' . $this->getExtension() );
	}

}