<?php

class YageError
{	
	
	public function __construct($init=null)
	{
		if($init) {
			$this->reset();
		}	
	}
	
	public function addCode($code)
	{
		$_SESSION['yage_error_codes'][] = $code;
	}
	
	public function addError($error)
	{
		$_SESSION['yage_errors'][] = $error;
	}
	
	public function getErrors()
	{
		return $_SESSION['yage_errors'];
	}
	
	public function getCodes()
	{
		return $_SESSION['yage_error_codes'];
	}
	
	public function hasErrors()
	{
		$err_count = count($this->getErrors());
		$code_count = count($this->getCodes());
		if($err_count > 0 || $code_count > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function render()
	{
		$View = new YageView();
		$View->renderError();
		$this->clear();
	}
	
	public function reset()
	{
		$_SESSION['yage_error_codes'] = array();
		$_SESSION['yage_errors'] = array();
	}
}