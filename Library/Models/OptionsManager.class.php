<?php

/*
 * Get Options Data
 */

namespace Library\Models;

class OptionsManager extends \Library\Model {
	public function __construct($tablePrefix) {
		$this->tablePrefix = $tablePrefix;
		$this->table = $this->tablePrefix.'options';
	}

	public function options() {
		$sql = "SELECT * FROM ".$this->table;
		return $this->execSQL($sql);
	}	

	public function option($option) {
		$sql = "SELECT value FROM ".$this->table."
		WHERE name = :option";
		$params = array();
		$params[] = array('param' => ':option', 'value' => $option, 'type' => \PDO::PARAM_STR);
		return $this->execSQL($sql, $params);
	}	
}
