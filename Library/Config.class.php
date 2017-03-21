<?php

/*
 * Read config file and get settings
 */ 

namespace Library;

class Config {
	private static $settings;

	// Get one setting
	public static function getSetting($name, $value = null) {
		$settings = self::getAllSettings();
		foreach($settings as $settingData) {
			if($settingData->getAttribute('var') == $name) {
				$setting = $settingData->getAttribute('value');
			}
		}
		return $setting;
	}

	// Get an array of all settings
	private static function getAllSettings() {
		if(self::$settings == null) {
			$filePath = ROOT_PATH.'Library/Config/dev.xml';
			if(!file_exists($filePath)) {
				$filePath = ROOT_PATH.'Library/Config/prod.xml';
			}
			if(file_exists($filePath)) {
				$Xml = new \DOMDocument;
				$Xml->load($filePath);
				self::$settings = $Xml->getElementsByTagName('setting');	
			}
			else {
				try {
					throw new Error404('No config file');		
				}
				catch (Error404 $e) {
					$e->getMsg();
					exit;
				}	
			}
		}
		return self::$settings;
	}
}