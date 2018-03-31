<?php 

include('../config.php');
header(CHARSET);

session_start();
if(!isset($_SESSION['account']))
{
	echo ERROR_AUTH;
	exit();
}

if(!isset($_POST['data']) || !isset($_POST['link']))
{
	echo ERROR_DATA;
	exit();
}

if(isset($_SESSION['player']['id']))
{
	$data['fromPlayerId']=$_SESSION['player']['id'];
}
$data['fromAccountId']=$_SESSION['account']['id'];
$data['message']=$_POST['data'];
$data['link']=$_POST['link'];

// -------------- j'en suis là
try{
	include(DIR_ROOT.'class/Message.php');
	$Message= new Message($data);
	include(DIR_ROOT.'class/MessageManager.php');
	include(CONNECT);
	$MessageManager=new MessageManager($db);
	$MessageManager->addRespond($Message);
	
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	
// on affiche la réponse
echo nl2br(htmlentities($data['message']));
