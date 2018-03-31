<?php 
include('../config.php');
include(HEAD);
include(HEADER);

?><h2>Administration de guilde</h2>
<article>
<?php 
if (!isset($_SESSION['player']))
{
	echo 'Permission refusé.';
	exit();
}
try{
	$levelId=$PlayerManager->getLevelId($_SESSION['player']['id']);
	if($levelId==null)
	{
		echo 'Permission refusé.';
		exit();
	}
}
catch(Exception $e)
{
	$e->getMessage();exit();
}

?>
<ul>
	<li class="link"><a class="button2" onclick="leave_guild();">Quitter la guilde</a></li>
<?php 

if($levelId==2)
{
	echo '<p>Votre compte est en attente de validation par un admin pour être accepté dans la guilde.</p>';
}
if($levelId==1)
{
	
	try{
		$countPlayerComing=$PlayerManager->countPlayerComing($guildId);		
		}
	catch(Exception $e){
	echo $e->getMessage();exit();
	}
	
	?><li class="link"><a class="button2" href="<?php echo URL_ROOT.'page/management_guild.php';?>">Gestion des membres (<?php echo $countPlayerComing;?> en attente)</a></li>

	
	<?php 
	
}
?><div id="box_error" class="text_error"></div> 
</article>
<?php include(FOOTER);?>
<script>	
	function leave_guild()
	{
		$('#box_error').load('<?php echo URL_ROOT.'proc/leave_guild.php';?>');
	}
</script>



