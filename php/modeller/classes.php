<?php
/*
	Viewer controller, defining view table for selected category
*/
require_once("/../modeller/connecter.php");

	//interface method for abstract Viewer class and implements it to child class with values and methods
interface IViewer{
	function getFullObjStat()			;	// get full statistic of instance of object
	function getFieldsCount()			;	// get count of fields for table
	function getFieldsName()			;	// get a names of fields of table
	function getTableRecords($tableName);	// get a record of fields for table
}

//abstract class with generic methods for all classes
abstract class CViewer{
	protected $dblink;   // link to connect to db
	protected $rights;   // user access rights for work with data 
	protected $message;  // error message
	protected $tableName; //Table Name for category
	public function viewCatData($dbname){
		$countTHead = $this->getFieldsCount();
		$tHeadName = $this->getFieldsName();
		$tableHead = "\t<thead><tr>\n";
		for($i=0; $i<$countTHead; ++$i){
			$tableHead .= "\t\t<th>".$tHeadName[$i]."</th>\n";
		}
		$tableHead .= "</tr></thead>\n";
		echo($tableHead);
		$tableBody=$this->getTableRecords($this->tableName);
		echo ($tableBody);
	}

	public function getEditMenu($recID,$rights,$tableName){
		switch($rights){
			case 'SUID': return "<a href=\"/../php/controller/rec_editor.php?id={$recID}&action=editid&table={$tableName}\">Изменить</a> 
						&nbsp;&nbsp;<a href=\"/../php/controller/rec_editor.php?id={$recID}&action=deleteid&table={$tableName}\">Удалить</a>";
			case 'SUI' : 
			case 'SU'  : return "<a href=\"/../php/controller/rec_editor.php?id={$recID}&action=editid&table={$tableName}\">Изменить</a>";
			default    : return "--------";
		}
	}

	protected function errorer($message){
		die($message);
	}

	public function insertData(){
		if(!mysql_query($this->insDataToTable(),$this->dblink)){
			echo("Запрос на добавление произведен с ошибкой! Проверьте правильность заполнения полей или обратитесь к администратору!");
		}
		else{
			echo("Запрос на добавление данных произведен успешно!");
		}
	}

	public function commonStat(){		//may be need more parameters at future
		$arr = $this->getFullObjStat();	//fullObjStat() - a method for full statistic about object instance
		$html = "<table class=\"iw-table table-condensed\"><tbody>";
		foreach ($arr as $key => $value) {
			$html .= "\t<tr><td style=\"text-align:right\">{$key}  : </td><td>{$value}</td></tr>\n";
		}
		$html .= "</tbody></table>";
		print($html);
	}

	function getStatForMP($param){
		// header of table
		$sql = "SELECT start_date_time AS sdt, finish_date_time AS fdt, description, tblautos.gos_num AS gn,tblworkers.fio AS fio,tblworkers.phone AS phone 
				FROM tblservices AS s
				INNER JOIN tblautos ON s.tblAutos\$auto_id = tblautos.auto_id
				INNER JOIN tblworkers ON s.tblWorkers\$worker_id = tblworkers.worker_id
				WHERE s.serv_type = '{$param}'
				AND (s.status = 'not done')
				LIMIT 0 , 5	
				";
		$res = mysql_query($sql,$this->dblink) or $this->errorer("getTableRecords for ".$this->tableName. "is wrong!!");
		
		$head = "\t<thead>\n";
		$head .= "\t\t<tr>\n";
		$head .= "\t\t\t<th class=\"fxw\">№</th>\n";
		$head .= "\t\t\t<th>Дата начала</th>\n";
		$head .= "\t\t\t<th>Конечная дата</th>\n";
		$head .= "\t\t\t<th>Номер машины</th>\n";
		$head .= "\t\t\t<th>Рабочий</th>\n";
		$head .= "\t\t\t<th>Телефон работника</th>\n";
		$head .= "\t\t\t<th>Описание работы</th>\n";
		$head .= "\t\t</tr>\n";
		$head .= "\t</thead>\n";
		print($head);

		if(mysql_num_rows($res) == 0){
			$err = "\t<tbody>\n \t\t<tr>\t\t\t<td colspan=8> Нет задач для монатажа, чей статус - not done или performed</td>\n</tr>\n \t</tbody>\n";
			print($err);
		}
		else{
			//body of table
			$body = "\t<tbody>\n";
			$i = 0;	// string number
			while($value = mysql_fetch_array($res,MYSQL_ASSOC)){
				$body .= "\t\t<tr>\n";
				$body .= "\t\t\t<td>".++$i."</td>\n";
				$body .= "\t\t\t<td>".$value['sdt']."</td>\n";
				$body .= "\t\t\t<td>".$value['fdt']."</td>\n";
				$body .= "\t\t\t<td>".$value['gn']."</td>\n";
				$body .= "\t\t\t<td>".$value['fio']."</td>\n";
				$body .= "\t\t\t<td>".$value['phone']."</td>\n";
				$body .= "\t\t\t<td>".$value['description']."</td>\n";
				$body .= "\t\t</tr>\n";
			}
			$body .= "\t</tbody>\n";
			print($body);
		}
	}
}





