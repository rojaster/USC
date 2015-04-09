-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Апр 09 2015 г., 14:17
-- Версия сервера: 5.6.11
-- Версия PHP: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `usctestdb`
--
CREATE DATABASE IF NOT EXISTS `usctestdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `usctestdb`;

-- --------------------------------------------------------

--
-- Структура таблицы `auth`
--

CREATE TABLE IF NOT EXISTS `auth` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `reg_date` date DEFAULT '0000-00-00',
  `log` varchar(25) NOT NULL,
  `lpas` varchar(50) NOT NULL,
  `access_rights` varchar(4) NOT NULL,
  `tblWorkers$worker_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tblWorkers_fk1` (`tblWorkers$worker_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- СВЯЗИ ТАБЛИЦЫ `auth`:
--   `tblWorkers$worker_id`
--       `tblworkers` -> `worker_id`
--

--
-- Дамп данных таблицы `auth`
--

INSERT INTO `auth` (`id`, `reg_date`, `log`, `lpas`, `access_rights`, `tblWorkers$worker_id`) VALUES
(6, '2015-04-09', 't_admin', 'b6e3f6649830a1a2062e8da891905d042dc7f232', 'SUID', 11),
(7, '2015-04-09', 't_empl', 'b7150e417383fdf367eadd0b57005084edfbac9a', 'SUI', 12),
(8, '2015-04-09', 't_empl1', 'bb0aea1bf2d986ad4d5b52af4037adbbec11244d', 'SU', 13),
(9, '2015-04-09', 't_empl2', 'da209e2e6ac54a660e1b2001935e46820a69b30e', 'S', 14);

-- --------------------------------------------------------

--
-- Структура таблицы `devauto`
--

