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
		return $fieldsCount+1;
	}

	public function getFieldsName(){
		$fieldsName = array();
		$fieldsName[] = '№';
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
			$i = 0; // string number
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".++$i."</td>\n";
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

	function insDataToTable(){
			var_dump($_POST);
		}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"firm_name\">Название фирмы: </label>
							<input type=\"text\" name=\"firm_name\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"person\">Персона для контакта: </label>
							<input type=\"text\" name=\"person\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"phone\">Телефон: </label>
							<input type=\"text\" name=\"phone\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"fax_num\">Номер факса: </label>
							<input type=\"text\" name=\"fax_name\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"email\">Email: </label>
							<input type=\"text\" name=\"email\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"cl_type\">Тип клиента: </label>
							<input type=\"text\" name=\"cl_type\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"address\">Адрес клиента: </label>
							<input type=\"text\" name=\"address\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус клиента: </label>
							<input type=\"text\" name=\"status\">
							";
		print($htmlFormContent);
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
		return $fieldsCount+1; // because i return sim_id 
	}

	function getFieldsName(){
		$fieldsName = array();
		$fieldsName[] = '№';
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
			$i = 0; // string number
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".++$i."</td>\n";
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

	function insDataToTable(){
		var_dump($_POST);
	}

	function render(){
		$htmlFormContent= "";
		$htmlFormContent .= "\t\t\n<label for=\"NSim\">Сим номер: </label>
							<input type=\"text\" name=\"NSim\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"PNumber\">Телефонный номер: </label>
							<input type=\"text\" name=\"PNumber\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<select name=\"status\">
								<option >free</option>
								<option >busy</option>
								<option >blocked</option>
							</select>
							";
		//$htmlFormContent .= "\t\t\n<label for=\"crdate\">Дата создания: </label>
		//					<input type=\"text\" name=\"crdate\">
		//					";
		$htmlFormContent .= "\t\t\n<label for=\"lic\">Лицевой счет: </label>
							<input type=\"text\" name=\"lic\">
							";
		print($htmlFormContent);
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
		return $fieldsCount+1; // +1 for sim card number from tblsims 
	}

	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = '№';
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
			$i = 0; // string number
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".++$i."</td>\n";
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

	function insDataToTable(){
		var_dump($_POST);
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"NSim\">Сим номер: </label>
							<input type=\"text\" name=\"NSim\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"PNumber\">Телефонный номер: </label>
							<input type=\"text\" name=\"PNumber\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<input type=\"text\" name=\"status\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"crdate\">Дата создания: </label>
							<input type=\"text\" name=\"crdate\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"lic\">Лицевой счет: </label>
							<input type=\"text\" name=\"lic\"
							";
		print($htmlFormContent);
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
		return $fieldsCount+2;
	}


	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = '№';
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
			$i = 0; // string number
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".++$i."</td>\n";
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

	function insDataToTable(){
		var_dump($_POST);
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"NSim\">Сим номер: </label>
							<input type=\"text\" name=\"NSim\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"PNumber\">Телефонный номер: </label>
							<input type=\"text\" name=\"PNumber\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<input type=\"text\" name=\"status\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"crdate\">Дата создания: </label>
							<input type=\"text\" name=\"crdate\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"lic\">Лицевой счет: </label>
							<input type=\"text\" name=\"lic\"
							";
		print($htmlFormContent);
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
		return $fieldsCount+1;
	}

	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = '№';
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
			$i = 0; // string number
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".++$i."</td>\n";
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

	function insDataToTable(){
		var_dump($_POST);
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"NSim\">Сим номер: </label>
							<input type=\"text\" name=\"NSim\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"PNumber\">Телефонный номер: </label>
							<input type=\"text\" name=\"PNumber\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<input type=\"text\" name=\"status\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"crdate\">Дата создания: </label>
							<input type=\"text\" name=\"crdate\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"lic\">Лицевой счет: </label>
							<input type=\"text\" name=\"lic\"
							";
		print($htmlFormContent);
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
		return $fieldsCount+2;
	}

	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = "№";
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
			$i = 0; // string number
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".++$i."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['worker_id']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['fio']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['some_info']."</td>\n";
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

	function insDataToTable(){
		var_dump($_POST);
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"NSim\">Сим номер: </label>
							<input type=\"text\" name=\"NSim\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"PNumber\">Телефонный номер: </label>
							<input type=\"text\" name=\"PNumber\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<input type=\"text\" name=\"status\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"crdate\">Дата создания: </label>
							<input type=\"text\" name=\"crdate\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"lic\">Лицевой счет: </label>
							<input type=\"text\" name=\"lic\"
							";
		print($htmlFormContent);
	}
}

//for service table
class CViewServicesM extends Cviewer implements IViewer{