/*********************************************************************
*		Definition and Declaration classes from abstract class 		 *
**********************************************************************/

/*-------------------Start of Client Class---------------------------*/
class CViewClients extends CViewer implements IViewer{
	function __construct($lnk,$rights){
		$this->tableName = 'tblclients';
		$this->message = 'Error in viewClients class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	public function getFieldsCount(){
		return sizeof($this->getFieldsName());
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
		//$fieldsName[] = 'Фото';
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
				//$tableBody .= "\t\t\t<td>".$value['foto']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['status']."</td>\n";
				$tableBody .= "\t\t\t<td>".$this->getEditMenu($value['client_id'],$this->rights,$tableName)."</td>\n";
				$tableBody .= "\t\t</tr>\n";
			}
			$tableBody .="</tbody>\n";
		}
		@mysql_free_result($records);
		return $tableBody;
	}

	function jsFormValid(){
		$jsValid = "if(!$('input[name=firm_name]').val()) {alert('firm_name is empty'); return false;}\n";
		$jsValid .= "if(!$('input[name=person]').val()) {alert('person is empty'); return false;}\n";
		$jsValid .= "if(!$('input[name=phone]').val()) {alert('phone is empty'); return false;}\n";
		$jsValid .= "if(!$('input[name=fax_num]').val()) {alert('fax_num is empty'); return false;}\n";
		$jsValid .= "if(!$('input[name=email]').val()) {alert('email is empty'); return false;}\n";
		$jsValid .= "if(!$('input[name=address]').val()) {alert('address is empty'); return false;}\n";
		print_r($jsValid."\n");
	}

	function insDataToTable(){
		$firmName = mysql_real_escape_string($_POST['firm_name']);
		$person   = mysql_real_escape_string($_POST['person']   );
		$phone    = mysql_real_escape_string($_POST['phone']    );
		$fax_num  = mysql_real_escape_string($_POST['fax_num']  );
		$email    = mysql_real_escape_string($_POST['email']    );
		$cl_type  = mysql_real_escape_string($_POST['cl_type']  );
		$address  = mysql_real_escape_string($_POST['address']  );
		$status   = mysql_real_escape_string($_POST['status']   );
		$sql = "INSERT INTO `tblclients`(`client_id`, `firm_name`, `contact_person`, `phone_num`, `fax_num`, `e_mail`, `cl_type`, `address`, `foto`, `status`) 
				VALUES (NULL,'{$firmName}','{$person}','{$phone}','{$fax_num}','{$email}','{$cl_type}','{$address}',NULL,'{$status}') 
				";
		return $sql;
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
							<input type=\"text\" name=\"fax_num\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"email\">Email: </label>
							<input type=\"text\" name=\"email\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"cl_type\">Тип клиента: </label>
							<select name=\"cl_type\">
								<option>оао</option>
								<option>зао</option>
								<option>ип </option>
								<option>ооо</option>
								<option>чл </option>
							</select>
							";
		$htmlFormContent .= "\t\t\n<label for=\"address\">Адрес клиента: </label>
							<input type=\"text\" name=\"address\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус клиента: </label>
							<select name=\"status\">
								<option>active</option>
								<option>blocked</option>
								<option>black_list</option>
							</select>
							";
		print($htmlFormContent);
	}

	function getFullObjStat(){
		$sql = "SELECT `status`
				FROM {$this->tableName}
				";
		$res = mysql_query($sql,$this->dblink) or $this->errorer("getFullObjStat for ".$this->tableName." is wrong");
		if(mysql_num_rows($res)==0){
			$stat = "записей нет";
			return $stat;
		}
		else{
			$h = $j = $k = 0;
			while($value = mysql_fetch_array($res,MYSQL_ASSOC)){
				switch ($value['status']) {
					case 'active'    : ++$h; break;
					case 'blocked'   : ++$j; break;
					case 'black_list': ++$k; break;
					default          : break;
				}
			}
			$stat = array('Действующий'=>$h , 'Блокированный'=>$j , 'Черный список'=>$k, 'Всего'=>$h+$j+$k);
			return $stat;
		}
	}
}
/*-------------------End of Client Class---------------------------*/


