
<?php 
include('../config.php');
include(HEAD);
include(HEADER);

?><h2>Rejoindre une guilde</h2>
<article id="joinGuild">
<?php 
if(!isset($_SESSION['player']))
{
	echo ERROR_AUTH;
	exit();
}
$guildId=$PlayerManager->getGuildId($_SESSION['player']['id']);
if($guildId != null)
{
	echo 'Vous devez d\'abord quitter votre guilde avant d\'en rejoindre une autre.';
	exit();	
}

include(DIR_ROOT.'class/GuildManager.php');
try{
	$GuildManager=new GuildManager($db);
	$guildData=$GuildManager->getGuildList($_SESSION['world']['id']);
	}
	catch(Exception $e){
	echo $e->getMessage();exit();
	}	
?>

<?php if(!isset($guildData))
{
	echo '<p>Aucune guilde sur ce monde.';
}
else
{
	?><ul>
	<?php 
	foreach($guildData as $value)
	{
		?>
			<li><a href="<?php echo URL_ROOT.'proc/join_guild.php?id='.$value['guildId'];?>"><?php echo $value['guildName'];?></a></li>
		
	<?php }
	?>
	</ul><?php
}?>

</article>
<?php include(FOOTER);?>