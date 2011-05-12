<?php

class YageModel extends Yage
{
	public $db;
	public $statement;
	public $query;
	public $bind;
	
	public function __construct()
	{
		if(!$this->db)
		{
			$this->statement = array();
			$this->db = new mysqli(C_DB_HOST, C_DB_USER, C_DB_PASS, C_DB_NAME);
			if($this->db->connect_errno) {
				$this->route('/error/database');
			}
		}
	}	
	
	public function prepareFields($fields) {
		$count = count($fields);
		$fieldList = '';
		for($i = 0; $i < $count; $i++) {
			$fieldList .= $fields[$i];
			if($i != ($count - 1)) { $fieldList .= ','; }
		}
		return $fieldList;
	}
	
	public function prepareParams($col, $val) {
		$types = '';
		$types .= $this->getType($col);
		$types .= $this->getType($val);
		return $types;
	}
	
	public function getType($param) {
		$type = false;
		switch(gettype($param)) {
			case 'string':
				$type = 's';
				break;
			case 'integer':
				$type = 'i';
				break;
			case 'double':
				$type = 'd';
				break;
			default:
				break;
		}
		return $type;
	}
	
	public function prepareBinds($fields) {
		$count = count($fields);
		$bindList = '';
		for($i = 0; $i < $count; $i++) {
			$bindList .= '$' . $fields[$i];
			if($i != ($count - 1)) { $bindList .= ','; }
		}
		return $bindList;
	}
	
	public function __destruct()
	{
		if($this->db) {
			@$this->db->close();
		}
	}
}