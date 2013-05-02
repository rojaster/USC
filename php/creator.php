<?php
/***********************************************
* For Create a new record in the category
* use viewer.php class 
************************************************/
require_once("viewer.php");
@session_start();
if(empty($_SESSION['sess_token'])) header("Location: exit.php");
$category = $db->secure($_GET['ctg']);
	switch($category){
		case 'sims'       : $cat_header = 'SIM'; 
							$catInsData = new CViewSimcards($db->get_link(),$_SESSION['rights']);
							break;
		case 'devices'    : $cat_header = 'ПРИБОРЫ';
							$catInsData = new CViewDevices($db->get_link(),$_SESSION['rights']);
							break;
		case 'sensors'    : $cat_header = 'ДАТЧИКИ';
							$catInsData = new CViewSensors($db->get_link(),$_SESSION['rights']);
							break;
		case 'autos'      : $cat_header = 'АВТО'; 
							$catInsData = new CViewAutos($db->get_link(),$_SESSION['rights']);
							break;
		case 'servicesm'  : $cat_header = 'МОНТАЖ'; 
							$catInsData = new CViewServicesM($db->get_link(),$_SESSION['rights']);
							break;
		case 'servicess'  : $cat_header = 'Заявки на тех. обслуживание'; 
							$catInsData = new CViewServicesTS($db->get_link(),$_SESSION['rights']);
							break;
		case 'workers'    : $cat_header = 'РАБОТНИКИ';
							$catInsData = new CViewWorkers($db->get_link(),$_SESSION['rights']);
							break;
		case 'clients'    : $cat_header = 'КЛИЕНТЫ';
							$catInsData = new CViewClients($db->get_link(),$_SESSION['rights']);
							break;
		//case 'statistics' : $cat_header = 'СТАТИСТИКА'; break;
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
	<script type="text/javascript">
	$(document).ready(function(){
		$('#frm').submit(function(e){
			e.preventDefault();
			var mthd = $(this).attr('method');
			var act = $(this).attr('action');
			var fdata = $(this).serialize();
			$.ajax({
				type   : mthd,
				url    : act,
				data   : fdata
				success: function(result){
					alert('Data was inserted');
					$('footer').after(result);
				}
			});
		});
	});
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
	<form id="frm">
		<?php
			$catInsData->render();
		?>
	<br/>
	<button type="submit" name="push" formaction="inserter.php?ctg=<?=$category?>" formmethod="POST">Внести</button>
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
