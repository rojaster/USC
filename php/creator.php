<?php
/***********************************************
* For Create a new record in the category
* use viewer.php class 
************************************************/
require_once("viewer.php");
@session_start();
$category = $db->secure($_GET['ctg']);
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
	<sctipt type="text/javascript">

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
			<a href="builder.php?cat=<?=$category?>">&larr;Вернуться в таблицу</a>
		</li>
	</ul>

	<div class="hero-unit">
	<form>
		<?php
			switch($db->secure($_GET['ctg'])){
				default: header("Location: ../index.php");
				case 'sims'       : $catInsData = new CViewSimcards($db->get_link(),$_SESSION['rights']); 
									$catInsData->render();
									break;
				case 'devices'    : $catInsData = 0; break;
				case 'sensors'    : $catInsData = 0; break;
				case 'autos'      : $catInsData = 0; break;
				case 'servicesm'  : $catInsData = 0; break;
				case 'servicess'  : $catInsData = 0; break;
				case 'workers'    : $catInsData = 0; break;
				case 'statistics' : $catInsData = 0; break;
				case 'clients'    : $catInsData = new CViewClients($db->get_link(),$_SESSION['rights']); 
									$catInsData->render();
									break;
			}
		?>

	</form>
	</div>

<!--FOOTER-->
	<hr/>
	<footer>
		<p><small>Created for ООО"ЮграСпецКонтроль" 2013(с)</small></p>
	</footer>

<!--FOOTER-->
</body>
</html>
