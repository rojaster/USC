<?php
/********************************************
*Auth builder is a script for auth user with *
*login and pass                              *
*if data auth is invalid then user is        *
*redirected to auth again                    *
*if data auth is good then user is           *
*redirected to main page                     *
********************************************/
require_once('/../modeller/connecter.php');
require_once('/../globals.php');
@session_start();


$login = trim($_POST['u_name']);
$pass = trim($_POST['u_pass']);

if(empty($login) || empty($pass)) header("Location:".__EXIT__); //little kinda trick

$login = $db->secure($login); // function defined in class 
$pass = sha1($pass);
$sess = @intval($_POST['sess_switch']);

//take some info about user for create session token
$sql = "SELECT id,log,reg_date,access_rights FROM auth WHERE log='{$login}' AND lpas='{$pass}' LIMIT 1";

$qresult = mysql_query($sql,$dblnk) or die(header("Location:".__EXIT__));
if(!$qresult) header("Location:".__EXIT__); 
if(mysql_num_rows($qresult) != 1) {header("Location:".__EXIT__);} // if query returns more one records  redirect to auth
$result = mysql_fetch_assoc($qresult);
if( $sess  == 1 ){ 
		
		$_SESSION['sess_token'] = md5($result['id'].'_'.$result['reg_date']);
		 // sess token for user, i would check it for every script where user doing work
		 // take a session token and compare it with all variants
		 // yeah, we have one minus - we don't change session token after any time 
}
else{
		@session_destroy();
		@session_unset();
		header("Location:".__EXIT__);
}

$_SESSION['name'] = $result['log']; // log
$_SESSION['rights'] = $result['access_rights']; // rigths , used to check it on main script and must to know what we do with user

header("Location:".__MAIN__); // if all was cool , redirect user to main control panel
unset($result);
@mysql_free_result($qresult); // free query result, just a good practice





