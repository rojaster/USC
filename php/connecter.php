<?php
/***********************************************
This is connecter class to connect db
************************************************/

Class Connecter{
		
		private $dbuser = 'root';
		private $dbpassw = '';
		private $host = 'localhost';
		private $dbname = 'usctestdb';
		private $dblink;

		function Connecter(){
				$this->dblink = mysql_connect($host,$dbuser,$dbpassw) 
									or die("Connect to database is crashed, check parameters");
		}
}