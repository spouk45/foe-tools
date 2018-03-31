<?php include('../config.php');
header(CHARSET);
session_start();
if (!isset($_SESSION['player']))
{
	echo ERROR_AUTH;
	exit();
}

if (!isset($_POST))
{
	echo ERROR_DATA;
	exit();
}

if(!isset($_POST['pf']))
{
	echo ERROR_DATA;
	exit();
}

try{
	include(DIR_ROOT.'class/SeekManager.php');
	include(CONNECT);
	$SeekManager=new SeekManager($db);
	$SeekManager->updatePfProd($_POST['pf'],$_SESSION['player']['id']);
	
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}