<?php

/*
 * Get View Data
 */

namespace Library\Models;

class ViewManager extends \Library\Model {
	public function __construct($tablePrefix) {
		$this->tablePrefix = $tablePrefix;
	}

	public function templateMenu() {
		$sql = "SELECT menu.name, menu.label, menu.url AS menuUrl, categories.url AS categoryUrl FROM ".$this->tablePrefix."menus AS menu
		LEFT JOIN ".$this->tablePrefix."categories AS categories ON categories.id_PK = menu.categoryId_FK
		WHERE menu.groupId = 1 AND menu.lft > 1
		ORDER BY menu.lft ASC";
		return $this->execSQL($sql);
	}	
}
