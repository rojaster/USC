<?php 
/********************************************
*Main Control Panel Page*
*query builder : get category , get data
from db about category, create page with category*
********************************************/
require_once('/connecter.php');
require_once('/../globals.php');
@session_start();

if(empty($_SESSION['sess_token'])) header("Location:".__EXIT__); // if session token is empty, redirect user to auth

$login = Connecter::secure($_SESSION['name']);
$rights = Connecter::secure($_SESSION['rights']);
$category = Connecter::secure($_GET['cat']);
$sql = "SELECT * 
		FROM auth
		INNER JOIN tblworkers ON tblworkers.worker_id = tblworkers\$worker_id
		WHERE log = '{$login}' AND access_rights = '{$rights}'
		LIMIT 1"; //build query

$qresult = @mysql_query($sql,$dblnk) or die(print_r($sql)); // check user again, lil trick
if(!$qresult) header("Location:".__EXIT__); 
if(mysql_num_rows($qresult) != 1) {header("Location:".__EXIT__);} // if query returns more one records  redirect to auth
$result = mysql_fetch_assoc($qresult);
@mysql_free_result($qresult); // free query result

if(md5($result['id'].'_'.$result['reg_date']) != $_SESSION['sess_token']){
		session_unset($_SESSION['sess_token']);
		unset($_SESSION['session_token']);
		@session_destroy();
		header("Location:".__EXIT__);
}
else{
	switch($category){
		case 'sims'			: $cat_header = __SIM__			;	break;
		case 'devices'		: $cat_header = __DEVICES__		;	break;
		case 'sensors'		: $cat_header = __SENSORS__		;	break;
		case 'autos'		: $cat_header = __AUTOS__		;	break;
		case 'servicesm'	: $cat_header = __MONTAGE__		;	break;
		case 'servicess'	: $cat_header = __TSERVICE__	;	break;
		case 'workers'		: $cat_header = __WORKERS__		;	break;
		case 'statistics'	: $cat_header = __STATISTICS__	;	break;
		case 'clients'		: $cat_header = __CLIENTS__		;	break;
		default 			: header("Location:".__EXIT__)	;
	}
?>

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
						<a href="/php/controller/creator.php?ctg=<?=$_GET['cat']?>">Создать</a> 
			<?php }?>
		</li>
	</ul>

	<table class="table table-bordered table-hover table-condensed">
			<?php require_once("/../viewer/view.php"); ?>
	</table>

<!--FOOTER-->
<?= include_once('/../viewer/footer.php');?>
<!--FOOTER-->
</body>
</html>


<?php
} // end else 

