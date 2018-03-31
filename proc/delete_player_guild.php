<?php include('../config.php');
header(CHARSET);
session_start();


if(!isset($_POST['deleteId']) || !isset($_SESSION['player']))
{	
	echo 'Permission refusée.';
	exit();
}
$deleteId=$_POST['deleteId'];

include(DIR_ROOT.'class/PlayerManager.php');


try{
	include(CONNECT);
	$PlayerManager= new PlayerManager($db);
	$levelId=$PlayerManager->getLevelId($_SESSION['player']['id']);	
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	
if($_SESSION['player']['id']==$deleteId && $levelId==1)
{
	echo 'Vous êtes administrateur de la guilde et ne pouvez la quitter.';
	exit();
}	
	
if ($levelId != 1)
{
	echo 'Permission refusée.';
	exit();
}

// a présent on peut procéder au delete 
try{
	$proc=$PlayerManager->deletePlayerGuild($deleteId);
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	
	if ($proc==true)
	{
		echo 'Le joueur à été supprimé de la guilde.';
		?>
		<script>

		$('<?php echo '.tr'.$deleteId;?>').remove();
		</script><?php 
	}
	
?>
