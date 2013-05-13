<?php

/*
* This file is a view for header:
* Navbar 
* Worker Profile Info
* Contact Info
*/
require_once('/../globals.php'); // globals parameters and constants

?>
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
								Профиль сотрудника
							</a>

							<ul class="dropdown-menu pull-right" role="menu" >
								<li>
									<address>
											ФИО:<br/>
											<strong><?=$result['fio']?></strong></br>
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