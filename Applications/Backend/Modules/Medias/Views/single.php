<?php

/*
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
*/


if(isset($id_PK)) {
	$id = $id_PK;
}
else {
	$id = '';
	$source = '';
	$sourceUrl = '';
	$fileName = '';
	$filePath = date('Y/m/');
	$type = '';
}

$html = '';


$html .= '<form action="/admin/media/action" id="form" method="post" enctype="multipart/form-data">';

/*
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
*/

$html .= '<p><input type="file" name="media" id="media" /></p>';


$html .= '<p>FilePath : <input type="text" id="filePath" name="filePath" size="90" value="'.$filePath.'" /></p>';    
$html .= '<p>FileName : <input type="text" id="fileName" name="fileName" size="90" value="'.$fileName.'" /></p>';
$html .= '<p>Type : <input type="text" id="type" name="type" size="90" value="'.$type.'" /></p>';
$html .= '<p>Source : <input type="text" id="source" name="source" size="90" value="'.$source.'" /></p>';
$html .= '<p>Source url : <input type="text" id="sourceUrl" name="sourceUrl" size="90" value="'.$sourceUrl.'" /></p>';

$html .= '<p style="width:500px;"><img src="/'.MEDIAS_DIRECTORY.$filePath.$fileName.'" /></p>';

$html .= '<p><input type="submit" value="Submit"/></p>';


if(isset($id_PK)) {
	$html .= '<input type="hidden" name="updateMediaId" value="'.$id.'" /></form>';
}
else {
	$html .= '<input type="hidden" name="newMedia" value="new" /></form>';
}



