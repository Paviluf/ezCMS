<?php

/**
 * Define ROOT_PATH ACTIVE DISABLED
 * Include autoload
 */

session_start();

/**
 * Define
 */

// Root directory path (eg. /home/user/ezCMS/)
if(!defined('ROOT_PATH')) {
	// Don't works on some Host like PlanetHoster
	//define('ROOT_PATH', realpath($_SERVER['DOCUMENT_ROOT']).'/../');

	// Not reliable. Vary in function of calling script 
	//define('ROOT_PATH', implode('/', array_slice(explode('/', $_SERVER['SCRIPT_FILENAME']), 0, -2)).'/');

	define('ROOT_PATH', implode('/', array_slice(explode('/', realpath(dirname(__FILE__))), 0, -2)).'/');
}

// Web directory path
if(!defined('WEB_PATH')) {
	define('WEB_PATH', ROOT_PATH.'Web/');
}

// SALT
if(!defined('SALT')) {
	define('SALT', 'eL|m?F=/APc1T:zX=RAE|}]cl@hF>hJoGMT,d|:m9wB?c+A5IDH(e{->ed.-J8q/');
}

// ezCMS Version
if(!defined('VERSION')) {
	define('VERSION', 'Alpha 1.7');
}

// Load custom config
if(file_exists(ROOT_PATH.'Library/Config/custom.php')) {
	require ROOT_PATH.'Library/Config/custom.php';
}

// Autoload
if(file_exists(ROOT_PATH.'Library/autoload.php')) {
	require ROOT_PATH.'Library/autoload.php';
}

if(!defined('ACTIVE')) {
	define('ACTIVE', 1);
}

if(!defined('DISABLED')) {
	define('DISABLED', 0);
}

// Medias directory
if(!defined('MEDIAS_DIRECTORY')) {
	define('MEDIAS_DIRECTORY', 'medias/');
}

// Medias url
if(!defined('MEDIAS_URL')) {
	define('MEDIAS_URL', '/medias/');
}



/*
 * DEBUG
 */
// Active development tools
if(file_exists(ROOT_PATH.'Library/Config/dev.xml')) {
	if(!defined('DEV')) {
		define('DEV', TRUE);
		$_SESSION['mysql'] = 0;
	}
}
else {
	if(!defined('DEV')) {
		define('DEV', FALSE);
	}
}