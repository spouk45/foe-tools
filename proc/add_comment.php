<?php 
include('../config.php');
header(CHARSET);

session_start();
if(!isset($_SESSION['account']))
{
	echo ERROR_AUTH;
	exit();
}

if(!isset($_POST['comment']) ||!isset($_POST['id']) )
{
	echo ERROR_DATA;
	exit();
}


//print_r($_POST);
//print_r($_SESSION);


$data['userId']=$_SESSION['account']['id'];
$data['comment']=$_POST['comment'];
$data['newsId']=$_POST['id'];

try{
	include(DIR_ROOT.'class/NewsManager.php');
	include(CONNECT);
	$NewsManager=new NewsManager($db);
	$NewsManager->addComment($data);
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}


?>


