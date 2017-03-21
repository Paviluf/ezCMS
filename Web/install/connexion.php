<?php
/**
 * Installer
 *
 * Database connexion
 */

// Database infos
$dbUser = $_POST['dbUser'];
$dbPwd = $_POST['dbPwd'];
$dbHost = $_POST['dbHost'];

$dbName = $_POST['dbName'];
$tablePrefix = $_POST['tablePrefix'];

$dbh = new PDO("mysql:host=$dbHost;charset=utf8", "$dbUser", "$dbPwd", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));