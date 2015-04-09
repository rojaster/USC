<?php
/***********************************************************
* CUniClassBuilder is a class for create one builder point *
* for every object                                         *
* for it query is put a parameter:                         *
* after execute  builder to return                         *
* the object of class of need for this parameter           *
* Singleton pattern                                    *
* 18/06/2013 1:33 for Surgut                               *
* adminpc                                                  *
***********************************************************/
//*INCLUDES
include_once('/../globals.php');		//include global variables
include_once('classes.php'    );		//include classes for objects


//*DECLARATIONS
final class CUniClassBuilder{
	//*PUBLIC
	private static $obj;

	final public static function initObj($category,$link,$rights){
		switch($category){
			case __SIM__		:	self::$obj = new CViewSimcards($link,$rights)  ;
									break 										   ;
			case __DEVICES__    :	self::$obj = new CViewDevices($link,$rights)   ;
									break                                          ;
			case __SENSORS__    :	self::$obj = new CViewSensors($link,$rights)   ;
									break                                          ;
			case __AUTOS__      :	self::$obj = new CViewAutos($link,$rights)     ;
									break                                          ;
			case __MONTAGE__    :	self::$obj = new CViewServicesM($link,$rights) ;
									break                                          ;
			case __TSERVICE__   :	self::$obj = new CViewServicesTS($link,$rights);
									break                                          ;
			case __CLIENTS__    :	self::$obj = new CViewClients($link,$rights)   ;
									break                                          ;
			case __WORKERS__    :	self::$obj = new CViewWorkers($link,$rights)   ;
									break                                          ;
			default             :	self::$obj = NULL                              ;
		}
		return self::$obj;
	}
}
