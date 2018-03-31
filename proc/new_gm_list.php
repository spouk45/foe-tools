<?php 
include('../config.php');
header(CHARSET);
session_start();
if(!isset($_SESSION['account']) || !isset($_SESSION['player']))
{
	echo ERROR_AUTH;
	exit();
}
if(!isset($_POST['gmId']))
{
	echo ERROR_DATA;
}
$gmData['gmId']=$_POST['gmId'];
$content=$_POST['contentText'];
$gmData['playerId']=$_SESSION['player']['id'];
try{
	// on recherche les Gm du joueur
	include(CONNECT);
	include(DIR_ROOT.'class/Gm.php');
	$Gm=new Gm($gmData);
	include(DIR_ROOT.'class/GmManager.php');
	$GmManager=new GmManager($db);
	// on doit ajouter le nouveau GM
	$gm=$GmManager->addGm($Gm);
	//récupération du nom du Gm
	 $gmName=$GmManager->getGmName($Gm);
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}
echo $content;
?><div class="wrapperGm"><!--
		--><div class="gmName"><p><?php echo $gmName;?></p></div><!--
		--><div class="gmLevel">
			<p class="thGm">Niveau</p>
			<p class="gmtd"><input class="lvl" type="text" data-type="level" data-id="<?php echo $gmData['gmId'];?>" value="0">
		</p>
	</div><!--
		--><div class="gmPf">
		<p class="thGm">Points de forge</p>
		<p class="gmtd">
			<input class="pfAmount" type="text" data-id="<?php echo $gmData['gmId'];?>" data-type="pfAmount" value="0">
			<span> sur </span>
			<input class="pfMax" type="text" data-id="<?php echo $gmData['gmId'];?>" data-type="pfMax" value="0">
		</p>
	</div><!--
	--><div class="detail">
		<p class="thGm">Mise à jour</p>
		<p class="gmtd"></p>
	</div><!--
--></div>

						
					
