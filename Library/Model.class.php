<?php

/*
 * Database connexion and execute request
 */

namespace Library;

abstract class Model {
	protected $Dbh;
	protected $tablePrefix;
	protected $table;

	// Process Db resquests
	protected function execSQL($sql, $params = null) {
		$data = $this->Db()->prepare($sql);
		if($params != null) {
			foreach ($params as $k => $v) {
				$data->bindValue($v['param'], $v['value'], $v['type']);
			}
		}
		$data->execute();	

		// DEBUG
		if(DEV) {
			$_SESSION['mysql']++;
		}

		return $data;
	}

	// Db connexion
	protected function Db() {
		if($this->Dbh==null) {
			$dbHost = Config::getSetting('dbHost');
			$dbName = Config::getSetting('dbName');
			$dbUser = Config::getSetting('dbUser');
			$dbPwd = Config::getSetting('dbPwd');
			$dsn = "mysql:host=".$dbHost.";dbname=".$dbName.";charset=utf8";

			/* Use \PDO::ATTR_EMULATE_PREPARES => false
			 * If you don't use bindValue()
			 * because :
			 * Bug #44639	PDO quotes integers in prepared statement 
			 */
			$this->Dbh = new \PDO($dsn, $dbUser, $dbPwd, array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
		}
		return $this->Dbh;
	}
}


/*

namespace Library;

abstract class Model {
	private static $dbh;
	protected $tablePrefix;
	protected $table;

	protected function execSQL($sql, $params = null) {
		if($params == null) {
			$data = self::Db()->query($sql);
		}
		else {
			$data = self::Db()->prepare($sql);
			foreach ($params as $k => $v) {
				$data->bindValue($v['param'], $v['value'], $v['type']);
			}
			$data->execute();
		}
		//$_SESSION['mysql']++;
		return $data;
	}

	protected static function Db() {
		if(self::$dbh==null) {
			$dbName = Config::get('dbName');
			$dbUser = Config::get('dbUser');
			$dbPwd = Config::get('dbPwd');
			$dbHost = Config::get('dbHost');
			$dsn = "mysql:host=".$dbHost.";dbname=".$dbName.";charset=utf8";

			/* Use \PDO::ATTR_EMULATE_PREPARES => false
			 * because :
			 * Bug #44639	PDO quotes integers in prepared statement 
			 
			self::$dbh = new \PDO($dsn, $dbUser, $dbPwd, array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
		}
		return self::$dbh;
	}
}

*/