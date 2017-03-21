<?php

/**
 * Back Controler
 * Set Vars
 * Execute Action 
 * Call View
 */

namespace Library;

abstract class Controler {
	protected $App;
	protected $action;
	protected $vars;

	public function __construct($App) {
		$this->App = $App;
		$this->vars = '';
		$this->action = '';
	}

	// Get vars 
	public function setVars($vars) {
		$this->vars = $vars;
	}

	// Execute Action
	public function execAction($action) {
		if(method_exists($this, $action)) {
			$this->action = $action;
			$this->{$this->action}();
		}
		else {
			try {
				if(empty($this->requestedRoute)) {
					throw new Error404('Action "'.$action.'" not defined');	
				}
				else {
					throw new Error404('Unknow Action: '.$action);
				}
			}
			catch (Error404 $e) {
				$e->getMsg();
			}			
		}
	}

	// Call View
	protected function generateView($dataView = array()) {	
		$classControler = get_class($this);
		$controler = str_replace('Controler', '', $classControler);
		$View = new View($this->App, $controler, $this->action);
		$View->generateAndDisplay($dataView);
	}
}