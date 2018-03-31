<?php 
include('../config.php');
header(CHARSET);

session_start();
if(!isset($_SESSION['account']) || !isset($_SESSION['guild']['id']))
{
	echo ERROR_AUTH;
	exit();
}

if(!isset($_POST['guildId']))
{
	echo ERROR_DATA;
	exit();
}

try{	
	include(DIR_ROOT.'class/PlayerManager.php');
	include(CONNECT);
	$PlayerManager=new PlayerManager($db);
	$data=$PlayerManager->getPlayerGuild($_POST['guildId']);
	//print_r($data);
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	
	if($data==null){
	exit();
}
$text='';
foreach($data as $value)
{
	
	$text.=$value['playerName'].';';
}
echo $text;
?>