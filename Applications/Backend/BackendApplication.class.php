<?php

/*
 * Application
 * Run the application
 */



namespace Applications\Backend;

//$_SESSION = array();

class BackendApplication extends \Library\Application {
	public function run() {
		if(isset($_SESSION['logged']) && !empty($_SESSION['logged'])) {
			$Router = new \Library\Router($this);
			$Router->routeRequest();
		}
		else {
			$this->login();
		}
	}

	public function login() { 
		$Login = new \Library\Login();
		$user = $Login->login();
		if($user) {
			$_SESSION['logged'] = $user;
			$this->run();
		}
	}
}