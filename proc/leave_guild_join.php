<?php include('../config.php');
header(CHARSET);
session_start();
if(!isset($_SESSION['player']))
{	
	echo 'Permission refusée.';
	exit();
}

include(DIR_ROOT.'class/PlayerManager.php');


try{
	include(CONNECT);
	$playerId=$_SESSION['player']['id'];
	$PlayerManager= new PlayerManager($db);
	// vérifier que si on est admin il n'y est pas d'autres joueurs dans la guilde
	$levelId=$PlayerManager->getLevelId($playerId);
	if($levelId==1)
	{
		throw new Exception('Vous ne pouvez quitter la guilde en étant administrateur de celle-ci.');
	}
	else{
		$PlayerManager->deletePlayerGuild($_SESSION['player']['id']);	
	}
	
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	
header('location:'.URL_ROOT.'page/succes_leave_guild.php');