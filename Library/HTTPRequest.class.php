<?php

/*
 * Get Client Request
 */

namespace Library;

class HTTPRequest {
	public function GetData($key) {
		return isset($_GET[$key]) ? $_GET[$key] : null;
	}

	public function PostData($key) {
		return isset($_POST[$key]) ? $_POST[$key] : null;
	}

	public function RequestMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}

	public function URI() {
		return $_SERVER['REQUEST_URI'];
	}
}