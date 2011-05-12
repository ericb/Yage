<?php

class HomeController extends YageController
{
	var $layout = 'mylayout';
	var $uses = array('home', 'user');
	var $plugin = array('auth');
	
	public function beforeFilter()
	{
		//$this->Auth->testAuth();
	}
	
	public function afterFilter()
	{
		$this->session->remove('test');
	}
	
	public function action()
	{
		$this->set('passme', $this->session->get('test'));
		$this->data['modeldata'] = $this->Home->testModel();
		$this->render();
	}
	
	public function testing()
	{
		$this->session->set('test', 'test is set');
		$this->route('/home');
	}
}