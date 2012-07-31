<?php

class db {
	private $server;
	private $user;
	private $pass;
	private $db;
	private $db_res;
	
	public function __construct($serv_ = null, $user_ = null, $pass_ = null, $db_ = null) {
		if(!empty($serv_) && !empty($user_) && !empty($pass_) && !empty($db_)){
			$this->db_res = mysql_connect($serv_, $user_, $pass_);
			mysql_select_db($db_, $this->db_res);
		}
	}
	
	public function __get($what) {
		if(isset($this->{$what})){
			return $this->{$what};
		} else {
			return false;
		}
	}
	
	public function __set($what, $with){
		if(isset($this->{$what}) && $what != 'db_res'){
			return $this->{$what} = $with;
		} else {
			return false;
		}
	}
	
	public function delete($what){
		unset($this->{$what});
	}
	
	public function connect(){
		if(!empty($serv) && !empty($user) && !empty($pass) && !empty($db)){
			$this->db_res = mysql_connect($serv, $user, $pass);
			mysql_select_db($db, $this->db_res);
			return true;
		} else {
			return false;
		}
	}
}

?>