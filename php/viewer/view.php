<?php
// class header file 
require_once('/../modeller/uniClassBuilder.php');
require_once('/../globals.php'                 ); // globals parameters and constants
/**************************************************************
__costruct for class get two parameters 
object = new className(database link, rights for current user)
object->viewCatData(database name)
***************************************************************/

$object = CUniClassBuilder::initObj($cat_header,$dblnk,$rights);
if(is_null($object)) header("Location: /php".__EXIT__); // go away, kiddi
$object->viewCatData($db->get_dbname());