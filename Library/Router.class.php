<?php

/**
 * Get the route
 * Set action, module
 * Call the right Controler
 */

namespace Library;

class Router {
	protected $App;
	protected $Controler;
	protected $requestedRoute;
	protected $action;
	protected $module;

	public function __construct(Application $App) {
		$this->App = $App;
	}

	// Call route creation => create Module, Action and Controler. Call the controler
	public function routeRequest() {
		try {
			// Get route
			$Route = new Route($this->App);
			$this->requestedRoute = $Route->createRoute();

			// create Module, Action and Controler
			$this->createModule();
			$this->createAction();			
			$this->createControler();

			// Get variables from route
			if(isset($this->requestedRoute['vars']) && !empty($this->requestedRoute['vars'])) {
				$this->Controler->setVars($this->requestedRoute['vars']);
			}

			// Call the controler
			$this->Controler->execAction($this->action);
		}
		catch (Error404 $e) {
			$e->getMsg();
		}
	}

	// create Module
	protected function createModule() {
		if(empty($this->requestedRoute) || !isset($this->requestedRoute['module'])) {
			throw new Error404("No Module");	
		}
		else {
			$this->module = ucfirst(mb_strtolower($this->requestedRoute['module']));
		}
	}

	// create Action
	protected function createAction() {
		if(empty($this->requestedRoute) || !isset($this->requestedRoute['action'])) {
			throw new Error404("No Action");	
		}
		else {
			$this->action = mb_strtolower($this->requestedRoute['action']);
		}
	}

	// create Controler
	protected function createControler() {
		$controler = $this->module;

		$fileControler = ROOT_PATH.'Applications/'.$this->App->name().'/Modules/'.$this->module.'/'.$controler.'Controler.class.php';

		if(file_exists($fileControler)) {
			$classControler = '\Applications\\'.$this->App->name().'\Modules\\'.$this->module.'\\'.$controler.'Controler';
			$this->Controler = new $classControler($this->App);
		}
		else {
			throw new \Error404("Unknow Controller");
		}
	}
}