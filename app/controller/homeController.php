<?php

class HomeController extends AppController
{
	var $layout = 'mylayout';
	var $uses = array('home', 'user');
	
	public function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	public function afterFilter()
	{
		//$this->session->remove('test');
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