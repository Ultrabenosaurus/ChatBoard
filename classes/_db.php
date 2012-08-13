<?php

class db {
	private $server;
	private $user;
	private $pass;
	private $db;
	private $db_res;
	private $query;
	private $query_res;
	private $errors = array('normal'=>null, 'connect'=>null);
	
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
	public function delete($what){
		$this->{$what} = null;
	}
	
	public function connect(){
		if(!empty($this->server) && !empty($this->user) && !empty($this->db)){
			$this->db_res = mysqli_connect($this->server, $this->user, $this->pass);
			if($this->set_errors()){
				return $this->errors();
			}
			if($this->is_conn() && mysqli_select_db($this->db_res, $this->db)){
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
	public function conn_die(){
		$this->__destruct();
	}
	public function is_conn(){
		if(!empty($this->db_res) && mysqli_ping($this->db_res)){
			return true;
		}
		return false;
	}
	public function errors(){
			// echo "<pre>" . print_r($this->errors, true) . "</pre>";
		if(!empty($this->errors)){
			if($this->errors['normal'] === ($this->db_res->errno.": ".$this->db_res->error) || $this->errors['connect'] === ($this->db_res->connect_errno.": ".$this->db_res->connect_error)){
				return $this->errors;
			} else {
				$this->errors['normal'] = ($this->db_res->errno.": ".$this->db_res->error);
				$this->errors['connect'] === ($this->db_res->connect_errno.": ".$this->db_res->connect_error);
			}
			return $this->errors;
		}
		return false;
	}
	private function set_errors(){
		if($this->db_res->errno > 0 || $this->db_res->connect_errno > 0){
			$this->errors['normal'] = $this->db_res->errno.": ".$this->db_res->error;
			$this->errors['connect'] = $this->db_res->connect_errno.": ".$this->db_res->connect_error;
			return true;
		}
		return false;
	}
	
	public function prepare($query){
		if(isset($query) && !empty($query)){
			$temp = mysqli_real_escape_string($this->db_res, $query);
			if($temp){
				$this->query = $temp;
				return $this->query;
			}
		}
		return false;
	}
	public function query($query, $ret = 'assoc'){
		if(isset($query) && !empty($query)){
			$temp = mysqli_query($this->db_res, $query);
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
					$temp = mysqli_fetch_all($this->query_res, MYSQLI_ASSOC);
					break;
				case 'array':
					$temp = mysqli_fetch_all($this->query_res, MYSQLI_NUM);
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
					return $this->query_res;
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