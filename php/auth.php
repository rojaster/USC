<!DOCTYPE html>
<html>
<head>
	<title>Аутентификация пользователя</title>

	<meta charset="utf-8"/>
	
	<!--Bootstrap include -->
	<link href="../css/bootstrap.css" rel="stylesheet" />
	<link href="../css/bootstrap-responsive.css" rel="stylesheet"/>
	<script src="../js/bootstrap.js"></script>
	<script src="../js/bootstrap-dropdown.js"></script>
	<script src="../js/jquery-min.js"></script>
	<script>
		$(document).ready(function(){
				$('.dropdown-toggle').dropdown();
		});
	</script>
	<link href="../css/mystyle.css" rel="stylesheet" />

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


	</style>


	
</head>
<body>
	
	<header class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="#">ООО "ЮграСпецКонтроль"</a> <!--Вставить ссылку на сайт компании -->
				<div class="nav-collapse collapse">
					<ul class="nav">
						<!-- Poppup меню с кратким профилем компании, горячая линия телефона, факс , мейл и прочее -->
						<li class="active"><a href="#">Контактная информация</a></li>

						<!-- Вставить ссылку на сайт компании, пока выводить "Скоро ожидается" -->
						
						 <li class="dropdown" >
                        <a class="dropdown-toggle" data-toggle="dropdown" id="dLabel" href="#">Сайт компании<b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li><a href="http://www.google.com">http://www.google.com</a></li>
                            
                        </ul>
                    </li>
						
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</header>

	<div class="container">
		<form class="form-signin" method="POST" action="auther.php" >
			<h2 class="form-signin-heading">Авторизация</h2>
			<input type="text" class="input-block-level" placeholder="Логин" name="u_name">
			<input type="password" class="input-block-level" placeholder="Пароль" name="u_pass">
			<label class="checkbox">
				<input type="checkbox" value="remember-me" name="sess_switch"> Запомнить меня на время сессии
			</label>
			<button class="btn btn-large btn-primary" type="submit">Войти</button>
		</form>
	</div>
</body>
</html>