<?php
/***********************************************
inserter.php is a script which call insDataToTable()
and insert data at table for object
************************************************/
@session_start();
require_once('viewer.php');

if(empty($_SESSION['sess_token'])) header("Location: exit.php");
$category = $db->secure($_GET['ctg']);
	switch($category){
		case 'sims'       : $catInsData = new CViewSimcards($db->get_link(),$_SESSION['rights']);
							break;
		case 'devices'    : $catInsData = new CViewDevices($db->get_link(),$_SESSION['rights']);
						    break;
		case 'sensors'    : $catInsData = new CViewSensors($db->get_link(),$_SESSION['rights']);
							break;
		case 'autos'      : $catInsData = new CViewAutos($db->get_link(),$_SESSION['rights']);
							break;
		case 'servicesm'  : $catInsData = new CViewServicesM($db->get_link(),$_SESSION['rights']);
							break;
		case 'servicess'  : $catInsData = new CViewServicesTS($db->get_link(),$_SESSION['rights']);
							break;
		case 'workers'    : $catInsData = new CViewWorkers($db->get_link(),$_SESSION['rights']);
							break;
		case 'clients'    : $catInsData = new CViewClients($db->get_link(),$_SESSION['rights']); // create a client viewer object
							break;
		default           : header("Location: exit.php"); // go away, kiddi
	}

$catInsData->insertData();