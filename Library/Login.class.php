<?php

namespace Library;

class Login {
	protected $Login;

	public function __construct() {
		$this->Login = new \Library\Models\LoginManager(\Library\Config::getSetting('tablePrefix'));
	}

	public function login() {
		if(!isset($_POST['login'])) {
			echo '<form method="post" action="" >
			<input type="text" name="user" />
			<input type="password" name="pwd" />
			<input type="submit" name="login" />
			</form>';
		}
		else {
			if(isset($_POST['user']) && !empty($_POST['user']) && isset($_POST['pwd']) && !empty($_POST['pwd'])) {
				return $this->Login->isUser()->fetch()['name'];
			}
		}
	}
}