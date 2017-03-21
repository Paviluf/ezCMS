<?php

/**
 * Autoloading Class function
 */

function autoload($class) {
	$filePath = ROOT_PATH.str_replace('\\', '/', $class).'.class.php';
	try {
		if(file_exists($filePath)) {
			require $filePath;
		}
		else {
			throw new \Exception("Unknow File");
		}	
	}
	catch (Error404 $e) {
		$e->getMsg();
	}	
}

spl_autoload_register('autoload');