CREATE TABLE IF NOT EXISTS `devauto` (
  `r_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tblsimdev_r_id` int(10) unsigned NOT NULL,
  `tblAutos_auto_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`r_id`),
  KEY `tblsimdev_fk1` (`tblsimdev_r_id`),
  KEY `tblAutos_fk1` (`tblAutos_auto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- СВЯЗИ ТАБЛИЦЫ `devauto`:
--   `tblsimdev_r_id`
--       `simdev` -> `r_id`
--   `tblAutos_auto_id`
--       `tblautos` -> `auto_id`
--

-- --------------------------------------------------------

--
-- Структура таблицы `devsens`
--

CREATE TABLE IF NOT EXISTS `devsens` (
  `id` int(30) unsigned NOT NULL AUTO_INCREMENT,
  `dev_id` int(30) unsigned NOT NULL,
  `sens_id` int(30) unsigned NOT NULL,
  `mont_date` date DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `dev_fk1` (`dev_id`),
  KEY `sens_fk2` (`sens_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- СВЯЗИ ТАБЛИЦЫ `devsens`:
--   `dev_id`
--       `tbldevices` -> `dev_id`
--   `sens_id`
--       `tblsensors` -> `sens_id`
--

-- --------------------------------------------------------

--
-- Структура таблицы `simdev`
--

CREATE TABLE IF NOT EXISTS `simdev` (
  `r_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tblDevices_dev_id` int(30) unsigned NOT NULL,
  `tblSims_sims_id` int(10) NOT NULL,
  PRIMARY KEY (`r_id`),
  KEY `tblDevices_fk1` (`tblDevices_dev_id`),
  KEY `tblSims_sims_id` (`tblSims_sims_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- СВЯЗИ ТАБЛИЦЫ `simdev`:
--   `tblDevices_dev_id`
--       `tbldevices` -> `dev_id`
--   `tblSims_sims_id`
--       `tblsims` -> `sim_id`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tblautos`
--

CREATE TABLE IF NOT EXISTS `tblautos` (
  `auto_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gos_num` varchar(10) NOT NULL DEFAULT 'not def',
  `marka` varchar(50) NOT NULL DEFAULT 'not def',
  `status` enum('active','not_active') DEFAULT NULL,
  `conn_date` date DEFAULT '0000-00-00',
  `tblclients_client_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`auto_id`),
  KEY `tblAutos_fk_1` (`tblclients_client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- СВЯЗИ ТАБЛИЦЫ `tblautos`:
--   `tblclients_client_id`
--       `tblclients` -> `client_id`
--

--
-- Дамп данных таблицы `tblautos`
--

INSERT INTO `tblautos` (`auto_id`, `gos_num`, `marka`, `status`, `conn_date`, `tblclients_client_id`) VALUES
(1, 'к456вч86', 'reno', 'active', '2015-04-09', 1),
(2, 'в789уч70', 'mersedes', 'not_active', '2015-04-09', 1),
(3, 'п345гр56', 'вольво', 'active', '2015-04-09', 2),
(4, 'д183ск67', 'камаз', 'active', '2015-04-09', 3),
(5, 'з454шк', 'mitsubishi', 'not_active', '2015-04-09', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `tblclients`
--

CREATE TABLE IF NOT EXISTS `tblclients` (
  `client_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firm_name` varchar(150) NOT NULL DEFAULT 'not defined',
  `contact_person` varchar(150) NOT NULL DEFAULT 'not defined',
  `phone_num` int(20) unsigned NOT NULL DEFAULT '0',
  `fax_num` int(20) unsigned NOT NULL DEFAULT '0',
  `e_mail` varchar(50) NOT NULL DEFAULT 'not defined',
  `cl_type` enum('оао','зао','ип','чл','ооо') DEFAULT NULL,
  `address` text,
  `wialon` varchar(100) NOT NULL DEFAULT 'none',
  `status` enum('active','blocked','black_list') DEFAULT NULL,
  `info` text,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `tblclients`
--

INSERT INTO `tblclients` (`client_id`, `firm_name`, `contact_person`, `phone_num`, `fax_num`, `e_mail`, `cl_type`, `address`, `wialon`, `status`, `info`) VALUES
(1, 'barabara', 'ai se te pegu', 4294967295, 4294967295, 'bara@ya.ru', '', 'ул.Маяковского 32 дом 5', 'none', 'active', NULL),
(2, 'someFirm', 'Виктор Пелевин', 4294967295, 75229633, 'sf@ya.ru', '', 'ул.Нефтюганское шоссе', 'none', 'active', NULL),
(3, 'СНГ', 'Богданов', 4294967295, 4294967295, 'bogdanov@sng.ru', '', 'Бизнес центр', 'none', 'active', NULL),
(4, 'sherbac', 'Щербаков Щербацкий Щербанович', 4294967295, 4294967295, 'sherbakov@ya.ru', '', 'ул.Маяковского 32 дом 5', 'none', 'black_list', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `tbldevices`
--

CREATE TABLE IF NOT EXISTS `tbldevices` (
  `dev_id` int(30) unsigned NOT NULL AUTO_INCREMENT,
  `dev_model` varchar(150) DEFAULT 'not defined',
  `serial_number` bigint(30) unsigned DEFAULT '0',
  `IMEI` bigint(20) unsigned DEFAULT '0',
  `status` enum('free','busy','reserve','defects') DEFAULT NULL,
  `dev_date` date DEFAULT '0000-00-00',
  PRIMARY KEY (`dev_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tblsensors`
--

CREATE TABLE IF NOT EXISTS `tblsensors` (
  `sens_id` int(30) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` text,
  `sens_serial` int(30) unsigned NOT NULL DEFAULT '0',
  `sens_status` enum('using','not_used','defected') NOT NULL,
  `cr_date` date DEFAULT '0000-00-00',
  PRIMARY KEY (`sens_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tblservices`
--

CREATE TABLE IF NOT EXISTS `tblservices` (
  `serv_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `start_date_time` date NOT NULL DEFAULT '0000-00-00',
  `finish_date_time` date NOT NULL DEFAULT '0000-00-00',
  `tblAutos$auto_id` int(10) unsigned NOT NULL,
  `tblWorkers$worker_id` int(10) unsigned NOT NULL DEFAULT '0',
  `status` enum('done','performed','not done') NOT NULL,
  `description` text,
  `serv_type` enum('m','ts') NOT NULL,
  PRIMARY KEY (`serv_id`),
  KEY `tblAutos_fk2` (`tblAutos$auto_id`),
  KEY `tblWorkers_fk2` (`tblWorkers$worker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- СВЯЗИ ТАБЛИЦЫ `tblservices`:
--   `tblAutos$auto_id`
--       `tblautos` -> `auto_id`
--   `tblWorkers$worker_id`
--       `tblworkers` -> `worker_id`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tblsims`
--

CREATE TABLE IF NOT EXISTS `tblsims` (
  `sim_id` int(10) NOT NULL AUTO_INCREMENT,
  `sim_number` int(30) unsigned DEFAULT '0',
  `phone_number` int(30) unsigned DEFAULT '0',
  `status` enum('free','busy','blocked') NOT NULL,
  `dateofcreate` date DEFAULT '0000-00-00',
  `lic_schet` text,
  PRIMARY KEY (`sim_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tblworkers`
--

CREATE TABLE IF NOT EXISTS `tblworkers` (
  `worker_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fio` varchar(150) NOT NULL,
  `passport_ser_num` bigint(20) unsigned NOT NULL,
  `hire_date` date NOT NULL DEFAULT '0000-00-00',
  `fire_date` date NOT NULL DEFAULT '0000-00-00',
  `post_of_worker` varchar(200) NOT NULL,
  `phone` bigint(11) unsigned DEFAULT '0',
  `email` varchar(50) NOT NULL DEFAULT 'not_def',
  `additional_info` text,
  PRIMARY KEY (`worker_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `tblworkers`
--

INSERT INTO `tblworkers` (`worker_id`, `fio`, `passport_ser_num`, `hire_date`, `fire_date`, `post_of_worker`, `phone`, `email`, `additional_info`) VALUES
(11, 'adm', 6710384560, '2015-04-09', '0000-00-00', 'admin', 79224567890, 'admin@ya.ru', 'user was created for test'),
(12, 'empl', 6710425652, '2015-04-09', '0000-00-00', 'employer', 79321472583, 'empl@ya.ru', 'employer was created for test'),
(13, 'tester', 6710123456, '2015-04-09', '0000-00-00', 'worker', 79229517536, '', 'worker was created for test'),
(14, 'empl1', 6710124587, '2015-04-09', '0000-00-00', 'empl1', 79221254785, 'empl1@mail.ru', 'empl1 was created for test'),
(15, 'empl2', 6710985653, '2015-04-09', '0000-00-00', 'empl2', 79238529632, '', 'empl2 was created for test');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `auth`
--
ALTER TABLE `auth`
  ADD CONSTRAINT `tblWorkers_fk1` FOREIGN KEY (`tblWorkers$worker_id`) REFERENCES `tblworkers` (`worker_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `devauto`
--
ALTER TABLE `devauto`
  ADD CONSTRAINT `tblsimdev_fk1` FOREIGN KEY (`tblsimdev_r_id`) REFERENCES `simdev` (`r_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblAutos_fk1` FOREIGN KEY (`tblAutos_auto_id`) REFERENCES `tblautos` (`auto_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `devsens`
--
ALTER TABLE `devsens`
  ADD CONSTRAINT `dev_fk1` FOREIGN KEY (`dev_id`) REFERENCES `tbldevices` (`dev_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sens_fk2` FOREIGN KEY (`sens_id`) REFERENCES `tblsensors` (`sens_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `simdev`
--
ALTER TABLE `simdev`
  ADD CONSTRAINT `tblDevices_fk1` FOREIGN KEY (`tblDevices_dev_id`) REFERENCES `tbldevices` (`dev_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblSims_sims_id` FOREIGN KEY (`tblSims_sims_id`) REFERENCES `tblsims` (`sim_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tblautos`
--
ALTER TABLE `tblautos`
  ADD CONSTRAINT `tblAutos_fk_1` FOREIGN KEY (`tblclients_client_id`) REFERENCES `tblclients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tblservices`
--
ALTER TABLE `tblservices`
  ADD CONSTRAINT `tblAutos_fk2` FOREIGN KEY (`tblAutos$auto_id`) REFERENCES `tblautos` (`auto_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblWorkers_fk2` FOREIGN KEY (`tblWorkers$worker_id`) REFERENCES `tblworkers` (`worker_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
