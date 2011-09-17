<?php

class Yage
{
	private $params;
	
	private $controllerName;
	private $modelName;
	private $viewName;
	
	private $controller;
	private $model;
	private $view;
	
	public $error;
	public $session;
	public $data;
	public $plugin;
	public $db;
	
	public function __construct($init=null)
	{
		$this->error = new YageError();
		$this->session = new YageSession();
		$this->initParams();
		if(isset($init)) {
			$this->error = new YageError(true);
			$this->init();
		}
	}
	
	public function convertName($name, $lower=null)
	{
		if(!$lower) {
			$letter = strtoupper(substr($name, 0, 1));
		} else {
			$letter = strtolower(substr($name, 0, 1));
		}
		$name = $letter . substr($name, 1);
		return $name;
	}
	
	public function init()
	{
		$this->initController();
	}
	
	private function initController()
	{
		// If no controller is specified. Route to default Controller.
		if(!$this->params['request']) {
            $this->route(C_ROUTE_DEFAULT);
    	    exit(); 
		}
		
		// Set Controller Class Name
		$letter = strtoupper(substr($this->params['controller'], 0, 1));
		$this->controllerName = $letter . substr($this->params['controller'], 1) . 'Controller';

		// Check for Controller Existence
		if(class_exists($this->controllerName)) {
			eval('$this->controller = new ' . $this->controllerName . '();'); 
		} else {
			$this->error->addCode('ERR_MISSING_CONTROLLER');
			$this->error->render();
		}
		
		// Check for Models
		if(!$this->error->hasErrors()) {
			if($this->controller->uses !== false) {
				if(!$this->controller->uses) {
					$this->modelName = str_replace('Controller', '', $this->controllerName);
				} else {
					$this->modelName = array();
					foreach($this->controller->uses as $name) {
						$this->modelName[] = $name;
					}
				}
				
				$this->initModel();
				if($this->model) {
					$this->injectModels();
					
				}
			}
		}
		
		// Initialize Plugins
		if($this->controller->plugin) {
			$this->initPlugins();
			if($this->plugin) {
				$this->injectPlugins();
			}
		}		
		
		// Check For Action & Associated View
		if(!$this->error->hasErrors()) {
			$view = null;
			if($this->params['action'] && method_exists($this->controller, $this->params['action'])) {
				$this->actionName = $this->params['action'];
				$view = $this->params['action'];
			} else if($this->params['action'] && !method_exists($this->controller, $this->params['action'])) {
				$this->error->addCode('ERR_INVALID_ACTION');
				$this->error->addError($this->params['action'] . ' is not a valid action of ' . $this->controllerName);
				$this->error->render();
			} else {
				$this->actionName = 'action';
				$view = $this->params['controller'];
			}
			
			// Call Action
			call_user_func(array($this->controller, $this->actionName));
		}
	}

	public function initParams() 
	{
	    $url_request = '';
	    $default_route = C_ROUTE_DEFAULT;
		if(isset($_REQUEST['request'])) { $url_request = $_REQUEST['request']; } elseif(empty($default_route)) { $url_request = 'root'; }
		preg_match_all('/([^\/]+)/i', $url_request, $matches);
		
		if(count($matches) > 0 && count($matches[0]) > 0) {
		    $this -> params = array( 'request' => $url_request);
    		if(isset($matches[0][0])) { $this -> params['controller'] = $matches[0][0]; }
    		if(isset($matches[0][1])) { $this -> params['action']     = $matches[0][1]; }
    		if(isset($matches[0][2])) { $this -> params['id']         = $matches[0][2]; }
        } else {
            $this -> params = array(
    			'request'    => '',
    			'controller' => '',
    			'action'     => '',
    			'id'         => ''	
    		);
        }
	}
	
	private function initModel()
	{
		$tempModel = array();
		$this->model = array();
		if(gettype($this->modelName) != 'array') {
			if(class_exists($this->modelName . 'Model')) {
				eval('$tempModel[\'' . $this->modelName . '\'] = new ' . $this->modelName . 'Model();');
				$this->model[$this->modelName] = $tempModel[$this->modelName];
			} else {
				$this->error->addCode('ERR_MISSING_MODEL');
				$this->error->render();
			} 
		} else if(gettype($this->modelName) == 'array') {
			foreach($this->modelName as $model) {
				$model = $this->convertName($model);
				if(class_exists($model . 'Model')) {
					eval('$tempModel[\'' . $model . '\'] = new ' . $model . 'Model();');
					$this->model[$model] = $tempModel[$model];
				} else {
					$this->error->addCode('ERR_MISSING_MODEL');
					$this->error->addError($model . 'Model' . ' doesn\'t exist');
					$this->error->render();
				} 
			}
		}
	}
	
	public function initPlugins()
	{
		if($this->controller->plugin) {
			$this->plugin = array();
			$tempPlugin = array();
			foreach($this->controller->plugin as $plugin)
			{
				@include_once C_SRC_PLUGIN . '/' . $plugin . 'Plugin.php';
				if(class_exists($plugin . 'Plugin')) {
					$plugin = $this->convertName($plugin);
					eval('$tempPlugin[\'' . $plugin . '\'] = new ' . $plugin . 'Plugin();');
					$this->plugin[$plugin] = $tempPlugin[$plugin];
				} else {
					$this->error->addCode('ERR_MISSING_PLUGIN');
					$this->error->addError($plugin . 'Plugin' . ' doesn\'t exist');
					$this->error->render();
				} 
			}
		}
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
	private function injectModels()
	{
		foreach($this->model as $key=>$mod) {
			eval('$this->controller->' . $key . ' = $mod;');
		}		
	}
	
	private function injectPlugins()
	{
		foreach($this->plugin as $key=>$plugin) {
			eval('$this->controller->' . $key . ' = $plugin;');
		}
	}
	
	public function route($path)
	{
		header('Location: ' . C_PATH_ROOT . $path);
		exit();
	}
	
	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}
	
	public function render($view=null)
	{
		$this->beforeFilter();
		
		$page_title = $this->page_title;
		$data = $this->data;
		$params = $this->getParams();
		$controller = $params['controller'];
		if(!$view) {
			$view = $params['action'];
		}
		
		$YageView = new YageView();
		$YageView->init($controller, $view, $this->layout);
		$YageView->render($data);
		
		$this->afterFilter();
	}
}