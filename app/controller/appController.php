<?php

class AppController extends YageController
{
	var $plugin = array('auth');
	
	public function beforeFilter()
	{
		//$this -> Auth -> testAuth();
	}
}