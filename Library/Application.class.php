<?php

/**
 * Store Application Name
 */

namespace Library;

abstract class Application {
	protected $name;

	public function __construct($name) {
		$this->name = $name;
	}

	public abstract function run();

	// Get Application Name
	public function name() {
		return $this->name;
	}
}