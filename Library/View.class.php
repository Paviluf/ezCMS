<?php

/**
 * Build and send the HTML page to client
 */

namespace Library;

class View {
	protected $App;
	protected $Template;
	protected $Options;
	protected $actionFile;
	protected $configData;
	protected $templatePath;
	

	public function __construct($App, $controler, $action) {
		$this->App = $App;
		$this->Template = new \Library\Models\ViewManager(\Library\Config::getSetting('tablePrefix'));
		$this->Options = new \Library\Models\OptionsManager(\Library\Config::getSetting('tablePrefix'));
		$this->actionFile = ROOT_PATH.str_replace('\\', '/', mb_substr($controler, 0, -mb_strlen(strrchr($controler, '\\'))))."/Views/".$action.".php";
		$this->configData = array('siteName' => Config::getSetting('siteName'),
								'sysName' => Config::getSetting('sysName'),
								'serverName' => $_SERVER['SERVER_NAME'],
								'host' => 'http://'.$_SERVER['SERVER_NAME'],
								'uri' => $_SERVER['REQUEST_URI'],
								'appName' => $this->App->name(),
								'isBot' => \Library\Functions::botDetect());		
	}

	// Generate the template elements
	protected function generateTemplate() {
		// Get template
		$this->configData['template'] = $this->Options->option('template')->fetch()['value'];
		// Get template path
		$this->templatePath = ROOT_PATH.'Web/templates/'.mb_strtolower($this->App->name()).'/'.$this->configData['template'].'/';

		// Get template's elements files in his directory
		$templateElements = array();
		foreach (scandir($this->templatePath) as $v) {
			if($v != '.' && $v != '..' && $v != 'css' && $v != 'layout.php') {
				if(substr($v, -4) == '.php') {
					$templateElements[] = substr($v, 0, -4);
				}
			}
		}

		// Get menu and sort it
		$templateData = $this->Template->templateMenu()->fetchAll();
		foreach ($templateData as $k => $v) {
			if(!empty($v['menuUrl'])) {
				$url = $v['menuUrl'];
			}
			else {
				$url = $v['categoryUrl'];
			}
			$this->configData['menu'][] = array('name' => $v['name'], 'label' => $v['label'], 'url' => $url);
		}
		ksort($this->configData['menu']);
		
		// Generate template's elements (HTML)
		foreach ($templateElements as $element) {
			$elementPath = $this->templatePath.$element.'.php';
			if(file_exists($elementPath)) {
				$this->configData[$element] = $this->generateView($elementPath);
			}
		}
	}

	// Generate the page and display it (HTML)
	public function generateAndDisplay($data) {
		$this->configData = array_merge($this->configData, $data);
		unset($data);
		$this->generateTemplate();
		$this->configData['content'] = $this->generateView($this->actionFile);

		echo $this->generateView($this->templatePath.'layout.php');
	}

	// Generate the element requested (HTML)
	protected function generateView($actionFile) {
		if(file_exists($actionFile)) {
			extract($this->configData);
			require $actionFile;
			return $html;
		}
		else {
			try {
				throw new \Exception("Unknow Action File");
			}
			catch (Error404 $e) {
				$e->getMsg();
			}	
		}
	}
}