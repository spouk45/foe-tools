<?php 
include('../config.php');
header(CHARSET);

session_start();
if(!isset($_SESSION['player']) || !isset($_POST['guild_name']) || !isset($_SESSION['world']))
{
	echo ERROR_AUTH;
	exit();
}

$data['playerId']=$_SESSION['player']['id'];
$data['guildName']=$_POST['guild_name'];
$data['worldId']=$_SESSION['world']['id'];

try{
	include(DIR_ROOT.'class/Guild.php');
	$Guild=new Guild($data);
	include(DIR_ROOT.'class/GuildManager.php');
	include(CONNECT);
	$GuildManager=new GuildManager($db);
	$guildId=$GuildManager->addGuild($Guild);		
}
catch(Exception $e){
	echo $e->getMessage();
	exit();
	}
try {
	include(DIR_ROOT.'class/PlayerManager.php');
	$PlayerManager=new PlayerManager($db);
	$PlayerManager->setLevel($data['playerId'],'admin');
	$PlayerManager->setGuildId($data['playerId'],$guildId);	
	$guildName=$PlayerManager->getGuildName($_SESSION['player']['id']);
}
catch(Exception $e){
	echo $e->getMessage();
	try {
		$GuildManager->deleteGuild($guildId);
		}
	catch(Exception $e2){
		echo $e2->getMessage();
		exit();
		}
	exit();
	}
$_SESSION['guild']['id']=$guildId;
$_SESSION['guild']['name']=$guildName;

?><script>
$(document).ready(function() {
window.location.replace('<?php echo URL_ROOT;?>page/succes_create_guild.php'); 
});
</script>

