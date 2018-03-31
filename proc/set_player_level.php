<?php 

 include('../config.php');
header(CHARSET);
session_start();
// ------- CONTROLE DES PERMISSION  ------------
if(!isset($_POST['playerId']) || !isset($_POST['levelId']) || !isset($_SESSION['player']))
{	
	echo 'Permission refusée.';
	exit();
}

include(DIR_ROOT.'class/PlayerManager.php');
try{
	include(CONNECT);
	$PlayerManager= new PlayerManager($db);
	$levelId=$PlayerManager->getLevelId($_SESSION['player']['id']);	
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
		
if ($levelId != 1)
{
	echo 'Permission refusée.';
	exit();
}
if($_POST['playerId'] == $_SESSION['player']['id'])
{
	$count=$PlayerManager->countLevelAdmin($_POST['playerId']);
	$playerId=$_SESSION['player']['id'];
	
	if($count<2)
	{
		echo 'Il doit rester au moins un administrateur dans la guilde.';
		?><script>
			$('#select<?php echo $playerId;?>').val(1);
		</script><?php 
		exit();
	}
}

// ------------------------------

try{	
	$levelId=$PlayerManager->getLevelId($_POST['playerId']);
	$proc=$PlayerManager->setLevelId($_POST['playerId'],$_POST['levelId']);	
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	