//------------------------------------------------------------------------------------------------------


/*-------------------Start of Simcards Class---------------------------*/
class CViewSimcards extends CViewer implements IViewer{
	function __construct($lnk,$rights){
		$this->tableName = 'tblsims';
		$this->message = 'Error in CViewSimcards class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount(){
		return sizeof($this->getFieldsName());
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
		$sql = "SELECT * FROM `{$this->tableName}` ORDER BY `status`";
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

	function jsFormValid(){
		$jsValid = "if(!$('input[name=NSim]').val()){alert('SimNum is empty'); return false;}\n";
		$jsValid .= "if(!$('input[name=PNumber]').val()){alert('Phone Num is empty'); return false;}\n";
		$jsValid .= "if(!$('input[name=lic]').val()){alert('Lic schet is empty'); return false;}\n";
		print_r($jsValid."\n");
	}

	function insDataToTable(){
		$nsim = mysql_real_escape_string($_POST['NSim']);
		$pnum = mysql_real_escape_string($_POST['PNumber']);
		$stat = mysql_real_escape_string($_POST['status']);
		$lic = mysql_real_escape_string($_POST['lic']);
		$sql = "INSERT INTO `tblsims`(`sim_id`, `sim_number`, `phone_number`, `status`, `dateofcreate`, `lic_schet`) 
				VALUES (NULL,'{$nsim}','{$pnum}','{$stat}',CURDATE(),'{$lic}')
				";
		return $sql;
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

	function getFullObjStat(){
		$sql = "SELECT `status`
				FROM {$this->tableName}
				";
		$res = mysql_query($sql,$this->dblink) or $this->errorer("getFullObjStat for ".$this->tableName." is wrong");
		if(mysql_num_rows($res)==0){
			$stat = "записей нет";
			return $stat;
		}
		else{
			$h = $j = $k = 0;
			while($value = mysql_fetch_array($res,MYSQL_ASSOC)){
				switch ($value['status']) {
					case 'free'   : ++$h; break;
					case 'busy'   : ++$j; break;
					case 'blocked': ++$k; break;
					default       : break;
				}
			}
			$stat = array('Свободно'=>$h , 'Используется'=>$j , 'Блокировано'=>$k, 'Всего'=>$h+$j+$k);
			return $stat;
		}
	}
}
/*-------------------End of Simcards Class---------------------------*/


//------------------------------------------------------------------------------------------------------


/*-------------------Start of Devices Class--------------------------*/
class CViewDevices extends CViewer implements IViewer{
	function __construct($lnk,$rights){
		$this->tableName = 'tbldevices';
		$this->message = 'Error in viewDevices class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount(){// get count of fields for table
		return sizeof($this->getFieldsName());
	}

	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = '№';
		$fieldsName[] = 'Модель прибора';
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

	function jsFormValid(){
		$jsValid = "if(!$('input[name=dev_name]').val()){alert('Dev name is empty'); return false;}\n";
		$jsValid .= "if(!$('input[name=serial]').val()){alert('Serial is empty'); return false;}\n";
		$jsValid .= "if(!$('input[name=imei]').val()){alert('IMEI is empty'); return false;}\n";
		print_r($jsValid."\n");
	}

	function insDataToTable(){
		$devname = mysql_real_escape_string($_POST['dev_name']);
		$serial = mysql_real_escape_string($_POST['serial']);
		$imei = mysql_real_escape_string($_POST['imei']);
		$status = mysql_real_escape_string($_POST['status']);
		$sql = "INSERT INTO `tbldevices`(`dev_id`, `dev_name`, `serial_number`, `IMEI`, `status`, `dev_date`) 
				VALUES (NULL,'{$devname}','{$serial}','{$imei}','{$status}',CURDATE())
				";
		return $sql;
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"dev_name\">Модель прибора: </label>
							<input type=\"text\" name=\"dev_name\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"serial\">Серийный номер: </label>
							<input type=\"text\" name=\"serial\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"imei\">IMEI: </label>
							<input type=\"text\" name=\"imei\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<select name=\"status\">
								<option>free</option>
								<option>busy</option>
								<option>reserve</option>
								<option>defects</option>
							</select>
							";
		print($htmlFormContent);
	}

	function getFullObjStat(){
		$sql = "SELECT `status`
				FROM {$this->tableName}
				";
		$res = mysql_query($sql,$this->dblink) or $this->errorer("getFullObjStat for ".$this->tableName."is wrong");
		if(mysql_num_rows($res)==0){
			$stat = 'записей нет';
			return $stat;
		}
		else{
			$k = 0; // free
			$l = 0; // busy
			$j = 0; // reserve
			$h = 0; // defects
			while($value = mysql_fetch_assoc($res,MYSQL_ASSOC))
			{
				switch($value['status'])
				{
					case 'free'   : ++$k; break;
					case 'busy'   : ++$l; break;
					case 'reserve': ++$j; break;
					case 'defects': ++$h; break;
					default       :	break;
				}
			}
			$stat = array('Свободно'=>$k,'Используется'=>$l,'Резерв'=>$j,'Дефект'=>$h, 'Всего'=>$k+$l+$j+$h);
			return $stat;
		}
	}
}
/*-------------------End of Devices Class--------------------------*/


//------------------------------------------------------------------------------------------------------


/*-------------------Start of Sensors Class------------------------*/
class CViewSensors extends CViewer implements IViewer{

	function __construct($lnk,$rights){
		$this->tableName = 'tblsensors';
		$this->message = 'Error in CViewSensors class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount(){// get count of fields for table
		return sizeof($this->getFieldsName());
	}


	function getFieldsName(){					// get a names of fields of table
		$fieldsName = array();
		$fieldsName[] = '№';
		//$fieldsName[] = "ID датчика";
		$fieldsName[] = "Тип модели";
		$fieldsName[] = "Серийный номер";
		$fieldsName[] = "Статус";
		$fieldsName[] = "Дата внесения записи";
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
			$tableBody = "<tbody><tr><td colspan=8>NONE ".$this->tableName." dont have a records</td>
							</tr></tbody>";
		}
		else{
			$tableBody = "\t<tbody>\n";
			$i = 0; // string number
			while($value = mysql_fetch_array($records,MYSQL_ASSOC)){
				$tableBody .= "\t\t<tr>\n";
				$tableBody .= "\t\t\t<td>".++$i."</td>\n";
				//$tableBody .= "\t\t\t<td>".$value['sens_id']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['model_type']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['sens_serial']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['sens_status']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['create_date']."</td>\n";
				$tableBody .= "\t\t\t<td>".$value['tblDevices_dev_id']."</td>\n";
				$tableBody .= "\t\t\t<td>".$this->getEditMenu($value['sens_id'],$this->rights,$tableName)."</td>\n";
				$tableBody .= "\t\t</tr>\n";
			}
			$tableBody .= "</tbody>\n";
		}
		return $tableBody;
	}

	function jsFormValid(){
		$jsValid = "if(!$('input[name=model]').val()){alert('Model is empty'); return false;}\n";
		$jsValid .= "if(!$('input[name=serial]').val()){alert('Serial is empty'); return false;}\n";
		print_r($jsValid."\n");
	}

	function insDataToTable(){
		$model = mysql_real_escape_string($_POST['model']);
		$serial = mysql_real_escape_string($_POST['serial']);
		$status = mysql_real_escape_string($_POST['status']);
		$devid = mysql_real_escape_string($_POST['dev_id']);
		$sql = "INSERT INTO `tblsensors`(`sens_id`, `model_type`, `sens_serial`, `sens_status`, `create_date`, `tblDevices_dev_id`) 
				VALUES (NULL,'{$model}','{$serial}','{$status}',CURDATE(),'{$devid}')
				";
		return $sql;
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"model\">Модель датчика: </label>
							<input type=\"text\" name=\"model\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"serial\">Серийный номер: </label>
							<input type=\"text\" name=\"serial\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<select name=\"status\">
								<option selected>not used</option>
								<option>used</option>
								<option>defected</option>
							</select>
							";
		$htmlFormContent .= "\t\t\n<label for=\"dev_id\">Идентификатор прибора: </label>
							<input type=\"text\" name=\"dev_id\">
							";
		print($htmlFormContent);
	}

	function getFullObjStat(){
		$sql = "SELECT `sens_status`
				FROM {$this->tableName}
				";
		$res = mysql_query($sql,$this->dblink) or $this->errorer("getFullObjStat for ".$this->tableName." is wrong");
		if(mysql_num_rows($res)==0){
			$stat = "записей нет";
			return $stat;
		}
		else{
			$h = $j = $k = 0;
			while($value = mysql_fetch_array($res,MYSQL_ASSOC)){
				switch ($value['sens_status']) {
					case 'using'   : ++$h; break;
					case 'not_used': ++$j; break;
					case 'defected': ++$k; break;
					default        : 
									 break;
				}
			}
			$stat = array('Используется' => $h, 'Не используется' => $j, 'Дефект' => $k, 'Всего'=>$h+$j+$k);
			return $stat;
		}
	}
}
/*-------------------End of Sensors Class------------------------*/


//------------------------------------------------------------------------------------------------------


/*-------------------Start of Autos Class------------------------*/
class CViewAutos extends CViewer implements IViewer{
	function __construct($lnk,$rights){
		$this->tableName = 'tblautos';
		$this->message = 'Error in CViewAutos class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount(){// get count of fields for table
		return sizeof($this->getFieldsName());
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

	function jsFormValid(){
		$jsValid = "if(!$('input[name=gnum]').val()){alert('Гос номер пуст'); return false;}\n";
		$jsValid .= "if(!$('input[name=vin]').val()){alert('VIN пустое поле'); return false;}\n";
		$jsValid .= "if(!$('input[name=marka]').val()){alert('Поле марка пуста'); return false;}\n";
		$jsValid .= "if(!$('input[name=cdate]').val()){alert('Дата подключение пусто'); return false;}\n";
		$jsValid .= "if(!$('input[name=client]').val()){alert('Не указан номер клиента'); return false;}\n";
		print_r($jsValid."\n");
	}

	function insDataToTable(){
		$gnum = mysql_real_escape_string($_POST['gnum']);
		$vin = mysql_real_escape_string($_POST['vin']);
		$marka = mysql_real_escape_string($_POST['marka']);
		$status = mysql_real_escape_string($_POST['status']);
		$cdate = mysql_real_escape_string($_POST['cdate']);
		$client = mysql_real_escape_string($_POST['client']);
		$sql = "INSERT INTO `tblautos`(`auto_id`, `gos_num`, `vin`, `marka`, `status`, `conn_date`, `tblclients_client_id`) 
				VALUES (NULL,'{$gnum}','{$vin}','{$marka}','{$status}','{$cdate}','{$client}')
				";
		return $sql;
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"gnum\">Государственный номер: </label>
							<input type=\"text\" name=\"gnum\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"vin\">VIN: </label>
							<input type=\"text\" name=\"vin\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"marka\">Марка автомобиля: </label>
							<input type=\"text\" name=\"marka\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<select name=\"status\">
								<option>not_active</option>
								<option>active</option>
							</select>
							";
		$htmlFormContent .= "\t\t\n<label for=\"cdate\">Дата подключения: </label>
							<input type=\"text\" name=\"cdate\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"client\">Клиент: </label>
							<input type=\"text\" name=\"client\">
							";
		print($htmlFormContent);
	}

	function getFullObjStat(){
		$sql = "SELECT `status`
				FROM {$this->tableName}
				";
		$res = mysql_query($sql,$this->dblink) or $this->errorer("getFullObjStat for ".$this->tableName." is wrong");
		if(mysql_num_rows($res)==0){
			$stat = "записей нет";
			return $stat;
		}
		else{
			$h = $j = 0;
			while($value = mysql_fetch_array($res,MYSQL_ASSOC)){
				switch ($value['status']) {
					case 'active'    : ++$h; break;
					case 'not_active': ++$j; break;
					default          : break;
				}
			}
			$stat = array('Активно' => $h, 'Не активно' => $j,'Всего'=>$h+$j);
			return $stat;
		}
	}
}
/*-------------------End of Autos Class------------------------*/


//------------------------------------------------------------------------------------------------------


/*-------------------Start of Workers Class------------------------*/
class CViewWorkers extends CViewer implements IViewer{
	function __construct($lnk,$rights){
		$this->tableName = 'tblworkers';
		$this->message = 'Error in CViewWorkers class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount(){// get count of fields for table
		return sizeof($this->getFieldsName());
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

	function jsFormValid(){
		$jsValid = "if(!$('input[name=fio]').val()){alert('ФИО необходимо заполнить'); return false;}\n";
		$jsValid .= "if(!$('input[name=pass_num]').val()){alert('Паспортные данные пусты'); return false;}\n";
		$jsValid .= "if(!$('input[name=post]').val()){alert('Не указана должность'); return false;}\n";
		$jsValid .= "if(!$('input[name=phone]').val()){alert('Телефон не указан'); return false;}\n";
		print_r($jsValid."\n");
	}

	function insDataToTable(){
		$fio = mysql_real_escape_string($_POST['fio']);
		$info = mysql_real_escape_string($_POST['info']);
		$pass = mysql_real_escape_string($_POST['pass_num']);
		$post = mysql_real_escape_string($_POST['post']);
		$phone = mysql_real_escape_string($_POST['phone']);
		$email = mysql_real_escape_string($_POST['email']);
		$add_info = mysql_real_escape_string($_POST['add_info']);
		$sql = "INSERT INTO `tblworkers`(`worker_id`, `fio`, `some_info`, `passport_ser_num`, `hire_date`, `post_of_worker`, `phone`, `email`, `additional_info`)
				VALUES (NULL,'{$fio}','{$info}','{$pass}',CURDATE(),'{$post}','{$phone}','{$email}','{$add_info}')
				";
		return $sql;
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"fio\">ФИО: </label>
							<input type=\"text\" name=\"fio\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"info\">Информация: </label>
							<textarea name=\"info\" rows=6 cols=10></textarea>
							";
		$htmlFormContent .= "\t\t\n<label for=\"pass_num\">Паспорт (серия, номер): </label>
							<input type=\"text\" name=\"pass_num\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"post\">Должность: </label>
							<input type=\"text\" name=\"post\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"phone\">Телеофон: </label>
							<input type=\"text\" name=\"phone\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"email\">EMAIL: </label>
							<input type=\"text\" name=\"email\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"add_inf\">Дополнительная информация: </label>
							<textarea name=\"add_info\" rows=6 cols=10></textarea>
							";
		print($htmlFormContent);
	}

	function getFullObjStat(){
		/*TODO*/
	}
}
/*-------------------End of Workers Class------------------------*/


//------------------------------------------------------------------------------------------------------


/*-------------------Start of ServicesM Class------------------------*/
class CViewServicesM extends CViewer implements IViewer{
	function __construct($lnk,$rights){
		$this->tableName = 'tblservices';
		$this->message   = 'Error in CViewServices class, check methods';
		$this->dblink    = $lnk;
		$this->rights    = $rights;
	}

