<?php 
include('../config.php');
header(CHARSET);

if(!isset($_POST['registration_user_name']) || !isset($_POST['registration_user_pass1']) || !isset($_POST['registration_user_pass2'] ))
{
	echo 'Une erreur s\'est produite. Veuillez recommencer.';
	exit();
}

$data['name']=$_POST['registration_user_name'];
$data['pass']=$_POST['registration_user_pass1'];
$data['pass2']=$_POST['registration_user_pass2'];

include(DIR_ROOT.'class/Account.php');
try{
$Account=new Account($data);
$checkPass=$Account->checkPass($data['pass2']);
include(CONNECT);
	$AccountManager=new AccountManager($db);
	if($AccountManager->checkLogin($Account) != 0)
	{
		echo 'Ce login existe déjà.';
		exit();
	}
	
$userId=$AccountManager->addUser($Account);
$Account->setUserId($userId);
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}

session_start();
$_SESSION['account']['id']=$Account->getUserId();
$_SESSION['account']['name']=$Account->getUserName();

//header('location:'.URL_ROOT.'index.php');
?>
<script>

$(document).ready(function() {
window.location.replace('<?php echo URL_ROOT;?>page/succes_registration.php'); 
});

</script>

