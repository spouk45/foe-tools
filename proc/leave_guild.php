<?php 
include('../config.php');
header(CHARSET);

session_start();
if(!isset($_SESSION['player']))
{
	print_r($_SESSION);
	echo ERROR_AUTH;
	exit();
}
// on doit effacer les info dans la base 
include(CONNECT);
include(DIR_ROOT.'class/PlayerManager.php');
try{
$PlayerManager=new PlayerManager($db);

// verifier si d'autres admin
if(isset($_SESSION['guild']))
{
	$levelId=$PlayerManager->getLevelId($_SESSION['player']['id']);
	if($levelId==1)
	{
		// il ne faut pas pouvoir quitter la guilde en étant seul admin
		$level=$PlayerManager->countLevelAdmin($_SESSION['player']['id']);
		$deleteGuild=false;
		if($level==1)
		{
			// si on est seul admin, on verifie qu'il n'y a pas d'autres membres
			$countPlayer=$PlayerManager->countPlayerGuild($_SESSION['guild']['id']);
			if($countPlayer>1)
			{
				echo 'impossible de quitter la guilde si d\'autres membres sont présents. Sinon, vous devez nommer un autre administrateur.';
				exit();
			}
			else {
				$deleteGuild=true;
			}			
		}
	}
	if ($deleteGuild== true)
	{
		include(DIR_ROOT.'class/GuildManager.php');
		$GuildManager=new GuildManager($db);
		$GuildManager->deleteGuild($_SESSION['guild']['id']);
	}
}

$PlayerManager->leaveGuild($_SESSION['player']['id']);
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	
unset($_SESSION['guild']);
?>
<script>
$(document).ready(function() {
window.location.replace('<?php echo URL_ROOT;?>page/succes_leave_guild.php'); 
});
</script>
