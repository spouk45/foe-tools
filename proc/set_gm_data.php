<?php
include ('../config.php');
header(CHARSET);
session_start();
if(!isset($_SESSION['player']))
{
    echo ERROR_AUTH;
    exit();
}
if(!isset($_POST)){
    echo ERROR_DATA;
    exit();
}

$data['playerId']=$_SESSION['player']['id'];
$data['gmId']=$_POST['id'];
if($_POST['type']=='level')$data['level']=$_POST['value'];
elseif($_POST['type']=='pfAmount')$data['pfAmount']=$_POST['value'];
elseif($_POST['type']=='pfMax')$data['pfMax']=$_POST['value'];
$data['setType']=$_POST['type'];
try{
    include(DIR_ROOT.'class/Gm.php');
    $Gm=new Gm($data);
    include(CONNECT);
    include(DIR_ROOT.'class/GmManager.php');
    $GmManager=new GmManager($db);
    $GmManager->setGmData($Gm);
}
catch(Exception $e){
    echo $e->getMessage();
    exit();
}

