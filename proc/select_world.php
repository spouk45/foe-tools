<?php 
include('../config.php');
header(CHARSET);

if (!isset($_GET['id']) || !isset($_GET['name']))
{
	echo 'Erreur';
	exit();
}

if (!is_numeric($_GET['id']))
{
	echo 'doit être un nombre';
	exit();
}

session_start();
if(isset($_SESSION['player']))
{
	unset($_SESSION['player']);
	unset($_SESSION['guild']);
}

$_SESSION['world']['id']=$_GET['id'];
$_SESSION['world']['name']=$_GET['name'];

// on doit également charger le player rattaché au monde ou le créer s'il n'existe pas. et les stocks à 0.

$data['worldId']=$_GET['id'];
$data['accountId']=$_SESSION['account']['id'];

include(CONNECT);
include(DIR_ROOT.'class/PlayerManager.php');
try{
// chargement player
$PlayerManager=new PlayerManager($db);
$playerId=$PlayerManager->getPlayerId($data);
if($playerId==null)
	{
		$PlayerManager->addPlayer($data);
	}
$playerId=$PlayerManager->getPlayerId($data);
$guildName=$PlayerManager->getGuildName($playerId);
$guildId=$PlayerManager->getGuildId($playerId);

if($guildName!= null)
{
	$_SESSION['guild']['name']=$guildName;
	$_SESSION['guild']['id']=$guildId;
}
$_SESSION['player']['id']=$playerId;
	
// chargement ressources
include(DIR_ROOT.'class/ResourcesManager.php');
$ResourcesManager=new ResourcesManager($db);
if(!$ResourcesManager->checkStock($playerId))
	{		
		$ResourcesManager->addStockData($playerId);
	}
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	

header('location:'.URL_ROOT.'page/news.php');