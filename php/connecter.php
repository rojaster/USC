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

		function Connecter(){
				$this->dblink = mysql_connect($this->host,$this->dbuser,$this->dbpassw) 
									or die("<br/>we don't connected to dtabase , check connection config");//bad news for us , we don't connected to database, may be dblink was dropped and we must create it again
				echo("<br/>database link was created");
				return $this->dblink;
		}

		function createDB(){
				if(!$this->dblink) die("<br/>dblink is not a pointer to DB, check it"); // if value equals is 0 - connection is fault, 1 - good  and DB was dropped
				if(mysql_select_db($this->dbname,$this->dblink)) die("can't select db, check it"); // db was created early, why u must do it again?
				$sql = "CREATE DATABASE {$this->dbname}";
				mysql_query($sql) or die("<br/>WRONG!!!CREATE DATABASE....."); // query was crashed, and you should view at log file, something wrong with query to db, check it
				$sql = "SET NAMES utf8";
				mysql_query($sql) or die("<br/>WRONG!!!SET NAMES utf8"); // query was crashed, and you should view at log file, something wrong with query to db, check it
				echo("<br/>DB was created, push @create tables@ to create a tables in db");
		}

		function createTB(){
			/**/
		}

		function dropTB(){
			/**/
		}

		function dropDB(){
				if(!mysql_select_db($this->dbname,$this->dblink)) die("<br/>database already was dropped , check it for dropDB()"); // database already was  dropped  
				$sql = "DROP DATABASE {$this->dbname}";
				if(!$this->dblink) die("<br/>WRONG!!!dblink is not a pointer to DB"); // if value equals is 0 - connection is fault, 1 - good  and DB was dropped
				mysql_query($sql,$this->dblink) or die("<br/>WRONG!!! query was crashed, {".mysql_error()."}"); // query was crashed, and you should view at log file 
				echo('<br/>DATABASE WAS DROPPED');
		}


}
$DB = new Connecter();
if(isset($_POST['createdb'])){$DB->createDB();}
if(isset($_POST['dropdb'])){ $DB->dropDB();}


?>
<br/>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" name="frm">
	<input type="submit" value="createdb" name="createdb"><br/>
	<input type="submit" value="dropdb" name="dropdb">
</form>