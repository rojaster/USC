<?php
/***********************************************
This is connecter class to connect db
************************************************/

Class Connecter{
		
		private $dbuser = 'root';		// user of database  must be  write here
		private $dbpassw = '';			// password for database must be write 
		private $host = 'localhost';	// host address for database
		private $dbname = 'usctestdb';	// database name
		private $dblink;				// variable for database link

		function __construct(){
				$this->dblink = @mysql_connect($this->host,$this->dbuser,$this->dbpassw) 
											or die("Can't connect to database, check parameters"); // fail 
		}
		
		function __destruct(){
				$res = @mysql_close($this->dblink) 
											or die("Can't close connection to current link"); // close connection 
		}
		

		function query($qstr){
				$qstr = mysql_real_escape_string(addslashes($qstr));
		}


}

?>
