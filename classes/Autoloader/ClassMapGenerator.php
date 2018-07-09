<?php

namespace AC\Autoloader;

use AC\FileObject;
use FilesystemIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use SplFileInfo;

class ClassMapGenerator {

	/**
	 * @param string $prefix
	 * @param string $path Absolute path of the prefix
	 * @param string $root Root dir for the prefix
	 *
	 * @throws \ReflectionException
	 */
	public function generate( $prefix, $path, $root ) {
		$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path, FilesystemIterator::SKIP_DOTS ) );
		$classmap = array();

		// normalize paths and prefix
		$prefix = rtrim( $prefix, '\\' );
		$path = rtrim( $path, '/' );
		$root = rtrim( $root, '/' );

		foreach ( $iterator as $leaf ) {
			$leaf = new FileObject( (string) $leaf );

			if ( ! $leaf->isPhpFile() ) {
				continue;
			}

			$namespace = $prefix . $this->replace_path( $path, $leaf ) . $leaf->getBasenameWithoutExtension();
			$namespace = str_replace( '/', '\\', $namespace );

			$file = $this->replace_path( $root, $leaf ) . $leaf->getBasename();

			$r = new \ReflectionClass( $namespace );

			if ( $r->getName() === $namespace ) {
				$classmap[ $namespace ] = $file;
			}
		}

		return $classmap;
	}

	/**
	 * @param string      $path
	 * @param SplFileInfo $leaf
	 *
	 * @return string
	 */
	private function replace_path( $path, SplFileInfo $leaf ) {
		return str_replace( $path, '', $leaf->getPath() ) . '/';
	}

}