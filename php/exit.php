<?php
/***************************************
Destroy session and redirect user to auth
page
***************************************/
@session_start();
@session_unset($_SESSION['sess_token']);
@session_destroy();
setcookie("PHPSESSID","",time()-3600,"/",$_SERVER['SERVER_NAME']);
header("Location: /php/auth.php") or die(print("Session is not destroyed, check session destruct")) ; // authentification page