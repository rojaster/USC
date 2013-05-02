<?php
/********************************************
*Main Control Panel Page*
********************************************/
require_once("/php/connecter.php");
@session_start(); // start session 

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
		@session_destroy();
		session_unset($_SESSION['sess_token']);
		header("Location: /php/auth.php");
}
else{
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>ООО "ЮграСпецКонтроль"</title>

	<!--Bootstrap include -->
	<link href="css/bootstrap.css" rel="stylesheet" />
	<link href="css/bootstrap-responsive.css" rel="stylesheet"/>
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	<link href="css/mystyle.css" rel="stylesheet" />
	<script type="text/javascript">
		var clrCoockie = function(){
			window.document.cookie = 'PHPSESSID=0;';
			return 1;
		}
	</script>

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
											<strong><?=$result['some_info']?> <br/> <?=$result['fio']?></strong></br>
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

	<h1>Главная Панель Управления</h1>

<!-- BODY Container -->
	<div class="hero-unit">


		<ul class="thumbnails">
			<li>
			<a href="/php/builder.php?cat=sims" class="thumbnail">
				SIM
			</a>
			</li>

			<li>
			<a href="/php/builder.php?cat=devices" class="thumbnail">
				ПРИБОРЫ
			</a>
			</li>

			<li>
			<a href="/php/builder.php?cat=sensors" class="thumbnail">
				ДАТЧИКИ
			</a>
			</li>

			<li>
			<a href="/php/builder.php?cat=autos" class="thumbnail">
				АВТО
			</a>
			</li>

			<li>
			<a href="/php/builder.php?cat=servicesm" class="thumbnail">
				МОНТАЖ
			</a>
			</li>

			<li>
			<a href="/php/builder.php?cat=servicess" class="thumbnail">
				Заявки на <br/>тех. обслуживание
			</a>
			</li>

			<li>
			<a href="/php/builder.php?cat=workers" class="thumbnail">
				РАБОТНИКИ
			</a>
			</li>

			<li>
			<a href="/php/builder.php?cat=statistics" class="thumbnail">
				СТАТИСТИКА
			</a>
			</li>

			<li>
			<a href="/php/builder.php?cat=clients" class="thumbnail">
				КЛИЕНТЫ
			</a>
			</li>

		</ul>
	

	</div>

<!--FOOTER-->
	<hr/>
	<footer>
		<p><small>Created for ООО"ЮграСпецКонтроль" 2013(с)</small></p>
	</footer>

<!--FOOTER-->
</body>
</html>
<?php
} // end else (when session token is valid , show up main panel )
?>
