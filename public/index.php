<?php
session_start();
require_once "../yage/init.php";

// Include Controllers
function ___autoload($name) {
	$letter = strtolower(substr($name, 0, 1));
	$name = $letter . substr($name, 1);
	
	try {
		@include_once  C_DIR_CONTROLLER . '/' . $name . '.php';
	} catch(Exception $e) {}
	
	try {
		@include_once C_DIR_MODEL . '/' . $name . '.php';
	} catch(Exception $e) {}	
}

// Instantiate Yage Engine
$yage = new Yage(true);




