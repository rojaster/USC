<?php
/*
	Viewer controller, defining view table for selected category
*/
require_once("connecter.php");
	//interface method for abstract Viewer class and implements it to child class with values and methods
interface IViewer{
	function getFieldsCount($dbname,$tableName);// get count of fields for table
	function getFieldsName();					// get a names of fields of table
	function getTableRecords($tableName);		// get a record of fields for table
}

//abstract class with generic method for all classes
abstract class CViewer{
	protected $dblink;   // link to connect to db
	protected $rights;   // user access rights for work with data 
	protected $message;  // error message
	protected $tableName; //Table Name for category
	public function viewCatData($dbname){
		$countTHead = $this->getFieldsCount($dbname,$this->tableName);
		$tHeadName = $this->getFieldsName();
		$tableHead = "\t<thead><tr>\n";
		for($i=0; $i<$countTHead; ++$i){
			$tableHead .= "\t\t<th>".$tHeadName[$i]."</th>\n";
		}
		$tableHead .= "</tr></thead>\n";
		echo($tableHead);
		$tableBody=$this->getTableRecords($this->tableName);
		echo($tableBody);
	}

	public function getEditMenu($recID,$rights,$tableName){
		switch($rights){
			case 'SUID': return "<a href=\"rec_editor.php?id={$recID}&action=editid&table={$tableName}\">Изменить</a> 
						&nbsp;&nbsp;<a href=\"rec_editor.php?id={$recID}&action=deleteid&table={$tableName}\">Удалить</a>";
			case 'SUI' : 
			case 'SU'  : return "<a href=\"rec_editor.php?id={$recID}&action=editid&table={$tableName}\">Изменить</a>";
			default: return "--------";
		}
	}

	protected function errorer($message){
		die($message);
	}
}


// for clients table
class CViewClients extends CViewer implements IViewer{
	function __construct($lnk,$rights){
		$this->tableName = 'tblclients';
		$this->message = 'Error in viewClients class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	public function getFieldsCount($dbname,$tableName){
		$qr = mysql_list_fields($dbname, $tableName) or $this->errorer("getFieldsCount ".$this->tableName." error");
		$fieldsCount = mysql_num_fields($qr);
		@mysql_free_result($qr);
		return $fieldsCount;
	}

	public function getFieldsName(){
		$fieldsName = array();
		$fieldsName[] = 'Название фирмы';
		$fieldsName[] = 'Персона для контактов';
		$fieldsName[] = 'Телефонный номер';
		$fieldsName[] = 'Номер факса';
		$fieldsName[] = 'Email';
		$fieldsName[] = 'Тип клиента(юр.лицо, физ.лицо)';
		$fieldsName[] = 'Адрес фирмы';
		$fieldsName[] = 'Фото';
		$fieldsName[] = 'Статус';
		$fieldsName[] = 'Редактирование';
		return $fieldsName;
	}

	public function getTableRecords($tableName){
		$sql = "SELECT * FROM `".$tableName."`";
		$records = mysql_query($sql,$this->dblink) or $this->errorer("getTableRecords".$this->tableName."error");
		$recordsCount = mysql_num_rows($records);
		if($recordsCount == 0){
			$tableBody = "<tbody><tr></tr><tr><td>NONE ".$this->tableName." dont have a records</td></tr></tbody>";
		}
		else{
			$tableBody = "\t<tbody>\n";
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".$value['firm_name']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['contact_person']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['phone_num']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['fax_num']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['e_mail']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['cl_type']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['address']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['foto']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['status']."</td>\n";
				$tableBody .= "\t\t\t<td>".$this->getEditMenu($value['client_id'],$this->rights,$tableName)."</td>\n";
				$tableBody .= "\t\t</tr>\n";
			}
			$tableBody .="</tbody>\n";
		}
		@mysql_free_result($records);
		return $tableBody;
	}
}


// for simcards table
class CViewSimcards extends CViewer implements IViewer{
	function __construct($lnk,$rights){
		$this->tableName = 'tblsims';
		$this->message = 'Error in CViewSimcards class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount($dbname,$tableName){
		$qr = mysql_list_fields($dbname, $tableName) or $this->errorer("getFieldsCount ".$this->tableName." error");
		$fieldsCount = mysql_num_fields($qr);
		@mysql_free_result($qr);
		return $fieldsCount; // because i return sim_id 
	}

