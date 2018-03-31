<?php 
include('../config.php');
include(HEAD);
header(CHARSET);

if(!isset($_POST['user_name']) || !isset($_POST['user_password']))
{
	echo 'Une erreur s\'est produite. Veuillez recommencer.';
	exit();
}

$data['name']=$_POST['user_name'];
$data['pass']=$_POST['user_password'];
$data['connect']='connect';

include(DIR_ROOT.'class/Account.php');

try{
$Account=new Account($data);
include(CONNECT);
$AccountManager=new AccountManager($db);
$userId=$AccountManager->getUserId($Account);
$Account->setUserId($userId);
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}

session_start();
//$_SESSION['Account']=$Account;
$_SESSION['account']['id']=$Account->getUserId();
$_SESSION['account']['name']=$Account->getUserName();


//header('location:../index.php');
?>
<script>
/*
$(document).ready(function() {
location.reload();
});
*/
$(document).ready(function() {
window.location.replace('<?php echo URL_ROOT;?>page/world.php'); 
//$('#connect').load('<?php echo URL_ROOT;?>inc/inc_connect.php');
//$('#menu').load('<?php echo URL_ROOT;?>inc/inc_nav_connect.php');

});


</script>