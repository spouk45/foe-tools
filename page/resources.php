<?php 
include ('../config.php');
include(HEAD);
include(HEADER);

?>

<h2>Ressources</h2>

<?php 
if(!isset($_SESSION['player']))
{
	echo 'Erreur d\'authentification';
	exit();
}
include(DIR_ROOT.'class/ResourcesManager.php');
include(CONNECT);

$playerId=$_SESSION['player']['id'];

try{
$ResourcesManager=new ResourcesManager($db);
$data=$ResourcesManager->getStockData($playerId);
}
catch(Exception $e)
{
	echo $e->getMessage();
}
foreach($data as $key => $value){
	foreach($value as $key2 => $value2)
	{	
		$age=$value['age_id'];
		$data2[$age][$value['resource_id']][$key2]=$value2;
		//echo '<br>clé:'.$key2.' val:'.$value2.'<br>';
	}
}

?>
<article id="resource">

<?php 
/*?><pre><?php print_r($data2);?></pre><?php */
$i=0;
?><div><?php

foreach($data2 as $ageId => $data3)
{ 	
	
		?><div class="age_group age<?php echo $ageId;?>">
			<div class="tr_th"> 
				<p class="col_image"></p>
				<p class="col_name">Ressources</p>
				<p class="col_amount">Stock</p>
				<p class="col_production">Production</p>
				<p class="col_need">Besoin</p>
				<p class="col_boost">Boost</p>
			</div>
			<div>
		<?php foreach($data3 as $resourceId => $value)
		{	?>
			<p class="col_image"><img src="<?php echo URL_ROOT.'img/resources/'.$value['image_name'];?>"></p>
			<p class="col_name"><?php echo $value['name'];?></p>
			<p class="col_amount"><input type="text" pattern="[0-9]{1,11}" name="amount" value="<?php echo $value['amount'];?>" onChange="updateAmount(this.value,<?php echo $value['id'];?>)"></p>
			<p class="col_production"><input type="text" pattern="[0-9\-]{1,11}" name="production" value="<?php echo $value['production'];?>" onChange="updateProduction(this.value,<?php echo $value['id'];?>)"></p>
			<p class="col_need"><input type="text" pattern="[0-9\-]{1,11}" name="need" value="<?php echo $value['need'];?>" onChange="updateNeed(this.value,<?php echo $value['id'];?>)"></p>
			<p class="col_boost"><input type="checkbox" <?php if($value['boost']==1){echo 'checked';}?> name="boost" onClick="updateBoost(this.checked,<?php echo $value['id'];?>)"></p>
			<div id="loader<?php echo $value['id'];?>"></div>
		<?php } ?>
		</div>
		<?php 
		
	?></div><?php
 } 

?>
<div id="error_box"></div>
</article>

<?php include(FOOTER);?>
<script>
	function updateAmount(amount,id)
	{if(/^[0-9]{1,11}$/.test(amount))
		{
			$.post("<?php echo URL_ROOT.'proc/update_resource.php';?>",{
				id: id,			
				amount: amount		
				},function(html) {$('#error_box').html(html)}
				);
		}		
	}
	function updateProduction(production,id)
	{
		if(/^[0-9\-]{1,11}$/.test(production))
			{
				$.post("<?php echo URL_ROOT.'proc/update_resource.php';?>",{
					id: id,			
					production: production		
				},function(html) {$('#error_box').html(html)}
				);
			}
	}
	function updateNeed(need,id)
	{
		if(/^[0-9\-]{1,11}$/.test(need))
			{
				$.post("<?php echo URL_ROOT.'proc/update_resource.php';?>",{
					id: id,			
					need: need		
				},function(html) {$('#error_box').html(html)}
				);
			}
	}
	function updateBoost(boost,id)
	{				
		$.post("<?php echo URL_ROOT.'proc/update_resource.php';?>",{
			id: id,			
			boost: boost		
		},function(html) {$('#error_box').html(html)}
		);
	}
	
</script>