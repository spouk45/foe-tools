<?php 
include ('../config.php');
header(CHARSET);
session_start();
if (!isset($_SESSION['player']))
{
	echo 'Erreur d\'authentification.';
	exit();
}


try{
	if (isset($_POST['id']))
	{
		include(DIR_ROOT.'class/Resources.php');
		$Resources=new Resources($_POST);
		include(DIR_ROOT.'class/ResourcesManager.php');
		include(CONNECT);
		$ResourcesManager=new ResourcesManager($db);
		$ResourcesManager->updateData($Resources,$_SESSION['player']['id']);
	}
	else
	{
		echo ('Erreur de transmission de données.');
	}
	
}
catch(Exception $e)
{
	echo $e->getMessage();
}