	function getFieldsName(){
		$fieldsName = array();
		$fieldsName[] = 'Сим номер';
		$fieldsName[] = 'Телефонный номер';
		$fieldsName[] = 'Статус';
		$fieldsName[] = 'Дата создания';
		$fieldsName[] = 'Лицевой счет';
		$fieldsName[] = 'Редактирование';
		return $fieldsName;
	}

	function getTableRecords($tableName){
		$sql = "SELECT * FROM `".$tableName."`";
		$records = mysql_query($sql,$this->dblink) or $this->errorer("getTableRecords for ".$tableName." is wrong");
		if(mysql_num_rows($records) == 0){
			$tableBody = "<tbody><tr><td>NONE ".$this->tableName." dont have a records</td>
							<td></td><td></td><td></td><td></td><td></td></tr></tbody>";
		}
		else{
			$tableBody = "\t<tbody>\n";
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".$value['sim_number']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['phone_number']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['status']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['dateofcreate']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['lic_schet']."</td>\n";
				$tableBody .= "\t\t\t<td>".$this->getEditMenu($value['sim_id'],$this->rights,$tableName)."</td>\n";
				$tableBody .= "\t\t</tr>\n";
			}
			$tableBody .= "</tbody>\n";
		}
		@mysql_free_result($records);
		return $tableBody;
	}
}


//for devices table
class CViewDevices extends CViewer implements IViewer{
	function __construct($lnk,$rights){
		$this->tableName = 'tbldevices';
		$this->message = 'Error in viewDevices class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount($dbname,$tableName){// get count of fields for table
		$qr = mysql_list_fields($dbname, $tableName) or $this->errorer("getFieldsCount ".$this->tableName." error");
		$fieldsCount = mysql_num_fields($qr);
		@mysql_free_result($qr);
		return $fieldsCount; // +1 for sim card number from tblsims 
	}

	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = 'Название прибора';
		$fieldsName[] = 'Номер прибора';
		$fieldsName[] = 'Серийный номер';
		$fieldsName[] = 'IMEI';
		$fieldsName[] = 'Статус';
		$fieldsName[] = 'Дата создания';
		$fieldsName[] = 'Редактирование';
		return $fieldsName;
	}

	function getTableRecords($tableName){		// get a record of fields for table
		$sql = "SELECT `tbldevices`.*
				FROM ".$tableName."";
		$records = mysql_query($sql,$this->dblink) or 
					$this->errorer(" getTableRecords for".$tableName." is a wrong");
		if(mysql_num_rows($records) == 0){
			$tableBody = "<tbody><tr><td>NONE ".$this->tableName." dont have a records</td>
							<td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody>";
		}
		else{
			$tableBody = "\t<tbody>\n";
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".$value['dev_name']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['dev_number']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['serial_number']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['IMEI']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['status']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['dev_date']."</td>\n";
				$tableBody .= "\t\t\t<td>".$this->getEditMenu($value['dev_id'],$this->rights,$tableName)."</td>\n";
				$tableBody .= "\t\t</tr>\n";
			}
			$tableBody .= "</tbody>\n";
		}
		return $tableBody;
	}
}

// for sensors table
class CViewSensors extends CViewer implements IViewer{

	function __construct($lnk,$rights){
		$this->tableName = 'tblsensors';
		$this->message = 'Error in CViewSensors class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount($dbname,$tableName){// get count of fields for table
		$qr = mysql_list_fields($dbname, $tableName) or $this->errorer("getFieldsCount ".$this->tableName." error");
		$fieldsCount = mysql_num_fields($qr);
		@mysql_free_result($qr);
		return $fieldsCount+1;
	}


	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = "ID датчика";
		$fieldsName[] = "Тип модели";
		$fieldsName[] = "Серийный номер";
		$fieldsName[] = "Статус";
		$fieldsName[] = "ID прибора";
		$fieldsName[] = "Редактирование";
		return $fieldsName;
	}
	
	function getTableRecords($tableName){		// get a record of fields for table
		$sql = "SELECT *
				FROM ".$tableName."";
		$records = mysql_query($sql,$this->dblink) or 
					$this->errorer(" getTableRecords for".$tableName." is a wrong");
		if(mysql_num_rows($records) == 0){
			$tableBody = "<tbody><tr><td colspan=6>NONE ".$this->tableName." dont have a records</td>
							</tr></tbody>";
		}
		else{
			$tableBody = "\t<tbody>\n";
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".$value['sens_id']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['model_type']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['sens_serial']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['sens_status']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['tblDevices_dev_id']."</td>\n";
				$tableBody .= "\t\t\t<td>".$this->getEditMenu($value['sens_id'],$this->rights,$tableName)."</td>\n";
				$tableBody .= "\t\t</tr>\n";
			}
			$tableBody .= "</tbody>\n";
		}
		return $tableBody;
	}
}

