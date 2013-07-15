<?php
/********************************************
*Main Control Panel Page*
*query builder : get category , get data
from db about category, create page with category*
********************************************/
require_once('/../modeller/connecter.php');
require_once('/../globals.php');
@session_start();

if(empty($_SESSION['sess_token'])) header("Location: /../".__EXIT__); // if session token is empty, redirect user to auth

$login = Connecter::secure($_SESSION['name']);
$rights = Connecter::secure($_SESSION['rights']);
$cat_header = Connecter::secure($_GET['cat']);
$sql = "SELECT *
		FROM auth
		INNER JOIN tblworkers ON tblworkers.worker_id = tblworkers\$worker_id
		WHERE log = '{$login}' AND access_rights = '{$rights}'
		LIMIT 1"; //build query

$qresult = @mysql_query($sql,$dblnk) or die(print_r($sql)); // check user again, lil trick
if(!$qresult) header("Location: /../".__EXIT__);
if(mysql_num_rows($qresult) != 1) {header("Location: /../".__EXIT__);} // if query returns more one records  redirect to auth
$result = mysql_fetch_assoc($qresult);
@mysql_free_result($qresult); // free query result

if(md5($result['id'].'_'.$result['reg_date']) != $_SESSION['sess_token']){
		session_unset($_SESSION['sess_token']);
		unset($_SESSION['session_token']);
		@session_destroy();
		header("Location: /../".__EXIT__);
}
else{
?>
<?php require_once("/../viewer/view.php"); ?>
<!DOCTYPE html>
<html>
	<?php include_once('/../viewer/head.php'); ?>
<body>
	<?php include_once('/../viewer/header.php'); ?>

	<h1><?=$cat_header?></h1>
	<ul class="pager">
		<li>
			<a href="<?=__MAIN__?>">&larr; Главная Панель Управления</a>

			<?php if($rights =='SUI' || $rights == 'SUID'){?>
						<a href="/php/controller/creator.php?ctg=<?=$cat_header?>">Создать</a>
			<?php }?>
		</li>
	</ul>

	<!--table for print information for category of object-->
	<div class="table-wrap">
			<!--Common statistic line-->
		<span class="statsline">
			<?php
				$arr = $object->getFullObjStat();
				$stats = '';
				foreach ($arr as $key => $value) {
					$stats .= $key.': '.'<b>'.$value.'</b>'.'&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				print($stats);
			?>
		</span>
		<table class="table table-bordered table-hover table-condensed">
			<?
				$object->viewCatData($db->get_dbname()); // info rendering
			?>
		</table>
	</div>
<!--FOOTER-->
<?= include_once('/../viewer/footer.php');?>
<!--FOOTER-->
</body>
</html>


<?php
} // end else

