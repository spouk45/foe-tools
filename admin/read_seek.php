<?php include('../config.php');?>
<?php include(HEAD);?>
<?php include(HEADER);?>
<?php include(DIR_ROOT.'admin/inc/check_admin.php'); ?>
<h2>Gestion des recherches</h2>
<?php
try{
	include(DIR_ROOT.'admin/class/AdminSeek.php');
	include(CONNECT);
	$Seek=new Seek($db);
	$data=$Seek->readSeek();
	include(DIR_ROOT.'class/ResourcesManager.php');
	$Resources=new ResourcesManager($db);
	$resourcesTab=$Resources->getListResourceById();
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}
/*
?><pre><?php print_r($data);?></pre>


<pre><?php print_r($resourcesTab);?></pre>
*/
?>
<article>
<?php 
/*?><pre><?php print_r($data);?></pre><?php*/
// regoupement tableau par age
$i=1;
foreach($data as $key => $value)
{
	if($value['ageId']==$i)
	{
		$data2[$i][]=$data[$key];
	}
	else {
		$i++;
	}
	
}
/*?><pre><?php print_r($data2);?></pre><?php*/
foreach ($data2 as $key => $ageTab)
{
	?><div class="age<?php echo $key;?>"><?php


foreach($ageTab as $value)
{
	
	?><div class="seek age<?php echo $value['ageId'];?>">
	
		<table>
			<tr><td colspan="3" class="name"><?php echo $value['name'];?></td></tr>
			<tr>
				<td class="logo"><img src="<?php echo URL_ROOT.'img/forge.png';?>" alt="pf"></td>
				
				<td class="input"><input type="text" value="PF" name="pfId" id="PF_ID<?php echo $value['id'];?>"> sur <?php echo $value['pf'];?></td>
				<td class="seek_valid" rowspan="3"><img src="IMG_SEEK" alt="valid"></td>
			</tr>
			<tr>
				<?php if($value['nbResource1']!=null){ ?>
				<td class="logo"><img src="<?php echo URL_ROOT.'img/resources/'.$resourcesTab[$value['resource1Id']];?>"></td>
				<td class="input"><input type="text" value="NB_RES1" name="nbResource1_<?php echo $value['id'];?>" id="nbResource1_<?php $value['id'];?>"> sur <?php echo $value['nbResource1'];?></td>
				<?php } 
				else {
					?><td class="logo"></td>
					<td>Aucune</td><?php
				}
				
			?></tr>
			<tr>
					<?php if($value['nbResource2']!=null){ ?>
				<td class="logo"><img src="<?php echo URL_ROOT.'img/resources/'.$resourcesTab[$value['resource2Id']];?>"></td>
				<td class="input"><input type="text" value="NB_RES2" name="nbResource1_<?php echo $value['id'];?>" id="nbResource1_<?php $value['id'];?>"> sur <?php echo $value['nbResource2'];?></td>
				<?php } 
				else {
					?><td class="logo"></td>
					<td>Aucune</td><?php
				}
				
			?></tr>
			</tr>
		</table>
		
	</div>
<?php }
?></div><?php 
}

?>
</article>

<script>
/*
$(document).ready(function() {
	alert($('article').html());
});
*/
</script>