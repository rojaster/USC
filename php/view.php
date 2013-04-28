<?php
require_once("viewer.php"); // class header file 

/**************************************************************
__costruct for class get two parameters 
object = new className(database link, rights for current user)
object->viewCatData(database name)
***************************************************************/


switch($category){
		case 'sims'       : $catInfo = new CViewSimcards($db->get_link(),$rights);
							$catInfo->viewCatData($db->get_dbname());
							break;
		case 'devices'    : $catInfo = new CViewDevices($db->get_link(),$rights);
							$catInfo->viewCatData($db->get_dbname());
						    break;
		case 'sensors'    : $catInfo = new CViewSensors($db->get_link(),$rights);
							$catInfo->viewCatData($db->get_dbname());
							break;
		case 'autos'      : $catInfo = new CViewAutos($db->get_link(),$rights);
							$catInfo->viewCatData($db->get_dbname());
							break;
		case 'servicesm'  : 
							break;
		case 'servicess'  : 
							break;
		case 'workers'    : $catInfo = new CViewWorkers($db->get_link(),$rights);
							$catInfo->viewCatData($db->get_dbname());
							break;
		case 'statistics' : 
							break;
		case 'clients'    : $catInfo = new CViewClients($db->get_link(),$rights); // create a client viewer object
						    $catInfo->viewCatData($db->get_dbname());             // get a data for viewing of clients table
						    break;
		default           : header("Location: exit.php"); // go away, kiddi
	}
