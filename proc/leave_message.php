<?php 

include('../config.php');
header(CHARSET);

session_start();
if(!isset($_SESSION['account']))
{
	echo ERROR_AUTH;
	exit();
}

if(!isset($_POST['messageId']))
{
	echo ERROR_DATA;
	exit();
}
$data['accountId']=$_SESSION['account']['id'];
if(isset($_SESSION['player']))
{
	$data['playerId']=$_SESSION['player']['id'];
}
try{	
	include(DIR_ROOT.'class/MessageManager.php');
	include(CONNECT);
	$MessageManager=new MessageManager($db);
	$MessageManager->leaveMessage($_POST['messageId'],$data);	
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}