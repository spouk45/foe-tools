<?php include('../config.php');
header(CHARSET);

session_start();
if(!isset($_SESSION['account']))
{
	echo ERROR_AUTH;	
	exit();
}
//print_r($_POST);

if(!isset($_POST['message']) || (!isset($_POST['toPlayerName']) && !isset($_POST['toAccountId']) && !isset($_POST['toPlayerId'])) )
{
	echo 'erreur de récup des infos';
	exit();
}

try{
	include(CONNECT);
$data['message']=$_POST['message'];
if(isset($_POST['toPlayerId']))
{
	$data['toPlayerId']=$_POST['toPlayerId'];
	//$data['toAccountId']=null;
	$data['fromPlayerId']=$_SESSION['player']['id'];
}
if(isset($_POST['toAccountId']))
{
	$data['toAccountId']=$_POST['toAccountId'];
	//$data['toPlayerId']=null;
	$data['fromAccountId']=$_SESSION['account']['id'];
}
if(isset($_POST['toPlayerName']))
{
	// on doit retranscrire en playerId
	$tabName=explode(';',$_POST['toPlayerName']);
	include(DIR_ROOT.'class/PlayerManager.php');
	$PlayerManager=new PlayerManager($db);
	
	$data2['worldId']=$_SESSION['world']['id'];	
	foreach($tabName as $value)
	{	
		$data2['name']=$value;
		if(($result=$PlayerManager->getPlayerIdByName($data2)) != null)
		{
			$data['toPlayerId'][]=$result;
		}		
	}
	if(!isset($data['toPlayerId']))
	{
		throw new Exception('un des utilisateurs n\'existe pas.');
	}
	//$data['toAccountId']=null;
	$data['fromPlayerId']=$_SESSION['player']['id'];
	// ----------------------------------------------------------------
}
if(isset($_POST['titleMessage']))
{
	$data['title']=$_POST['titleMessage'];
}

include(DIR_ROOT.'class/Message.php');
$Message=new Message($data);
//var_dump($Message);
include(DIR_ROOT.'class/MessageManager.php');
$MessageManager=new MessageManager($db);
$MessageManager->sendMessage($Message);
	
}catch(Exception $e){
	echo $e->getMessage();exit();
	}