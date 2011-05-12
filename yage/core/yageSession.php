<?php

class YageSession
{	
	
	public function remove($key)
	{
		session_unregister($key);
	}
	
	public function exists($key)
	{
		if(isset($_SESSION[$key])) {
			return true;
		}
		return false;
	}
	
	public function get($key)
	{
		return $_SESSION[$key];
	}

	public function push($key, $val)
	{
		$_SESSION[$key][] = $val;
	}
	
	public function set($key, $val)
	{
		$_SESSION[$key] = $val;
	}
}