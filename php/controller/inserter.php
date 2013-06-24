<?php
/***********************************************
inserter.php is a script which call insDataToTable()
and insert data at table for object
************************************************/
@session_start();
require_once('/../modeller/uniClassBuilder.php');
require_once('/../modeller/classes.php');
require_once('/../globals.php');

if(empty($_SESSION['sess_token'])) header("Location:".__EXIT__);
$category = Connetcter::secure($_GET['ctg']);
$catInsData = CUniClassBuilder::initObj($category,$dblnk,$rights);
if(is_null($catInsData)) header("Location:".__EXIT__); // go away, kiddi
$catInsData->insertData();