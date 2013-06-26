<?php
/***********************************************
* For Create a new record in the category
* use viewer.php class 
************************************************/
require_once('/../modeller/uniClassBuilder.php');
require_once('/../modeller/classes.php');
require_once('/../globals.php');
@session_start();
if(empty($_SESSION['sess_token'])) header("Location:".__EXIT__);
$category = Connecter::secure($_GET['ctg']);
$catInsData = CUniClassBuilder::initObj($category,$dblnk,$_SESSION['rights']);
if(is_null($catInsData)) header("Location: /php/".__EXIT__); // go away, kiddi
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>ООО "ЮграСпецКонтроль"</title>

	<!--Bootstrap include -->
	<link href="/../../css/bootstrap.css" rel="stylesheet" />
	<link href="/../../css/bootstrap-responsive.css" rel="stylesheet"/>
	<script src="/../../js/jquery.js"></script>
	<script src="/../../js/bootstrap.js"></script>
	<link href="/../../css/mystyle.css" rel="stylesheet" />
	<script type="text/javascript">
	$(document).ready(function(){
		$('#frm').submit(function(e){
			e.preventDefault();
			var mthd = $(this).attr('method');
			var act = $(this).attr('action');
			var fdata = $(this).serialize();
			<?=$catInsData->jsFormValid();?>
			$.ajax({
				type   : mthd,
				url    : act,
				data   : fdata,
				success: function(result){
							alert(result);
							$('#frm').each(function(){
								this.reset();
							});
				},
				error  : function(e,stat,errThrown){
					alert('Запрос Ajax на внесение данных не удался: ' + stat);
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

	<h1><?=$category?></h1>
	<ul class="pager">
		<li>
			<a href="/../../index.php">&larr; Главная Панель Управления</a>
			<a href="builder.php?cat=<?=$category?>">&larr;Вернуться в таблицу</a>
		</li>
	</ul>

	<div class="hero-unit-ins">
	<form id="frm" action="/inserter.php?ctg=<?=$category?>" method="post">
		<?php
			$catInsData->render();
		?>
	<br/>
	<button type="submit">Внести</button>
	</form>
	</div>

<!--FOOTER-->
	<?= require_once('/../viewer/footer.php'); ?>
<!--FOOTER-->
</body>
</html>
