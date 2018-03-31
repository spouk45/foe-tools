<?php include ('../config.php');
header(CHARSET);
session_start();

if(!isset($_SESSION['account']))
{
	echo 'Permission refusé.';
	exit();
}

$data['accountId']=$_SESSION['account']['id'];
if(isset($_SESSION['player']['id'])){
	$data['playerId']=$_SESSION['player']['id'];
}
try{
	include(DIR_ROOT.'/class/Message.php');
	//$Message=new Message($data);
	
	include(CONNECT);
	include(DIR_ROOT.'/class/MessageManager.php');
	$MessageManager=new MessageManager($db);
	$countMessage=$MessageManager->countMessage($data);
	echo $countMessage;
	
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	