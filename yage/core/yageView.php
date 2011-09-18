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
		$this -> smarty = new Smarty();
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
		if($this->view && file_exists(C_DIR_VIEW . '/' . $this->controller . '/' . $this->view . '.tpl')) {
			$view = C_DIR_VIEW . '/' . $this->controller . '/' . $this->view . '.tpl';
		} else if (!$this->view && file_exists(C_DIR_VIEW . '/' . $this->controller . '/' . $this->controller . '.tpl')) {
			$view = C_DIR_VIEW . '/' . $this->controller . '/' . $this->controller . '.tpl';
		} else {
			$this->error->addCode('ERR_MISSING_VIEW');
			$this->error->render();
		}
		$this->finalView = $view;
	}
	
	private function checkLayout()
	{
		$explicit_layout = C_DIR_LAYOUT . '/' . $this->layout . '.tpl';
		$default_layout = C_DIR_LAYOUT . '/' . C_DEFAULT_LAYOUT . '.tpl';
		
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
	
	private function setTemplateVars( $data = array() ) {
	    // loop over and dump the $this -> data set.
	    foreach( $data as $key => $value ) {
	        $this -> smarty -> assign($key, $value);
	    }
	    
	    
	    // set system specific vars
	    $this -> smarty -> assign('C_YAGE_NAME', C_YAGE_NAME);
	    $this -> smarty -> assign('C_PATH_ROOT', C_PATH_ROOT);
	    $this -> smarty -> assign('C_YAGE_VERSION', C_YAGE_VERSION);
	    
	    // user config vars
	    $this -> smarty -> assign('C_APP_TITLE', C_APP_TITLE);
	}
	
	public function render($data)
	{
		$data['error_codes'] = $this->error->getCodes();
		$data['errors'] = $this->error->getErrors();
		$this -> setTemplateVars( $data );

		$content_for_layout = $this -> smarty -> fetch( $this -> finalView );
		$this -> smarty -> assign('content_for_layout', $content_for_layout);
		$this -> smarty -> display( $this -> finalLayout );

		$this -> error -> reset();
	}
	
	public function renderError()
	{
	    $data = array('error_codes' => $this->error->getCodes(), 'errors' => $this->error->getErrors());
	    $this -> setTemplateVars( $data );
	    
		$this->finalView = C_SRC_VIEW . '/error/error.tpl';
		$this->finalLayout = C_SRC_LAYOUT . '/' . C_DEFAULT_SRC_LAYOUT . '.tpl';
		
		$content_for_layout = $this -> smarty -> fetch( $this -> finalView );
		$this -> smarty -> assign('content_for_layout', $content_for_layout);
		$this -> smarty -> display( $this -> finalLayout );
		
		exit();
	}
}