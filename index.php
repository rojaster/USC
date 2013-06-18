<?php
/********************************************
*Main Control Panel Page*
********************************************/
require_once('/php/controller/connecter.php');
require_once('/php/modeller/uniClassBuilder.php');
require_once('/php/globals.php');


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
	<!--Control Panel Menu-->
	<div class="hero-unit">
		<!--Menu for adds-->
		<label class="hr-labels">Управляющая панель:</label>
		<ul class="menu-adds">
			<li>
				- <a href="#">добавить запись <?=__CLIENTS__?></a>
			</li>
			<li>
				- <a href="#">добавить запись <?=__AUTOS__?></a>
			</li>
			<li>
				- <a href="#">добавить запись <?=__SIM__?></a>
			</li>
			<li>
				- <a href="#">добавить запись <?=__DEVICES__?></a>
			</li>
			<li>
				- <a href="#">добавить запись <?=__SENSORS__?></a>
			</li>
			<li>
				- <a href="#">добавить запись <?=__MONTAGE__?></a>
			</li>
			<li>
				- <a href="#">добавить запись <?=__TSERVICE__?></a>
			</li>
			<li>
				- <a href="#">добавить запись <?=__WORKERS__?></a>
			</li>
		</ul>

		<!--Menu Selectors-->
		<hr/>
		<ul class="thumbnails">
			<li>
			<a href="/php/controller/builder.php?cat=clients" class="thumbnail">
				<?=__CLIENTS__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=autos" class="thumbnail">
				<?=__AUTOS__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=sims" class="thumbnail">
				<?=__SIM__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=devices" class="thumbnail">
				<?=__DEVICES__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=sensors" class="thumbnail">
				<?=__SENSORS__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=servicesm" class="thumbnail">
				<?=__MONTAGE__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=servicess" class="thumbnail">
				<?=__TSERVICE__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=workers" class="thumbnail">
				<?=__WORKERS__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=statistics" class="thumbnail">
				<?=__STATISTICS__?>
			</a>
			</li>
		</ul>
	</div>
	<!--Control Panel Menu-->

	<div class="interactive-window">
		<div class="iw-wrap">
			<label class="hr-labels"><?=__TSERVICE__?></label>
			<table class="table iw-table table-bordered table-hover">

			</table>
		</div>
		
		<div class="iw-wrap">
			<label class="hr-labels"><?=__MONTAGE__?></label>
			<table class="table iw-table table-bordered table-hover">

			</table>
		</div>

		<div class="iw-wrap">
			<label class="hr-labels">Общая статистика</label>
			<table class="table iw-table table-bordered table-hover">
				<thead>
					<tr>
						<th>
							<?=__DEVICES__?>
						</th>
						<th>
							<?=__SENSORS__?>
						</th>
						<th>
							<?=__SIM__?>
						</th>
						<th>
							<?=__AUTOS__?>
						</th>
					</tr>
					<tbody>
						<tr>
							<td>
								<?php
									$obj = new CUniClassBuilder(__SIM__,$dblnk,$rights);
									$obj->getRefToObj()->fullStat();
								?>
							</td>
							
						</tr>
					</tbody>
				</thead>
			</table>
		</div>

	<br/>
	</div>
<!-- BODY Container -->


<!--FOOTER-->
<?=
	include_once('/php/viewer/footer.php');
?>
</body>
</html>
<?php
} // end else (when session token is valid , show up main panel )
?>
