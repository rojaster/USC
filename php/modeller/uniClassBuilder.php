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
//*INCLUDES
include_once('/../globals.php'			   );		//include global variables
include_once('/classes.php'				   );		//include classes for objects


//*DECLARATIONS
class CUniClassBuilder{
	//*PRIVATE 
	private $objName;
	private $dbLink;
	private $rights ;
	//*PUBLIC
	public $obj     ; 

	function __construct($name, $lnk, $rights){ //get name of category, db link and access rights
		$this->objName = $name            ;
		$this->dbLink  = $lnk             ;
		$this->rights  = $rights          ;
		$this->initObjOfClass()    ;
	}

	function __destruct(){
		$this->objName = ''        ;
		$this->rights  = ''        ;
		$this->obj     = NULL      ;
		@mysql_close($this->dbLink);
	}

	private function initObjOfClass(){
		switch($this->objName){
			case __SIM__		:	$this->obj = new CViewSimcards($this->dbLink,$this->rights)  ; 
									break 													     ;
			case __DEVICES__    :	$this->obj = new CViewDevices($this->dbLink,$this->rights)   ;
									break                                                        ;
			case __SENSORS__    :	$this->obj = new CViewSensors($this->dbLink,$this->rights)   ;
									break                                                        ;
			case __AUTOS__      :	$this->obj = new CViewAutos($this->dbLink,$this->rights)     ;
									break                                                        ;
			case __MONTAGE__    :	$this->obj = new CViewServicesM($this->dbLink,$this->rights) ;
									break                                                        ;
			case __TSERVICE__   :	$this->obj = new CViewServicesTS($this->dbLink,$this->rights);
									break                                                        ;
			case __CLIENTS__    :	$this->obj = new CViewClients($this->dbLink,$this->rights)   ;
									break                                                        ;
			case __WORKERS__    :	$this->obj = new CViewWorkers($this->dbLink,$this->rights)   ;
									break                                                        ;
			default             :	$this->obj = NULL                                            ;
		}
	}

	public function getRefToObj(){
		return $this->obj;
	}
}