	function getFieldsCount(){// get count of fields for table
		return sizeof($this->getFieldsName());
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

	function jsFormValid(){
		$jsValid = "if(!$('input[name=fin_date]').val()){alert('Не выставлена дата окончания'); return false;}\n";
		$jsValid .= "if(!$('input[name=auto_id]').val()){alert('Не указана машина для монтажа'); return false;}\n";
		$jsValid .= "if(!$('input[name=worker]').val()){alert('Не определен работник для исполнения'); return false;}\n";
		print_r($jsValid."\n");
	}

	function insDataToTable(){
		$fin_date = mysql_real_escape_string($_POST['fin_date']);
		$auto = mysql_real_escape_string($_POST['auto_id']);
		$worker = mysql_real_escape_string($_POST['worker']);
		$stat = mysql_real_escape_string($_POST['status']);
		$descr = mysql_real_escape_string($_POST['descr']);
		$sql = "INSERT INTO `tblservices`(`serv_id`, `start_date_time`, `finish_date_time`, `tblAutos\$auto_id`, `tblWorkers\$worker_id`, `status`, `description`, `serv_type`)
				VALUES (NULL,CURDATE(),'{$fin_date}','{$auto}','{$worker}','{$stat}','{$descr}','m')
				";
		return $sql;
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"fin_date\">Дата окончания: </label>
							<input type=\"text\" name=\"fin_date\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"auto_id\">ID Машины: </label>
							<input type=\"text\" name=\"auto_id\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"worker\">ID Работник: </label>
							<input type=\"text\" name=\"worker\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<select name=\"status\">
								<option selected>not done</option>
								<option>performed</option>
								<option>done</option>
							</select>
							";
		$htmlFormContent .= "\t\t\n<label for=\"descr\">Описание: </label>
							<textarea rows=7 cols=16 name=\"descr\"></textarea>
							";
		print($htmlFormContent);
	}

	// метод останется, так как для каждой страницы с объектом, в шапке, потребуется выводить полную статистику
	// поэтому решено разбить вывод статы на две части: главная панель getStatForMP и getFullObjStat
	// хотя это такая шляпа
	function getFullObjStat(){
		$sql = "SELECT `status`
				FROM {$this->tableName}
				WHERE `serv_type`='m'";
		$res = mysql_query($sql,$this->dblink) or $this->errorer("getFullObjStat for ".$this->tableName." is wrong");
		if(mysql_num_rows($res)==0){
			$stat = "записей нет";
			return $stat;
		}
		else{
			$h = $j = $k = 0;
			while($value = mysql_fetch_array($res,MYSQL_ASSOC)){
				switch ($value['status']) {
					case 'done'     : ++$h; break;
					case 'not done' : ++$j; break;
					case 'performed': ++$k; break;
					default          : break;
				}
			}
			$stat = array('Завершено' => $h, 'Не завершено' => $j, 'Текущих' => $k ,'Всего'=>$h+$j+$k);
			return $stat;
		}
	}
}
/*-------------------End of ServicesM Class------------------------*/


//------------------------------------------------------------------------------------------------------


/*-------------------Start of ServicesTS Class------------------------*/
class CViewServicesTS extends CViewer implements IViewer{

