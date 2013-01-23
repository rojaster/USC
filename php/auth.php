<!DOCTYPE html>
<html>
<head>
	<title>Аутентификация пользователя</title>

	<meta charset="utf-8"/>
	
	<!--Bootstrap include -->
	<link href="../css/bootstrap.css" rel="stylesheet" />
	<link href="../css/bootstrap-responsive.css" rel="stylesheet"/>
	<link href="../css/mystyle.css" rel="stylesheet" />
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
				$('#frm').submit(function(){
					if(!$('input[name=u_pass]').val()) return false;
					if(!$('input[name=u_name]').val()) return false;
					return true;
				});
		});
	</script> 
	

	<style type="text/css">

		body{
			padding-top: 40px;
			padding-bottom: 40px;
			background-color: #f5f5f5;
		}

		.form-signin {
			max-width: 300px;
			padding: 19px 29px 29px;
			margin: 70px auto 20px;
			background-color: #fff;
			border: 1px solid #e5e5e5;
			-webkit-border-radius: 5px;
 			-moz-border-radius: 5px;
			border-radius: 5px;
			-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
			-moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
			box-shadow: 0 1px 2px rgba(0,0,0,.05);
		}

		.form-signin .form-signin-heading,
		.form-signin .checkbox {
			margin-bottom: 10px;
		}
		
		.form-signin input[type="text"],
		.form-signin input[type="password"] {
			font-size: 16px;
			height: auto;
			margin-bottom: 15px;
			padding: 7px 9px;
		}

		.navbar-inverse .brand{
			color:#FFFFFF;
			cursor: default;
		}

		.dropdown-menu > li{
			padding:15px;
			padding-bottom: 0px;
			margin: 0px;
		}

		.dropdown-menu > li > address > a{
			color: #999999;
		}

		.dropdown-menu > li > address > a:hover{
			color: #0088cc;
		}

		.dropdown > a:hover{
			
		}
	</style>


	
</head>
<body>
	
	<header class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="#">ООО "ЮграСпецКонтроль"</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<!-- Вставить ссылку на сайт компании, пока выводить "Скоро ожидается" -->
						
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
						
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</header>

	<div class="container">
		<form class="form-signin" method="POST" action="auth_builder.php" id="frm">
			<h2 class="form-signin-heading">Авторизация</h2>
			<input type="text" class="input-block-level" placeholder="Логин" name="u_name">
			<input type="password" class="input-block-level" placeholder="Пароль" name="u_pass">
			<label class="checkbox">
				<input type="checkbox" value="remember-me" name="sess_switch" checked="true"> Запомнить меня </label>
			<button class="btn btn-large btn-primary" type="submit" >Войти</button>
		</form>
	</div>
</body>
</html>