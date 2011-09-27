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
	    $names = "'" . implode("','", array('eric', 'konen')) . "'";
	    $result = $this -> Home -> select(array(
	        'fields' => array('name', 'test'),
	        'order' => array('name', 'desc'),
	        'where' => array(
	            array('name', 'eric'),
	            array('name', $names, 'IN', 'OR')
	        ),
	        'limit' => 5
	    ));
	
		$people = array();
        while($r = mysqli_fetch_assoc($result)) {
            array_push($people, $r);
        }
        $this -> set('people', $people);
		$this->render();
	}
	
	public function testing()
	{
		$this->session->set('test', 'test is set');
		$this->route('/home');
	}
}