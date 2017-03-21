<?php

/**
 * Frontend Application
 * Start the Application
 * Call the Router
 */

namespace Applications\Frontend;

class FrontendApplication extends \Library\Application {
	// Call the Router
	public function run() {
		$Router = new \Library\Router($this);
		$Router->routeRequest();
	}
}