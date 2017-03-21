<?php

/**
 * Get user
 */

namespace Library\Models;

class LoginManager extends \Library\Model {
	public function __construct($tablePrefix) {
		$this->tablePrefix = $tablePrefix;
		$this->table = $this->tablePrefix.'users';
	}

	public function isUser() {
		$sql = "SELECT name FROM ".$this->table." WHERE name=:user AND pwd=:pwd AND status = ".ACTIVE;
		$params = array();
		$params[] = array('param' => ':user', 'value' => mb_strtolower($_POST['user']), 'type' => \PDO::PARAM_STR);
		$params[] = array('param' => ':pwd', 'value' => sha1(SALT.md5($_POST['pwd'].SALT).sha1(SALT)), 'type' => \PDO::PARAM_STR);
		return $this->execSQL($sql, $params);	
	}
}