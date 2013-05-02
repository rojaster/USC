Database scheme {/*db_struct_diagramm.jpeg*/
		tblClients 1-$> tblAutos (client_id)
		tblSims <1-1> tblDevices(sim_id) /*моделируем через ассоциативную таблицу*/
		tblDevices 1-$>tblSensors(dev_id)
		tblDevices <1-1> tblAutos(dev_id) /*моделируем через ассоциативную таблицу*/
		tblWorkers <1-1> auth /*воркеры, которые работают с системой*/
		tblServices(auto_id,worker_id,)
}


/*создание таблицы клиенты*/
CREATE TABLE tblClients(
						client_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
						firm_name VARCHAR(150) CHARACTER SET utf8 NOT NULL DEFAULT 'not defined',
						contact_person VARCHAR(150) CHARACTER SET utf8 NOT NULL DEFAULT 'not defined',
						phone_num int(20) UNSIGNED NOT NULL DEFAULT '0',
						fax_num int(20) UNSIGNED NOT NULL DEFAULT '0',
						e_mail VARCHAR(50) CHARACTER SET utf8 NOT NULL DEFAULT 'not defined',
						cl_type ENUM("fl","ul") CHARACTER SET utf8 NULL,
						address TEXT CHARACTER SET utf8 , 
						foto int(10) UNSIGNED DEFAULT NULL,
						status ENUM("active","blocked","black_list") CHARACTER SET utf8 NULL,
						PRIMARY KEY(client_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8
/*Тестовые данные клиента*/
INSERT INTO `tblclients`(`client_id`, `firm_name`, `contact_person`, `phone_num`, `fax_num`, `e_mail`, `cl_type`, `address`,  `status`) 
VALUES ('','barabara','ai se te pegu',73462487485,73462548721,'bara@ya.ru','fl','ул.Маяковского 32 дом 5','active');
INSERT INTO `tblclients`(`client_id`, `firm_name`, `contact_person`, `phone_num`, `fax_num`, `e_mail`, `cl_type`, `address`, `foto`, `status`) 
VALUES ('','someFirm','Виктор Пелевин',79234567896,75229633,'sf@ya.ru','ul','ул.Нефтюганское шоссе',floor(rand()),'active');
INSERT INTO `tblclients`(`client_id`, `firm_name`, `contact_person`, `phone_num`, `fax_num`, `e_mail`, `cl_type`, `address`, `status`) 
VALUES ('','СНГ','Богданов',73462407040,73462456987,'bogdanov@sng.ru','ul','Бизнес центр','active');
INSERT INTO `tblclients`(`client_id`, `firm_name`, `contact_person`, `phone_num`, `fax_num`, `e_mail`, `cl_type`, `address`, `foto`, `status`) 
VALUES ('','sherbac','Щербаков Щербацкий Щербанович',7224567812,73462324568,'sherbakov@ya.ru','fl','ул.Маяковского 32 дом 5',floor(rand()),'black_list');




/*создание таблицы автомобили, связываем с tblClients через tblClients_client_id , создаем так,
что машина без клиента не существует, удаляется клиент, удаляем все записи о нем,
программно сохраняем о нем записи в файло, все возможные, а потом удаляем все, что с ним связано,
допустим клиент может уйти, потом прийти, таким образом мы его восстановим, для истории*/
CREATE TABLE tblAutos(
						`auto_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
						`gos_num` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT 'not def',
						`vin` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT 'not def',
						`marka` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT 'not def',
						`status` ENUM("active","not_active") CHARACTER SET utf8 NULL,
						`conn_date` date DEFAULT '0000-00-00',
						`tblclients_client_id` int(10) UNSIGNED NOT NULL,
						PRIMARY KEY(`auto_id`),
						CONSTRAINT `tblAutos_fk_1`
							FOREIGN KEY `tblclietns_client_id`(`tblclients_client_id`)
							REFERENCES `tblClients`(`client_id`)
							ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8
/*Тестовые данные для авто*/
INSERT INTO `tblautos`(`auto_id`, `gos_num`, `vin`, `marka`, `status`, `conn_date`, `tblclients_client_id`) 
VALUES ('','к456вч86','123456789','reno','active',CURDATE(),5);
INSERT INTO `tblautos`(`auto_id`, `gos_num`, `vin`, `marka`, `status`, `conn_date`, `tblclients_client_id`) 
VALUES ('','в789уч70','741852963','mersedes','not_active',CURDATE(),5);
INSERT INTO `tblautos`(`auto_id`, `gos_num`, `vin`, `marka`, `status`, `conn_date`, `tblclients_client_id`) 
VALUES ('','п345гр56','147258369','вольво','active',CURDATE(),6);
INSERT INTO `tblautos`(`auto_id`, `gos_num`, `vin`, `marka`, `status`, `conn_date`, `tblclients_client_id`) 
VALUES ('','д183ск67','978456321','камаз','active',CURDATE(),7);
INSERT INTO `tblautos`(`auto_id`, `gos_num`, `vin`, `marka`, `status`, `conn_date`, `tblclients_client_id`) 
VALUES ('','з454шк','9517532584456','mitsubishi','not_active',CURDATE(),8);




/*создание таблицы приборы, делаем связь с таблицей tblSims через tblSims_sims_id, 
чтобы потом смотреть на каком приборе есть симка, кто без симки - устанавливая либо ее id, либо 0*/
CREATE TABLE `tblDevices` (
 `dev_id` int(30) unsigned NOT NULL AUTO_INCREMENT,
 `dev_name` varchar(150) CHARACTER SET utf8 DEFAULT 'not defined',
 `dev_number` int(30) unsigned DEFAULT '0',
 `serial_number` int(30) unsigned DEFAULT '0',
 `IMEI` int(20) unsigned DEFAULT '0',
 `status` ENUM("free","busy","reserve","defects") DEFAULT NULL,
 `dev_date` date DEFAULT '0000-00-00',
  PRIMARY KEY (`dev_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

/*Создание дополнительной ассоциативной таблицы для моделирования связи один к одному
Необходимо для того, чтобы избежать проблем обновления и удаления записей о связи симкарт и девайсов*/
CREATE TABLE `simdev`(
						`r_id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`tblDevices_dev_id` int(30) unsigned NOT NULL,
						`tblSims_sims_id` int(10) NOT NULL,
						CONSTRAINT `tblDevices_fk1`
							FOREIGN KEY `tblDevices_dev_id`(`tblDevices_dev_id`)
							REFERENCES `tblDevices`(`dev_id`)
							ON DELETE CASCADE ON UPDATE CASCADE,
						CONSTRAINT `tblSims_sims_id`
							FOREIGN KEY `tblSims_sims_id`(`tblSims_sims_id`)
							REFERENCES `tblSims`(`sim_id`)
							ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8

/*создание таблицы симкарт*/
CREATE TABLE `tblSims` (
 `sim_id` int(10) NOT NULL AUTO_INCREMENT,
 `sim_number` int(30) unsigned DEFAULT '0',
 `phone_number` int(30) unsigned DEFAULT '0',
 `status` ENUM("free","busy","blocked") CHARACTER SET utf8 NOT NULL,
 `dateofcreate` date DEFAULT '0000-00-00',
 `lic_schet` text CHARACTER SET utf8,
 PRIMARY KEY (`sim_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8



/* Ассоциативная таблица для связи деваса и машины, чтобы при удалении девайса, автоматически удалялась связь с машиной
Обновление девайса обновление машины 
Нет необходимости держать поле девайс в таблице машины, аналогично в таблице девайсов держать поле для машины
Связываем мы запись в таблице simdev, которая говорит о валидных девайсах с симкой, таким образом добавить девайс 
без симки нельзя*/
CREATE TABLE `devauto`(
						`r_id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`tblsimdev_r_id` int(10) unsigned NOT NULL,
						`tblAutos_auto_id` int(10) UNSIGNED NOT NULL,
						CONSTRAINT `tblsimdev_fk1`
							FOREIGN KEY `tblsimdev_r_id`(`tblsimdev_r_id`)
							REFERENCES `simdev`(`r_id`)
							ON DELETE CASCADE ON UPDATE CASCADE,
						CONSTRAINT `tblAutos_fk1`
							FOREIGN KEY `tblAutos_auto_id`(`tblAutos_auto_id`)
							REFERENCES `tblAutos`(`auto_id`)
							ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8



/*создание таблицы датчиков, которая связана tblDevices черех tblDevices_dev_id*/
CREATE TABLE `tblSensors`(
						`sens_id` int(30) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`model_type` text CHARACTER SET utf8,
						`sens_serial` int(30) unsigned NOT NULL DEFAULT '0000000',
						`sens_status` ENUM("using","not used","defected") CHARACTER SET utf8 NOT NULL,
						`tblDevices_dev_id` int(30) unsigned DEFAULT '0',
						CONSTRAINT `tblDevices_fk2`
							FOREIGN KEY `tblDevices_dev_id`(`tblDevices_dev_id`)
							REFERENCES `tblDevices`(`dev_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8




/*tblWorkers - таблица воркеров , что рабоают в фирме свяжем ее с таблицей аутентификации,
для каждого работника будем заводить пользователя аутентификации, если это необходимо,
причем удаление пользователя аутентификации не означает удаление воркера, удаление воркера должно означать удаление 
аутентификации*/
CREATE TABLE `tblWorkers`(
						`worker_id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`fio` varchar(150) CHARACTER SET utf8 NOT NULL,
						`some_info` varchar(150) CHARACTER SET utf8 NOT NULL,
						`passport_ser_num` bigint(20) unsigned NOT NULL,
						`hire_date` date NOT NULL DEFAULT '0000-00-00',
						`post_of_worker` varchar(200) CHARACTER SET utf8 NOT NULL,
						`phone` bigint(11) unsigned DEFAULT '00000000000',
						`email` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT 'not_def',
						`additional_info` text CHARACTER SET utf8
)ENGINE=InnoDB DEFAULT CHARSET=utf8
/*ТЕстовые данные для вставки*/
INSERT INTO `tblworkers`(`worker_id`, `firstname`, `lastname`, `passport_ser_num`, `hire_date`, `post_of_worker`, `phone`, `email`, `additional_info`) 
VALUES ('','tester_adm','tester_adm',6710384560,CURDATE(),'admin',79224567890,'admin@ya.ru','user was created for test');
INSERT INTO `tblworkers`(`worker_id`, `firstname`, `lastname`, `passport_ser_num`, `hire_date`, `post_of_worker`, `phone`, `email`, `additional_info`) 
VALUES ('','tester_empl','tester_empl',6710425652,CURDATE(),'employer',79321472583,'empl@ya.ru','employer was created for test');
INSERT INTO `tblworkers`(`worker_id`, `firstname`, `lastname`, `passport_ser_num`, `hire_date`, `post_of_worker`, `phone`, `email`, `additional_info`) 
VALUES ('','tester_worker','tester_worker',6710123456,CURDATE(),'worker',79229517536,'','worker was created for test');
INSERT INTO `tblworkers`(`worker_id`, `firstname`, `lastname`, `passport_ser_num`, `hire_date`, `post_of_worker`, `phone`, `email`, `additional_info`) 
VALUES ('','tester_worker','tester_empl1',6710124587,CURDATE(),'empl1',79221254785,'empl1@mail.ru','empl1 was created for test');
INSERT INTO `tblworkers`(`worker_id`, `firstname`, `lastname`, `passport_ser_num`, `hire_date`, `post_of_worker`, `phone`, `email`, `additional_info`) 
VALUES ('','tester_worker','tester_empl2',6710985653,CURDATE(),'empl2',79238529632,'','empl2 was created for test');



/*для создания юзверей и прочей рабочей живности работы с АС
это супертип работника*/
CREATE TABLE auth (
					`id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
					`reg_date` date DEFAULT '0000-00-00',
					`log` varchar(25) CHARACTER SET utf8 NOT NULL,
					`lpas` varchar(50) CHARACTER SET utf8 NOT NULL,
					`access_rights` varchar(4) CHARACTER SET utf8 NOT NULL,
					`tblWorkers$worker_id` int(10) UNSIGNED NOT NULL,
					CONSTRAINT `tblWorkers_fk1`
						FOREIGN KEY `tblWorkers$worker_id`(`tblWorkers$worker_id`)
						REFERENCES `tblWorkers`(`worker_id`)
						ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8
/*создали тестовых зверей, возможно права придется расширять*/
INSERT INTO `auth`(`id`, `reg_date`, `log`, `lpas`, `access_rights`, `tblWorkers$worker_id`) 
VALUES ('',CURDATE(),'t_admin',sha1('t_admin'),'SUID',4);
INSERT INTO `auth`(`id`, `reg_date`, `log`, `lpas`, `access_rights`, `tblWorkers$worker_id`) 
VALUES ('',CURDATE(),'t_empl',sha1('t_empl'),'SUI',5);
INSERT INTO `auth`(`id`, `reg_date`, `log`, `lpas`, `access_rights`, `tblWorkers$worker_id`) 
VALUES ('',CURDATE(),'t_empl1',sha1('t_empl1'),'SU',7);
INSERT INTO `auth`(`id`, `reg_date`, `log`, `lpas`, `access_rights`, `tblWorkers$worker_id`) 
VALUES ('',CURDATE(),'t_empl2',sha1('t_empl2'),'S',8);



/*tblServices - таблица учета монтажа, технического обслуживания и прочей фигни*/
CREATE TABLE `tblServices` (
					`serv_id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
					`start_date_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
					`finish_date_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
					`tblAutos$auto_id` int(10) UNSIGNED NOT NULL,
					`tblWorkers$worker_id` int(10) unsigned NOT NULL DEFAULT '0',
					`status` ENUM("done","performed","not done") CHARACTER SET utf8 NOT NULL,
					`description` text CHARACTER SET utf8,
					`serv_type` ENUM("m","ts") CHARACTER SET utf8 NOT NULL,
					CONSTRAINT `tblAutos_fk2`
						FOREIGN KEY `tblAutos$auto_id`(`tblAutos$auto_id`)
						REFERENCES `tblAutos`(`auto_id`)
						ON DELETE CASCADE ON UPDATE CASCADE,
					CONSTRAINT `tblWorkers_fk2`
						FOREIGN KEY `tblWorkers$worker_id`(`tblWorkers$worker_id`)
						REFERENCES `tblWorkers`(`worker_id`)
						ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8

