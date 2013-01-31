<?php
/********************************************
*Auth builder is a script for auth user with *
*login and pass                              *
*if data auth is invalid then user is        *
*redirected to auth again                    *
*if data auth is good then user is           *
*redirected to main page                     *
********************************************/
require_once("connecter.php");


$DB = new Connecter();

if($DB == 0) {echo("DAMN SHIT ");} else {echo("good shit");}


