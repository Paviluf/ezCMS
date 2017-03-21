<?php
/**
 * Installer
 *
 * Create database
 * Tables :
 * 		categories
 *		options
 *		menus
 *		users
 *		medias
 *		postsMultiPages
 *		posts
 *		postsCategories
 *		comments
 */

// Tables
$tables = array(
		$tablePrefix."categories" => array(
	"id_PK" => "INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
	"lft" => "INT UNSIGNED NOT NULL",
	"rgt" => "INT UNSIGNED NOT NULL",
	"name" => "VARCHAR(255) NOT NULL UNIQUE KEY",
	"label" => "VARCHAR(255) NOT NULL",
	"url" => "VARCHAR(255) NOT NULL",
	"status" => "TINYINT UNSIGNED NOT NULL"),

		$tablePrefix."options" => array(
	"id_PK" => "INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
	"name" => "VARCHAR(255) NOT NULL",
	"label" => "VARCHAR(255) NOT NULL",
	"value" => "VARCHAR(255) NOT NULL"),

		$tablePrefix."menus" => array(
	"id_PK" => "INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
	"groupId" => "INT UNSIGNED NOT NULL",
	"lft" => "INT UNSIGNED NOT NULL",
	"rgt" => "INT UNSIGNED NOT NULL",
	"name" => "VARCHAR(255) NOT NULL",
	"label" => "VARCHAR(255) NOT NULL",
	"categoryId_FK" => "INT UNSIGNED NOT NULL",
	"url" => "VARCHAR(255) NOT NULL"),

		$tablePrefix."users" => array(
	"id_PK" => "INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
	"name" => "VARCHAR(255) NOT NULL",
	"pwd" => "VARCHAR(255) NOT NULL",
	"registrationDate" => "DATETIME NOT NULL",
	"status" => "TINYINT UNSIGNED NOT NULL",
	"type" => "VARCHAR(255) NOT NULL",
	"permissions" => "TINYINT UNSIGNED NOT NULL",
	"mail" => "VARCHAR(255) NOT NULL"),

		$tablePrefix."medias" => array(
	"id_PK" => "INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
	"filePath" => "VARCHAR(255) NOT NULL",
	"fileName" => "VARCHAR(255) NOT NULL",
	"version" => "VARCHAR(255) NOT NULL",
	"type" => "VARCHAR(255) NOT NULL",
	"source" => "VARCHAR(255) NOT NULL",
	"sourceUrl" => "VARCHAR(255) NOT NULL",
	"userId_FK" => "INT UNSIGNED NOT NULL",	
	"status" => "VARCHAR(255) NOT NULL",
	"dateCreated" => "DATETIME NOT NULL",
	"dateModified" => "DATETIME NOT NULL",
	"sql" => "CONSTRAINT FK_".$tablePrefix."users_id".$tablePrefix."medias FOREIGN KEY (userId_FK) REFERENCES ".$tablePrefix."users(id_PK)"),

	$tablePrefix."postsMultiPages" => array(
	"id_PK" => "INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
	"name" => "VARCHAR(255) NOT NULL",
	"label" => "VARCHAR(255) NOT NULL"),
		
		$tablePrefix."posts" => array(
	"id_PK" => "INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
	"lft" => "INT UNSIGNED NOT NULL",
	"rgt" => "INT UNSIGNED NOT NULL",
	"postMultiPagesId_FK" => "INT UNSIGNED NOT NULL",
	"userId_FK" => "INT UNSIGNED NOT NULL",
	"title" => "VARCHAR(255) NOT NULL",
	"content" => "TEXT NOT NULL",
	"nbOfWords" => "INT UNSIGNED NOT NULL",
	"pageTitle" => "VARCHAR(255) NOT NULL",
	"pageDescription" => "VARCHAR(255) NOT NULL",
	"imageId" => "INT UNSIGNED NOT NULL",
	"slug" => "VARCHAR(255) NOT NULL",
	"source" => "VARCHAR(255) NOT NULL",
	"sourceUrl" => "VARCHAR(255) NOT NULL",
	"views" => "INT UNSIGNED NOT NULL",
	"status" => "VARCHAR(255) NOT NULL",
	"dateCreated" => "DATETIME NOT NULL",
	"dateModified" => "DATETIME NOT NULL",
	"commentStatus" => "TINYINT UNSIGNED NOT NULL",
	"commentCount" => "INT UNSIGNED NOT NULL",
	"sql" => "CONSTRAINT FK_".$tablePrefix."postsMultiPages_id".$tablePrefix."posts FOREIGN KEY (postsMultiPagesId_FK) REFERENCES ".$tablePrefix."postsMultiPages(id_PK)",
	"sql" => "CONSTRAINT FK_".$tablePrefix."users_id".$tablePrefix."posts FOREIGN KEY (userId_FK) REFERENCES ".$tablePrefix."users(id_PK)"),

		$tablePrefix."postsCategories" => array(
	"id_PK" => "INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
	"postId_FK" => "INT UNSIGNED NOT NULL",
	"postsMultiPagesId_FK" => "INT UNSIGNED NOT NULL",
	"categoryId_FK" => "INT UNSIGNED NOT NULL",
	"sql" => "CONSTRAINT FK_".$tablePrefix."posts_id".$tablePrefix."postsCategories FOREIGN KEY (postId_FK) REFERENCES ".$tablePrefix."posts(id_PK)",
	"sql" => "CONSTRAINT FK_".$tablePrefix."postsMultiPages_id".$tablePrefix."postsCategories FOREIGN KEY (postsMultiPagesId_FK) REFERENCES ".$tablePrefix."postsMultiPages(id_PK)",
	"sql" => "CONSTRAINT FK_".$tablePrefix."categories_id".$tablePrefix."postsCategories FOREIGN KEY (categoryId_FK) REFERENCES ".$tablePrefix."categories(id_PK)"),

		$tablePrefix."comments" => array(
	"id_PK" => "INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
	"postId_FK" => "INT UNSIGNED NOT NULL",
	"lft" => "INT UNSIGNED NOT NULL",
	"rgt" => "INT UNSIGNED NOT NULL",
	"userId_FK" => "INT UNSIGNED NOT NULL",
	"content" => "TEXT NOT NULL",
	"status" => "VARCHAR(255) NOT NULL",
	"dateCreated" => "DATETIME NOT NULL",
	"dateModified" => "DATETIME NOT NULL",
	"sql" => "CONSTRAINT FK_".$tablePrefix."posts_id".$tablePrefix."comments FOREIGN KEY (postId_FK) REFERENCES ".$tablePrefix."posts(id_PK)",
	"sql" => "CONSTRAINT FK_".$tablePrefix."users_id".$tablePrefix."comments FOREIGN KEY (userId_FK) REFERENCES ".$tablePrefix."users(id_PK)")
);


// Database and tables creation
try {
	$dbh->exec("CREATE DATABASE IF NOT EXISTS `$dbName` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci; USE `$dbName`;");

    foreach($tables as $table => $fields) {
    	$sql = "CREATE TABLE IF NOT EXISTS `$table`(";

    	foreach($fields as $fieldName => $fieldValue) {
    		if($fieldName == "sql") {
    			$sql .= "$fieldValue,";
    		}
    		else {
    			$sql .= "`$fieldName` $fieldValue,";
    		}	
    	}
    	$sql = substr($sql, 0, -1); // remove the last comma
    	$sql .= ") ENGINE=INNODB CHARACTER SET utf8 COLLATE utf8_general_ci"; 
		$dbh->exec($sql);
    }
}
catch(PDOExeption $e) {
	exit("DB ERROR: ".$e->getMessage());
}

