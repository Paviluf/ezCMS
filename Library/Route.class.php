<?php

/*
 * Create the route
 */

namespace Library;

class Route {
	protected $App;
	protected $HTTPRequest;
	protected $OptionsData;
	protected $routes;
	protected $requestedRoute;
	
	public function __construct(Application $App) {
		$this->App = $App;
		$this->HTTPRequest = new HTTPRequest();
		$this->Options = new \Library\Models\OptionsManager(\Library\Config::getSetting('tablePrefix'));
	}

	// Get routes from config file (.xml)
	private function routes() {
		$routesFile = ROOT_PATH.'Applications/'.$this->App->name().'/Config/routes.xml';
		$Xml = new \DOMDocument;
		$Xml->load($routesFile);
		$this->routes = $Xml->getElementsByTagName('route');
	}
	
	// Create the route
	public function createRoute() {
		// Get routes from config file (.xml)
		$this->routes();

		$this->requestedRoute = array();
		foreach($this->routes as $route) {
			// For UTF-8 preg_match('/regex/u') - note the /u
			if(preg_match("/^".str_replace('/', '\/', $route->getAttribute('url'))."$/u", $this->HTTPRequest->URI(), $matches)) {
				// Get Module
				$this->requestedRoute['module'] = $route->getAttribute('module');
				// Get Action
				$this->requestedRoute['action'] = $route->getAttribute('action');
				// Get Request Method
				$this->requestedRoute['vars']['RequestMethod'] = $this->HTTPRequest->RequestMethod();
				if($route->getAttribute('home')) {
					// Get Home page infos
					$this->requestedRoute['vars']['category'] = $this->Options->option('homeCategory')->fetch()['value'];
					$this->requestedRoute['vars']['home'] = TRUE;
					$this->requestedRoute['vars']['homeTitle'] = $this->Options->option('homeTitle')->fetch()['value'];
					$this->requestedRoute['vars']['homeDescription'] = $this->Options->option('homeDescription')->fetch()['value'];
				}
				else if(!empty($route->getAttribute('vars'))) {
					// Get variables from route
					$vars = explode(',', $route->getAttribute('vars'));		
					foreach($matches as $k => $varsValue) {
						if($k !== 0) {
							$this->requestedRoute['vars'][$vars[$k-1]] = $varsValue;
						}
					}
				}			
			}
		}

		try {
			if(empty($this->requestedRoute)) {
				throw new Error404("This page doesn't exist");	
			}
		}
		catch (Error404 $e) {
			$e->getMsg();
		}
		
		return $this->requestedRoute;
	}	
}