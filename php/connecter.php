<?php
/***********************************************
This is class of connector to connect to db
************************************************/

class Connecter{
		
		private $dbuser;		// user of database  must be  write here
		private $dbpassw;			// password for database must be write 
		private $host;	// host address for database
		private $dbname;	// database name
		private $dblink;				// variable for database link

		function __construct(){
				$this->set_param();
				$this->dblink = @mysql_connect($this->host,$this->dbuser,$this->dbpassw) 
											or die("Can't connect to database, check parameters"); // fail
				@mysql_set_charset('utf8'); 
				$this->select_db();
		}
		
		function __destruct(){
				$res = @mysql_close($this->dblink) 
											or die("Can't close connection to current link"); // close connection 
		}
		

		public function secure($qstr){  // don't write any strings of code  every time , this stuff don't save from hack 
				$qstr = mysql_real_escape_string(addslashes($qstr));
				return $qstr;
		}

		public function get_link(){
			 return $this->dblink; // for work out of class, at future i would close all methods in class
		}

		public function get_dbname(){
			return $this->dbname; // get dbname 
		}

		public function set_param($user = 'root', $passw = '', $host = 'localhost', $dbname= 'usctestdb'){ 
		// don't know why i do it, may be for more flexibility at near future
				$this->dbuser = $user;
				$this->dbpassw = $passw;
				$this->host = $host;
				$this->dbname = $dbname;
		}

		public function select_db(){
				@mysql_select_db($this->dbname,$this->dblink)
										or die("Can't select database for work");
		}
}

// for ucodese at 
$db = new Connecter(); // create Connecter object and connect to database
$dblnk = $db->get_link(); // better than write full code
?>
