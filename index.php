<?php
/********************************************
*Main Control Panel Page*
********************************************/
require_once("/php/controller/connecter.php");

@session_start(); // start session 

if(empty($_SESSION['sess_token'])) header("Location: /php/exit.php"); // if session token is empty, redirect user to auth

$login = Connecter::secure($_SESSION['name']);
$rights = Connecter::secure($_SESSION['rights']);

$sql = "SELECT * 
		FROM auth
		INNER JOIN tblworkers ON tblworkers.worker_id = tblworkers\$worker_id
		WHERE log = '{$login}' AND access_rights = '{$rights}'
		LIMIT 1"; //build query

$qresult = @mysql_query($sql,$dblnk) or die(print_r($sql)); // check user again, lil trick
if(!$qresult) header("Location: /php/exit.php"); 
if(mysql_num_rows($qresult) != 1) {header("Location: /php/exit.php");} // if query returns more one records  redirect to auth
$result = mysql_fetch_assoc($qresult);
@mysql_free_result($qresult); // free query result

if(md5($result['id'].'_'.$result['reg_date']) != $_SESSION['sess_token']){
		@session_destroy();
		session_unset($_SESSION['sess_token']);
		header("Location: /php/exit.php");
}
else{
?>
<!DOCTYPE html>
<html>
	<?php include_once('/php/viewer/head.php');?>
<body>

	<?php include_once('/php/viewer/header.php');?>

	<h1>Главная Панель Управления</h1>

<!-- BODY Container -->
	<div class="hero-unit">
		<ul class="thumbnails">
			<li>
			<a href="/php/controller/builder.php?cat=sims" class="thumbnail">
				SIM
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=devices" class="thumbnail">
				ПРИБОРЫ
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=sensors" class="thumbnail">
				ДАТЧИКИ
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=autos" class="thumbnail">
				АВТО
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=servicesm" class="thumbnail">
				МОНТАЖ
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=servicess" class="thumbnail">
				Заявки на <br/>тех. обслуживание
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=workers" class="thumbnail">
				РАБОТНИКИ
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=statistics" class="thumbnail">
				СТАТИСТИКА
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=clients" class="thumbnail">
				КЛИЕНТЫ
			</a>
			</li>
		</ul>
	</div>

<!--FOOTER-->
<?=
	include_once('/php/viewer/footer.php');
?>
</body>
</html>
<?php
} // end else (when session token is valid , show up main panel )
?>
