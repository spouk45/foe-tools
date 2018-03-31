<?php include('../config.php');
include(HEAD);
include(HEADER);

?>
<h2>Selection d'un monde</h2>
<div id="wrapper">
<?php 
if(!isset($_SESSION['account']))
{
	echo 'Erreur d\'authentification';
	exit();
}
include(CONNECT);
include(DIR_ROOT.'class/WorldManager.php');

try{
$WorldManager=new WorldManager($db);
$data=$WorldManager->worldList();
}
catch(Exception $e)
{
	echo $e->getMessage();exit();
}
if($data==null){
	echo '<p>Aucun monde enregistré.</p>';
	exit();
}

?><ul class="world"><?php
foreach ($data as $value){	
	?><li><a class="button" href="<?php echo URL_ROOT.'proc/select_world.php?id='.$value['id'].'&name='.$value['name'];?>"><?php echo $value['name'];?></a></li><?php	
}
?></ul><?php

?>
</div>
<?php include(FOOTER);?>