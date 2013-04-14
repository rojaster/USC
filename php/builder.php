<?php 
/********************************************
*Main Control Panel Page*
*query builder : get category , get data
from db about category, create page with category*
********************************************/
require_once("connecter.php");
@session_start();

if(empty($_SESSION['sess_token'])) header("Location: /php/auth.php"); // if session token is empty, redirect user to auth

$login = $db->secure($_SESSION['name']);
$rights = $db->secure($_SESSION['rights']);

$sql = "SELECT * 
		FROM auth
		INNER JOIN tblworkers ON tblworkers.worker_id = tblworkers\$worker_id
		WHERE log = '{$login}' AND access_rights = '{$rights}'
		LIMIT 1"; //build query

$qresult = @mysql_query($sql,$dblnk) or die(print_r($sql)); // check user again, lil trick
if(!$qresult) header("Location: /php/auth.php"); 
if(mysql_num_rows($qresult) != 1) {header("Location: /php/auth.php");} // if query returns more one records  redirect to auth
$result = mysql_fetch_assoc($qresult);
@mysql_free_result($qresult); // free query result

if(md5($result['id'].'_'.$result['reg_date']) != $_SESSION['sess_token']){
		session_unset($_SESSION['sess_token']);
		unset($_SESSION['session_token']);
		@session_destroy();
		header("Location: /php/auth.php");
}
else{
	$category = $db->secure($_GET['cat']);
	switch($category){
		case 'sims' : $cat_header = 'SIM'; break;
		case 'devices' : $cat_header = 'ПРИБОРЫ'; break;
		case 'sensors' : $cat_header = 'ДАТЧИКИ'; break;
		case 'autos' : $cat_header = 'АВТО'; break;
		case 'servicesm' : $cat_header = 'МОНТАЖ'; break;
		case 'servicess' : $cat_header = 'Заявки на тех. обслуживание'; break;
		case 'workers' : $cat_header = 'РАБОТНИКИ'; break;
		case 'statistics' : $cat_header = 'СТАТИСТИКА'; break;
		case 'clients' : $cat_header = 'КЛИЕНТЫ'; break;
		default: header("Location: exit.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>ООО "ЮграСпецКонтроль"</title>

	<!--Bootstrap include -->
	<link href="../css/bootstrap.css" rel="stylesheet" />
	<link href="../css/bootstrap-responsive.css" rel="stylesheet"/>
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.js"></script>
	<link href="../css/mystyle.css" rel="stylesheet" />

</head>
<body>

<!-- HEADER -->
	<header class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="#">ООО "ЮграСпецКонтроль"</a> <!--Вставить ссылку на сайт компании -->
				<div class="nav-collapse collapse">
					<ul class="nav">
						<!-- Poppup меню с кратким профилем компании -->
						<li class="dropdown" >
							<a class="dropdown-toggle" data-toggle="dropdown" href="#menu">Контактная информация
							</a>
							<ul class="dropdown-menu pull-right" role="menu" >
								<li>
									<address>
									<strong>ООО "ЮграСпецКонтроль"</strong><br>
											<a href="#">Сайт компании</a><br>
											San Francisco, CA 94107<br>
											<abbr title="Phone">P:</abbr> (123) 456-7890
									</address>

									<address>
											<strong>Full Name</strong><br>
											<a href="mailto:#">first.last@example.com</a>
									</address>
								</li>
							</ul>
						</li>


						<!-- Poppup меню с кратким профилем компании -->
						<li class="dropdown" >
							<a class="dropdown-toggle" data-toggle="dropdown" href="#menu">
								Профиль сотрудника <?=$login?>
							</a>

							<ul class="dropdown-menu pull-right" role="menu" >
								<li>
									<address>
											ФИО:<br/>
											<strong><?=$result['lastname']?> <br/> <?=$result['firstname']?></strong></br>
											Зарегистрирован : <br/><strong><?= $result['reg_date'] ?></strong><br/>
											Права доступа : <strong><?=$result['access_rights']?></strong><br/>
											Должность : <strong><?=$result['post_of_worker']?></strong><br/>
											<hr class="short-line"/>
											<a href="mailto:<?= $result['email']?>"><?= $result['email']?></a>
											
									</address>
								</li>
							</ul>
						
						</li>

						<li>
							<a href="/php/exit.php">Выход</a>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</header>
<!-- HEADER -->

	<h1><?=$cat_header?></h1>
	<ul class="pager">
		<li>
			<a href="../index.php">&larr; Главная Панель Управления</a>
		</li>
	</ul>

	<table class="table table-bordered table-hover table-condensed">
			<?php require_once("view.php"); ?>
	</table>

<!--FOOTER-->
	<hr/>
	<footer>
		<p><small>Created for ООО"ЮграСпецКонтроль" 2013(с)</small></p>
	</footer>

<!--FOOTER-->
</body>
</html>


<?php
} // end else 

