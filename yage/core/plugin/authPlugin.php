<?php

class AuthPlugin extends YagePlugin
{
	var $uses = array('user');
	
	public function testAuth()
	{
		echo 'testing auth';
	}
}