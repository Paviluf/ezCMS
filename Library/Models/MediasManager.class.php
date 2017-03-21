<?php

/*
 * Get Medias data
 */

namespace Library\Models;

class MediasManager extends \Library\Model {
	public function __construct($tablePrefix) {
		$this->tablePrefix = $tablePrefix;
		$this->table = $this->tablePrefix.'medias';
	}

	public function getNbOfAllMedias() {
		$sql = "SELECT COUNT(*) FROM ".$this->table;
		return $this->execSQL($sql);
	}

	public function getNbOfAllActiveMedias() {
		$sql = "SELECT COUNT(*) FROM ".$this->table."
		WHERE status = ".ACTIVE;
		return $this->execSQL($sql);
	}

	public function getAllMediasInRange($startMedia, $nbOfMediasPerPage) {
		$sql = "SELECT medias.* FROM ".$this->table." AS medias
			ORDER BY GREATEST(medias.dateModified, medias.dateCreated) DESC, medias.id_PK DESC
			LIMIT :startMedia, :nbOfMediasPerPage";
		$params = array();
		$params[] = array('param' => ':startMedia', 'value' => intval($startMedia), 'type' => \PDO::PARAM_INT);
		$params[] = array('param' => ':nbOfMediasPerPage', 'value' => intval($nbOfMediasPerPage), 'type' => \PDO::PARAM_INT);		
		return $this->execSQL($sql, $params);
	}

	public function getMedia($mediaId) {
		$sql = "SELECT * FROM ".$this->table." AS medias 
			WHERE medias.id_PK = :mediaId";
		$params = array();
		$params[] = array('param' => ':mediaId', 'value' => intval($mediaId), 'type' => \PDO::PARAM_INT);	
		return $this->execSQL($sql, $params);	
	}

	public function newMedia($mediaId) {
		
	}

	public function updateMedia($mediaId) {

	}

	public function deleteMedia($mediaId) {
		
	}
}