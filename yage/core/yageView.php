<?php

class YageView extends Yage
{
	private $controller;
	private $view;
	private $layout;
	
	private $finalContent;
	private $finalLayout;
	private $finalView;
	
	public $error;
	
	public function __construct()
	{
		$this->error = new YageError();
	}
	
	public function init($controller, $view, $layout)
	{
		$this->controller = $controller;
		if($view) {
			$this->view = $view;
		}
		$this->layout = $layout;
		
		$this->checkView();
		$this->checkLayout();
	}
	
	private function checkView()
	{
		$view = null;
		if($this->view && file_exists(C_DIR_VIEW . '/' . $this->controller . '/' . $this->view . '.php')) {
			$view = C_DIR_VIEW . '/' . $this->controller . '/' . $this->view . '.php';
		} else if (!$this->view && file_exists(C_DIR_VIEW . '/' . $this->controller . '/' . $this->controller . '.php')) {
			$view = C_DIR_VIEW . '/' . $this->controller . '/' . $this->controller . '.php';
		} else {
			$this->error->addCode('ERR_MISSING_VIEW');
			$this->error->render();
		}
		$this->finalView = $view;
	}
	
	private function checkLayout()
	{
		$explicit_layout = C_DIR_LAYOUT . '/' . $this->layout . '.php';
		$default_layout = C_DIR_LAYOUT . '/' . C_DEFAULT_LAYOUT . '.php';
		
		// Check for explicit layout
		if($this->layout && file_exists($explicit_layout)) {
			$this->finalLayout = $explicit_layout;
			return;
		} else if($this->layout && !file_exists($explicit_layout)) {
			$this->error->addCode('ERR_MISSING_LAYOUT');
			$this->error->addError($this->layout . ' layout does not exist');
			$this->error->render();
		}

		// Check for default layout
		if(!$this->layout && file_exists($default_layout)) {
			$this->finalLayout = $default_layout;
			return;
		} else if(!$this->layout && !file_exists($default_layout)) {
			$this->error->addCode('ERR_MISSING_LAYOUT');
			$this->error->addError('The default layout does not appear to exist. Check to make sure ' . C_DEFAULT_LAYOUT . ' exists');
			$this->error->render();
		}
	}
	
	private function getView($path, $data)
	{
	    if (is_file($path)) {
	        ob_start();
	        include $path;
	        $contents = ob_get_contents();
	        ob_end_clean();
	        return $contents;
	    }
	    return false;
	}
	
	public function render($data)
	{
		$data['error_codes'] = $this->error->getCodes();
		$data['errors'] = $this->error->getErrors();
		$content_for_layout = $this->getView($this->finalView, $data);
		include $this->finalLayout;
		$this->error->reset();
	}
	
	public function renderError()
	{
		$this->finalView = C_SRC_VIEW . '/error/error.php';
		$this->finalLayout = C_SRC_LAYOUT . '/' . C_DEFAULT_SRC_LAYOUT . '.php';
		$data = array('error_codes' => $this->error->getCodes(), 'errors' => $this->error->getErrors());
		
		$content_for_layout = $this->getView($this->finalView, $data);
		require_once $this->finalLayout;
		exit();
	}
}