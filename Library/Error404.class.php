<?php

/**
 * Handle error 404
 */

namespace Library;

class Error404 extends \Exception {
	// Send error 404, get message and display it
	public function getMsg() {
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
		echo parent::getMessage().'. Return to <a href="http://'.$_SERVER['SERVER_NAME'].'">'.Config::getSetting('siteName').'</a>';
		exit();
	}
}