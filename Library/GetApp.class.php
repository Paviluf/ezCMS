<?php

/**
 * List available Applications
 * Get Application Name
 */

namespace Library;

abstract class GetApp {
	// Applications name available
	private static $availableApps = array('Frontend', 'Backend');

	// Get Application Name
	public static function appName() {
		if(isset($_GET['app'])) {
			$appName = ucfirst(mb_strtolower($_GET['app']));

			if(in_array($appName, self::$availableApps)) {
				return $appName;
			}
			else {
				try {
					throw new Error404('This application "'.ucfirst(mb_strtolower($_GET['app'])).'" doesn\'t exist');		
				}
				catch (Error404 $e) {
					$e->getMsg();
				}	
			}		
		}
		else {
			try {
				throw new Error404('No application requested');		
			}
			catch (Error404 $e) {
				$e->getMsg();
			}	
		}
	}
}




