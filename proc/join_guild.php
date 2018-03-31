<?php 
include ('../config.php');
header(CHARSET);
session_start();
if(!isset($_SESSION['player']) || !isset($_GET['id']))
{
	echo 'Permission refusé.';
	exit();
}

try{
	include(DIR_ROOT.'class/PlayerManager.php');
	include(CONNECT);
	$PlayerManager=new PlayerManager($db);	
	$PlayerManager->setGuildId($_SESSION['player']['id'],$_GET['id']);
	$PlayerManager->setLevel($_SESSION['player']['id'],'coming');
	
	// envoyer une requete aux admin de guild	
	$PlayerAdmin=$PlayerManager->getPlayerAdminGuild($_GET['id']);// récupérer la liste des player Admin
	$data['message']='Bonjour, '.$_SESSION['account']['name'].' souhaiterai rejoindre la guilde.
	Vous pouvez accepter sa demande dans la section administration de guilde.';
	$data['title']='Nouveau membre';
	$data['toPlayerId']=$PlayerAdmin;
	$data['fromPlayerId']=$_SESSION['player']['id'];
	include(DIR_ROOT.'class/Message.php');	
	$Message=new Message($data);
	include(DIR_ROOT.'class/MessageManager.php');
	$MessageManager=new MessageManager($db);
	$MessageManager->sendMessage($Message);
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	
header('location:'.URL_ROOT.'page/succes_join_guild.php');