<?php 
include ('../config.php');
header(CHARSET);
session_start();
if(!isset($_SESSION['player']))
{
	echo 'Permission refusé.';
	exit();
}

try{
	include(CONNECT);
	include(DIR_ROOT.'class/GmManager.php');
	$GmManager=new GmManager($db);
	$gmList=$GmManager->getGmList($_SESSION['player']['id']);	
}
catch(Exception $e){echo $e->getMessage();exit();}

/*echo 'gmList:';?><pre><?php print_r($gmList);?></pre><?php*/

?>
<!-- 
<?php foreach($gmList as $value)
{
	?> --><p><img alt="" id="<?php echo 'gmId'.$value['gmId'];?>" src="<?php echo URL_ROOT.'img/GM/'.$value['gmImage'];?>"  draggable="true" ondragstart="drag(event)"></p><!--
<?php } ?>
-->

<script>
 </script>