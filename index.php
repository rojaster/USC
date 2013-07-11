<?php
/********************************************
*Main Control Panel Page*
********************************************/
require_once('/php/modeller/connecter.php'      );
require_once('/php/modeller/uniClassBuilder.php');
require_once('/php/globals.php'                 );


@session_start(); // start session 

if(empty($_SESSION['sess_token'])) header("Location: /php/exit.php"); // if session token is empty, redirect user to auth

$login  = Connecter::secure($_SESSION['name']);
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
				- <a href="/php/controller/creator.php?ctg=<?=__CLIENTS__?>">добавить запись <?=__CLIENTS__?></a>
			</li>
			<li>
				- <a href="/php/controller/creator.php?ctg=<?=__AUTOS__?>">добавить запись <?=__AUTOS__?></a>
			</li>
			<li>
				- <a href="/php/controller/creator.php?ctg=<?=__SIM__?>">добавить запись <?=__SIM__?></a>
			</li>
			<li>
				- <a href="/php/controller/creator.php?ctg=<?=__DEVICES__?>">добавить запись <?=__DEVICES__?></a>
			</li>
			<li>
				- <a href="/php/controller/creator.php?ctg=<?=__SENSORS__?>">добавить запись <?=__SENSORS__?></a>
			</li>
			<li>
				- <a href="/php/controller/creator.php?ctg=<?=__MONTAGE__?>">добавить запись <?=__MONTAGE__?></a>
			</li>
			<li>
				- <a href="/php/controller/creator.php?ctg=<?=__TSERVICE__?>">добавить запись <?=__TSERVICE__?></a>
			</li>
			<li>
				- <a href="/php/controller/creator.php?ctg=<?=__WORKERS__?>">добавить запись <?=__WORKERS__?></a>
			</li>
		</ul>

		<!--Menu Selectors-->
		<hr/>
		<ul class="thumbnails">
			<li>
			<a href="/php/controller/builder.php?cat=<?=__CLIENTS__?>" class="thumbnail">
				<?=__CLIENTS__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=<?=__AUTOS__?>" class="thumbnail">
				<?=__AUTOS__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=<?=__SIM__?>" class="thumbnail">
				<?=__SIM__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=<?=__DEVICES__?>" class="thumbnail">
				<?=__DEVICES__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=<?=__SENSORS__?>" class="thumbnail">
				<?=__SENSORS__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=<?=__MONTAGE__?>" class="thumbnail">
				<?=__MONTAGE__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=<?=__TSERVICE__?>" class="thumbnail">
				<?=__TSERVICE__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=<?=__WORKERS__?>" class="thumbnail">
				<?=__WORKERS__?>
			</a>
			</li>

			<li>
			<a href="/php/controller/builder.php?cat=<?=__STATISTICS__?>" class="thumbnail">
				<?=__STATISTICS__?>
			</a>
			</li>
		</ul>
	</div>
	<!--Control Panel Menu-->

	<div class="interactive-window">
		<!--Таблица по сервису технического обслуживания-->
		<div class="iw-wrap">
			<label class="hr-labels"><?=__TSERVICE__?> (performed,not done)</label>
			<table class="table iw-table table-bordered table-hover break_str">
				<?php
				$obj = CUniClassBuilder::initObj(__TSERVICE__,$dblnk,$rights);
					if(is_null($obj) || !method_exists($obj, "getStatForMP")){
						echo('Object is empty');
					}
					else{
						$obj->getStatForMP('ts');
					}
				?>
			</table>
		</div>

		<!--Таблица по монтажу устройств на автомобили-->
		<div class="iw-wrap">
			<label class="hr-labels"><?=__MONTAGE__?> (performed,not done)</label>
			<table class="table iw-table table-bordered table-hover break_str">
				<?php
					$obj = CUniClassBuilder::initObj(__MONTAGE__,$dblnk,$rights);
					if(is_null($obj) || !method_exists($obj, "getStatForMP")){
						echo('Object is empty');
					}
					else{
						$obj->getStatForMP('m');
					}
				?>
			</table>
		</div>

		<!--Таблица по сводной всех основных объектов-->
		<div class="iw-wrap">
			<label class="hr-labels">Общая статистика</label>
			<table>
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
						<th>
							<?=__CLIENTS__?>
						</th>
					</tr>
					<tbody>
						<tr>
							<!--DEVICES-->
							<td class="tb-comm-stat">
								<?php
									$obj = CUniClassBuilder::initObj(__DEVICES__,$dblnk,$rights);
									if(is_null($obj)){
										echo('Object is empty');
									}
									else{
										$obj->commonStat();
									}
								?>
							</td>
							<!--SENSORS-->
							<td class="tb-comm-stat">
								<?php
									$obj = CUniClassBuilder::initObj(__SENSORS__,$dblnk,$rights);
										if(is_null($obj)){
										echo('Object is empty');
									}
									else{
										$obj->commonStat();
									}
								?>
							</td>
							<!--SIM-->
							<td class="tb-comm-stat">
								<?php
									$obj = CUniClassBuilder::initObj(__SIM__,$dblnk,$rights);
									if(is_null($obj)){
										echo('Object is empty');
									}
									else{
										$obj->commonStat();		// commonStat() is returned an array
									}
								?>
							</td>
							<!--AUTOS-->
							<td class="tb-comm-stat">
								<?php
									$obj = CUniClassBuilder::initObj(__AUTOS__,$dblnk,$rights);
									if(is_null($obj)){
										echo('Object is empty');
									}
									else{
										$obj->commonStat();
									}
								?>
							</td>
							<!--CLIENTS-->
							<td class="tb-comm-stat">
								<?php
									$obj = CUniClassBuilder::initObj(__CLIENTS__,$dblnk,$rights);
									if(is_null($obj)){
										echo('Object is empty');
									}
									else{
										$obj->commonStat();
									}
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
// sorry for my code of monkey somewhere
