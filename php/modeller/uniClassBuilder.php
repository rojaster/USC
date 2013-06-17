<?php
/***********************************************************
* CUniClassBuilder is a class for create one builder point *
* for every object                                         *
* for it query is put a parameter:  __construct($objName)  *
* after execute  builder to return                         *
* the object of class of need for this parameter           *
*                                                          *
* 18/06/2013 1:33 for Surgut                               *
* adminpc                                                  *
***********************************************************/
include_once('/../globals.php');


class CUniClassBuilder{
	private $objName;
	private $obj    ;

	function __construct($name){
		$this->objName = $name;
		CUniClassBuilder::initObjOfClass();
	}

	function __destruct(){
		$this->objName = '';
	}

	static public function initObjOfClass(){
		switch($this->objName){
			case

		}
	}

}