//for Autos table
class CViewAutos extends CViewer implements IViewer{
	function __construct($lnk,$rights){
		$this->tableName = 'tblautos';
		$this->message = 'Error in CViewAutos class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount($dbname,$tableName){// get count of fields for table
		$qr = mysql_list_fields($dbname, $tableName) or $this->errorer("getFieldsCount ".$this->tableName." error");
		$fieldsCount = mysql_num_fields($qr);
		@mysql_free_result($qr);
		return $fieldsCount;
	}

	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = "Гос.номер";
		$fieldsName[] = "VIN";
		$fieldsName[] = "Марка";
		$fieldsName[] = "Статус";
		$fieldsName[] = "Дата подключения";
		$fieldsName[] = "Клиент";			// change this field on client name for production
		$fieldsName[] = "Редактирование";			// change this field on client name for production
		return $fieldsName;
	}
	
	function getTableRecords($tableName){		// get a record of fields for table
		$sql = "SELECT *
				FROM ".$tableName."";
		$records = mysql_query($sql,$this->dblink) or 
					$this->errorer(" getTableRecords for".$tableName." is a wrong");
		if(mysql_num_rows($records) == 0){
			$tableBody = "<tbody><tr><td colspan=7>NONE ".$this->tableName." dont have a records</td>
							</tr></tbody>";
		}
		else{
			$tableBody = "\t<tbody>\n";
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".$value['gos_num']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['vin']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['marka']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['status']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['conn_date']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['tblclients_client_id']."</td>\n";
				$tableBody .= "\t\t\t<td>".$this->getEditMenu($value['auto_id'],$this->rights,$tableName)."</td>\n";
				$tableBody .= "\t\t</tr>\n";
			}
			$tableBody .= "</tbody>\n";
		}
		return $tableBody;
	}
}

//for Workers and auth Info information
class CViewWorkers extends CViewer implements IViewer{

	function __construct($lnk,$rights){
		$this->tableName = 'tblworkers';
		$this->message = 'Error in CViewWorkers class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount($dbname,$tableName){// get count of fields for table
		$qr = mysql_list_fields($dbname, $tableName) or $this->errorer("getFieldsCount ".$this->tableName." error");
		$fieldsCount = mysql_num_fields($qr);
		@mysql_free_result($qr);
		return $fieldsCount+1;
	}

	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = "ИД Работника";
		$fieldsName[] = "Имя";
		$fieldsName[] = "Фамилия\Отчество";
		$fieldsName[] = "Серия паспорта";
		$fieldsName[] = "Дата принятия";
		$fieldsName[] = "Должность";
		$fieldsName[] = "Телефон";
		$fieldsName[] = "Email";
		$fieldsName[] = "Доп.информация";
		$fieldsName[] = "Редактирование";
		return $fieldsName;
	}
	
	function getTableRecords($tableName){		// get a record of fields for table
		$sql = "SELECT *
				FROM ".$tableName."";
		$records = mysql_query($sql,$this->dblink) or 
					$this->errorer(" getTableRecords for".$tableName." is a wrong");
		if(mysql_num_rows($records) == 0){
			$tableBody = "<tbody><tr><td colspan=10>NONE ".$this->tableName." dont have a records</td>
							</tr></tbody>";
		}
		else{
			$tableBody = "\t<tbody>\n";
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".$value['worker_id']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['firstname']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['lastname']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['passport_ser_num']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['hire_date']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['post_of_worker']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['phone']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['email']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['additional_info']."</td>\n";
				$tableBody .= "\t\t\t<td>".$this->getEditMenu($value['worker_id'],$this->rights,$tableName)."</td>\n";
				$tableBody .= "\t\t</tr>\n";
			}
			$tableBody .= "</tbody>\n";
		}
		@mysql_free_result($records);
		return $tableBody;
	}

}