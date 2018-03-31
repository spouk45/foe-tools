<?php 

include(DIR_ROOT.'class/PlayerManager.php');
include(CONNECT);

try{
	$PlayerManager=new PlayerManager($db);
	$guildId=$PlayerManager->getGuildId($_SESSION['player']['id']);
}
catch(Exception $e)
{
	$e->getMessage();exit();
}

if($guildId==null)
	{
		?>
		<li><a href="<?php echo URL_ROOT.'page/create_guild.php';?>">Créer une Guilde</a></li>
		<li><a href="<?php echo URL_ROOT.'page/join_guild.php';?>">Rejoindre une Guilde</a></li>
		<?php 
	} 
else 
{
	?>
		<li><a href="<?php echo URL_ROOT.'page/member_guild.php';?>">Membres</a></li>
		<li><a href="<?php echo URL_ROOT.'page/admin_guild.php';?>">Administration</a></li>
		<li><a href="<?php echo URL_ROOT.'page/resource_guild.php';?>">Ressources</a></li>
		<?php 
}
	
	?>
						