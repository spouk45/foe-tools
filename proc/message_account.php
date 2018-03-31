<?php include('../config.php');
header(CHARSET);
session_start();
if (!isset($_SESSION['account']))
{
	echo ERROR_AUTH;
	exit();
}

if (!isset($_POST['message']))
{
	echo ERROR_DATA;
	exit();
}
$data['fromAccountId']=$_SESSION['account']['id'];
$data['message']=$_POST['message'];
$data['title']='Nous contacter';

try{
	include(CONNECT);
	include(DIR_ROOT.'class/PlayerManager.php');
	$PlayerManager=new PlayerManager($db);
	
	$data['toAccountId'][]=$PlayerManager->getIdAdministrator();	
	//print_r($data);
	include(DIR_ROOT.'class/Message.php');
	$Message=new Message($data);	
	
	include(DIR_ROOT.'class/MessageManager.php');
	$MessageManager=new MessageManager($db);
	$MessageManager->sendMessage($Message);
	
	
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}