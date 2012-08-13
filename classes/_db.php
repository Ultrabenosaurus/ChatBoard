<?php

class db {
	private $server;
	private $user;
	private $pass;
	private $db;
	private $db_res;
	private $query;
	private $query_res;
	private $errors;
	
	public function __construct($_serv = null, $_user = null, $_pass = '', $_db = null) {
			$this->server = $_serv;
			$this->user = $_user;
			$this->pass = $_pass;
			$this->db = $_db;
		if(!empty($this->server) && !empty($this->user) && !empty($this->pass) && !empty($this->db)){
			$this->connect();
		}
	}
	public function __destruct(){
		$this->disconnect();
		$this->delete('server');
		$this->delete('user');
		$this->delete('pass');
		$this->delete('db');
		$this->delete('db_res');
		$this->delete('query');
		$this->delete('query_res');
		$this->delete('errors');
	}
	
	public function __get($what) {
		if(isset($this->{$what})){
			return $this->{$what};
		}
		return false;
	}
	public function __set($what, $with){
		if(isset($this->{$what})){
			switch($what){
				case 'db_res':
				case 'query':
				case 'query_res':
				case 'errors':
					return false;
					break;
				default:
					return $this->{$what} = $with;
					break;
			}
		}
		return false;
	}
	public function delete($what){
		$this->{$what} = null;
	}
	
	public function connect(){
		if(!empty($this->server) && !empty($this->user) && !empty($this->pass) && !empty($this->db)){
			$this->db_res = mysqli_connect($serv, $user, $pass);
			if($this->set_errors()){
				return $this->errors();
			}
			if($this->is_conn() && mysqli_select_db($db, $this->db_res)){
				if(!$this->set_errors()){
					return true;
				}
				return $this->errors();
			}
		}
		return false;
	}
	public function disconnect(){
		if($this->is_conn()){
			if(mysqli_close($this->db_res)){
				return true;
			}
		}
		return false;
	}
	public function is_conn(){
		if(!empty($this->db_res) && mysqli_ping()){
			return true;
		}
		return false;
	}
	public function errors(){
		if(!empty($this->errors)){
			if($this->errors === (mysqli_errno().": ".mysqli_error())){
				return $this->errors;
			}
			return (mysqli_errno().": ".msql_error());
		}
		return false;
	}
	private function set_errors(){
		$_err = mysqli_error();
		if(!empty($_err)){
			$this->errors = mysqli_errno().": ".$_err;
			return true;
		}
		return false;
	}
	
	public function prepare($query){
		if(isset($query) && !empty($query)){
			$temp = mysqli_real_escape_string($query);
			if($temp){
				$this->query = $temp;
				return $this->query;
			}
		}
		return false;
	}
	public function query($query, $ret = 'assoc'){
		if(isset($query) && !empty($query)){
			$temp = mysqli_query($query);
			if($this->set_errors()){
				return $this->errors();
			}
			if($temp){
				$this->query_res = $temp;
				return $this->retrieve($ret);
			}
		}
		return false;
	}
	public function retrieve($ret = 'assoc'){
		if(!empty($this->query_res)){
			switch($ret){
				case 'assoc':
					$temp = mysqli_fetch_assoc($this->query_res);
					break;
				case 'array':
					$temp = mysqli_fetch_array($this->query_res);
					break;
				case 'field':
					$temp = mysqli_fetch_field($this->query_res);
					break;
				case 'lengths':
					$temp = mysqli_fetch_lengths($this->query_res);
					break;
				case 'object':
					$temp = mysqli_fetch_object($this->query_res);
					break;
				case 'row':
					$temp = mysqli_fetch_row($this->query_res);
					break;
				case 'raw':
					return $this->query;
					break;
			}
			if(!$this->set_errors()){
				return $temp;
			}
			return $this->errors();
		}
		return false;
	}
}

?>