<?php

class YageModel extends Yage
{
	public $db;
	public $statement;
	public $query;
	public $bind;
	public $table;
	
	public function __construct()
	{
		if(!$this->db)
		{
			$this->statement = array();
			$this->db = new mysqli(C_DB_HOST, C_DB_USER, C_DB_PASS, C_DB_NAME, C_DB_PORT);
			if($this->db->connect_errno) {
				$this->route('/error/database');
			}
		}
		
        if(empty($this -> table)) {
            $this -> table();
        }
	}
	
	/**
	 *  Compile Query Options
	 *  Compiles a list of values to be used in a query
	 *  @param array    Options Array
	 *  @param array    Allowed Options
	 */
	private function _compile_options( $options = array(), $allowed = array() ) {
	    $values = array();
	    $fields = '';
	    if(is_array($options)) {
	        foreach($options as $field => $opt) {
	            if(in_array($field, $allowed)) {
            	    
                    switch(strtolower($field)) {
                        case 'fields':
                            $fields = implode(', ', $opt);
                            break;
                            
                        case 'where':
                            foreach($opt as $o) {
                                $operator = ' = ';
                                $type = $this -> getType($o[1]);
                        	    if($o[2] != 'IN') { $o[1] = $this -> db -> real_escape_string($o[1]); }
                        	    if($type != 'i' && $o[1] != 'NULL' && $o[1] != 'NULL)' && $o[2] != 'IN') { $o[1] = "'{$o[1]}'"; }
                        	    if($o[2] == 'IN') { $o[1] = '(' . $o[1] . ')'; }
                                if(isset($o[2]) && $o[2]) { $operator = " {$o[2]} "; }
                                if(isset($values['where']) && $values['where']) { 
                                    if(isset($o[3]) && $o[3]) {
                                        $values['where'] .= strtoupper($o[3]) . " {$o[0]} {$operator} {$o[1]} "; 
                                    } else {
                                        $values['where'] .= "AND {$o[0]} {$operator} {$o[1]} "; 
                                    }
                                } else { 
                                    $values['where'] = "{$o[0]} {$operator} {$o[1]} "; 
                                }
                            }
                            break;
                            
                        case 'order':
                            $values['order'] = "{$opt[0]} " . strtoupper($opt[1]) . " ";
                            break;
                            
                        case 'limit':
                            $values['limit'] = "{$opt}";
                            break;
                    }
	            }
	        }
	    }
	    if(isset($values) && is_array($values)) {
	        if(isset($values['where']) && $values['where'])   { $where = " WHERE {$values['where']}"; }
	        if(isset($values['order']) && $values['order'])   { $order = " ORDER BY {$values['order']}"; }
	        if(isset($values['limit']) && $values['limit'])   { $limit = " LIMIT {$values['limit']}"; }
	    }
	    return array($fields, $where, $order, $limit);
	}
	
	public function table() {
	    $name = get_class($this);
	    $name = strtolower(str_replace('Model', '', $name));
	    $this -> table = $name;
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
	
	public function query( $query ) {
        $result = $this -> db -> query( $query );
        return $result;
	}
	
	public function select( $options = array() ) {
	    // dictate the options allowed to be set
	    $allowed = array('fields', 'where', 'order', 'table', 'limit');
	    
	    // set basics
	    $table   = $this -> table;
	    $where   = '';
	    $order   = '';
	    $limit   = '';
	    
	    // compile the options
	    list($fields, $where, $order, $limit) = $this -> _compile_options( $options, $allowed );
	    
	    // compile query
	    $query = "SELECT {$fields} FROM {$table} {$where} {$order} {$limit}";
	    
	    $result = $this -> db -> query( $query );
        return $result;
	}
	
	public function insert( $query ) {
	    $result = $this -> db -> query( $query );
        return $result;
	}
	
	public function get($col, $val, $fields = array(), $table = false) {
	    // prepare fields
	    $fields = $this -> prepareFields($fields);
	    if(empty($fields)) { $fields = '*'; }
	    
	    // prepare table
	    if(!$table) { $table = $this -> table; }
	    
	    // prepare value
	    $type = $this -> getType($val);
	    $val = $this -> db -> real_escape_string($val);
	    if($type != 'i') { $val = "'{$val}'"; }

	    $result = $this -> db -> query ( "SELECT {$fields} FROM {$table} WHERE {$col} = {$val}" );
	    return $result;
	}
	
	public function __destruct()
	{
		if($this->db) {
			@$this->db->close();
		}
	}
}