	function __construct($lnk,$rights){
		$this->tableName = 'tblservices';
		$this->message = 'Error in CViewServices class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount($dbname,$tableName){// get count of fields for table
		$qr = mysql_list_fields($dbname, $tableName) or $this->errorer("getFieldsCount ".$this->tableName." error");
		$fieldsCount = mysql_num_fields($qr);
		@mysql_free_result($qr);
		return $fieldsCount+2; // if was deleted one field
	}

	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = "№";
		$fieldsName[] = "Номер обслуживания";
		$fieldsName[] = "Дата начала";
		$fieldsName[] = "Дата окончания";
		$fieldsName[] = "Машина";
		$fieldsName[] = "Сотрудник на выполнение";
		$fieldsName[] = "Телефон";
		$fieldsName[] = "Статус";
		$fieldsName[] = "Описание";
		//$fieldsName[] = "Тип Сервиса";
		$fieldsName[] = "Редактирование";
		return $fieldsName;
	}
	
	function getTableRecords($tableName){		// get a record of fields for table
		$sql = "SELECT tblservices.*,tblworkers.fio,tblworkers.phone
				FROM tblservices 
				LEFT JOIN tblworkers ON tblworkers.worker_id = tblservices.tblWorkers\$worker_id 
				WHERE tblservices.serv_type = 1";
		$records = mysql_query($sql,$this->dblink) or 
					$this->errorer(" getTableRecords for ".$tableName." is a wrong");
		if(mysql_num_rows($records) == 0){
			$tableBody = "<tbody><tr><td colspan=10>NONE ".$this->tableName." dont have a records</td>
							</tr></tbody>";
		}
		else{
			$tableBody = "\t<tbody>\n";
			$i = 0; // string number
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".++$i."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['serv_id']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['start_date_time']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['finish_date_time']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['tblAutos$auto_id']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['fio']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['phone']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['status']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['description']."</td>\n";
				//$tableBody .= "\t\t\t<td>".$value['serv_type']."</td>\n";
				$tableBody .= "\t\t\t<td>".$this->getEditMenu($value['serv_id'],$this->rights,$tableName)."</td>\n";
				$tableBody .= "\t\t</tr>\n";
			}
			$tableBody .= "</tbody>\n";
		}
		@mysql_free_result($records);
		return $tableBody;
	}

	function insDataToTable(){
		var_dump($_POST);
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"NSim\">Сим номер: </label>
							<input type=\"text\" name=\"NSim\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"PNumber\">Телефонный номер: </label>
							<input type=\"text\" name=\"PNumber\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<input type=\"text\" name=\"status\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"crdate\">Дата создания: </label>
							<input type=\"text\" name=\"crdate\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"lic\">Лицевой счет: </label>
							<input type=\"text\" name=\"lic\"
							";
		print($htmlFormContent);
	}
}


class CViewServicesTS extends Cviewer implements IViewer{

	function __construct($lnk,$rights){
		$this->tableName = 'tblservices';
		$this->message = 'Error in CViewServices class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount($dbname,$tableName){// get count of fields for table
		$qr = mysql_list_fields($dbname, $tableName) or $this->errorer("getFieldsCount ".$this->tableName." error");
		$fieldsCount = mysql_num_fields($qr);
		@mysql_free_result($qr);
		return $fieldsCount+2; // if was deleted one field
	}

	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = "№";
		$fieldsName[] = "Номер обслуживания";
		$fieldsName[] = "Дата начала";
		$fieldsName[] = "Дата окончания";
		$fieldsName[] = "Машина";
		$fieldsName[] = "Сотрудник на выполнение";
		$fieldsName[] = "Телефон";
		$fieldsName[] = "Статус";
		$fieldsName[] = "Описание";
		//$fieldsName[] = "Тип Сервиса";
		$fieldsName[] = "Редактирование";
		return $fieldsName;
	}
	
	function getTableRecords($tableName){		// get a record of fields for table
		$sql = "SELECT tblservices.*,tblworkers.fio,tblworkers.phone
				FROM tblservices 
				LEFT JOIN tblworkers ON tblworkers.worker_id = tblservices.tblWorkers\$worker_id 
				WHERE tblservices.serv_type = 2";
		$records = mysql_query($sql,$this->dblink) or 
					$this->errorer(" getTableRecords for".$tableName." is a wrong");
		if(mysql_num_rows($records) == 0){
			$tableBody = "<tbody><tr><td colspan=10>NONE ".$this->tableName." dont have a records</td>
							</tr></tbody>";
		}
		else{
			$tableBody = "\t<tbody>\n";
			$i = 0; // string number
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".++$i."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['serv_id']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['start_date_time']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['finish_date_time']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['tblAutos$auto_id']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['fio']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['phone']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['status']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['description']."</td>\n";
				//$tableBody .= "\t\t\t<td>".$value['serv_type']."</td>\n";
				$tableBody .= "\t\t\t<td>".$this->getEditMenu($value['serv_id'],$this->rights,$tableName)."</td>\n";
				$tableBody .= "\t\t</tr>\n";
			}
			$tableBody .= "</tbody>\n";
		}
		@mysql_free_result($records);
		return $tableBody;
	}

	function insDataToTable(){
		var_dump($_POST);
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"NSim\">Сим номер: </label>
							<input type=\"text\" name=\"NSim\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"PNumber\">Телефонный номер: </label>
							<input type=\"text\" name=\"PNumber\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<input type=\"text\" name=\"status\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"crdate\">Дата создания: </label>
							<input type=\"text\" name=\"crdate\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"lic\">Лицевой счет: </label>
							<input type=\"text\" name=\"lic\"
							";
		print($htmlFormContent);
	}
}