	function __construct($lnk,$rights){
		$this->tableName = 'tblservices';
		$this->message = 'Error in CViewServices class, check methods';
		$this->dblink = $lnk;
		$this->rights = $rights;
	}

	function getFieldsCount(){// get count of fields for table
		return sizeof($this->getFieldsName());
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

	function jsFormValid(){
		$jsValid = "if(!$('input[name=fin_date]').val()){alert('Не выставлена дата окончания'); return false;}\n";
		$jsValid .= "if(!$('input[name=auto_id]').val()){alert('Не указана машина для монтажа'); return false;}\n";
		$jsValid .= "if(!$('input[name=worker]').val()){alert('Не определен работник для исполнения'); return false;}\n";
		print_r($jsValid."\n");
	}

	function insDataToTable(){
		$fin_date = mysql_real_escape_string($_POST['fin_date']);
		$auto = mysql_real_escape_string($_POST['auto_id']);
		$worker = mysql_real_escape_string($_POST['worker']);
		$stat = mysql_real_escape_string($_POST['status']);
		$descr = mysql_real_escape_string($_POST['descr']);
		$sql = "INSERT INTO `tblservices`(`serv_id`, `start_date_time`, `finish_date_time`, `tblAutos\$auto_id`, `tblWorkers\$worker_id`, `status`, `description`, `serv_type`)
				VALUES (NULL,CURDATE(),'{$fin_date}','{$auto}','{$worker}','{$stat}','{$descr}','ts')
				";
		return $sql;
	}

	function render(){
		$htmlFormContent = "\t\t\n<label for=\"fin_date\">Дата окончания: </label>
							<input type=\"text\" name=\"fin_date\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"auto_id\">ИД машины: </label>
							<input type=\"text\" name=\"auto_id\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"worker\">Работник: </label>
							<input type=\"text\" name=\"worker\">
							";
		$htmlFormContent .= "\t\t\n<label for=\"status\">Статус: </label>
							<select name=\"status\">
								<option selected>not done</option>
								<option>performed</option>
								<option>done</option>
							</select>
							";
		$htmlFormContent .= "\t\t\n<label for=\"descr\">Описание: </label>
							<textarea rows=7 cols=16 name=\"descr\"></textarea>
							";
		print($htmlFormContent);
	}

	// метод останется, так как для каждой страницы с объектом, в шапке, потребуется выводить полную статистику
	// поэтому решено разбить вывод статы на две части: главная панель getStatForMP и getFullObjStat
	function getFullObjStat(){
		$sql = "SELECT `status`
				FROM {$this->tableName}
				WHERE `serv_type`='ts'";
		$res = mysql_query($sql,$this->dblink) or $this->errorer("getFullObjStat for ".$this->tableName." is wrong");
		if(mysql_num_rows($res)==0){
			$stat = "записей нет";
			return $stat;
		}
		else{
			$h = $j = $k = 0;
			while($value = mysql_fetch_array($res,MYSQL_ASSOC)){
				switch ($value['status']) {
					case 'done'     : ++$h; break;
					case 'not done' : ++$j; break;
					case 'performed': ++$k; break;
					default          : break;
				}
			}
			$stat = array('Завершено' => $h, 'Не завершено' => $j, 'Текущих' => $k ,'Всего'=>$h+$j+$k);
			return $stat;
		}
	}
}
/*-------------------End of ServicesTS Class------------------------*/


//------------------------------------------------------------------------------------------------------