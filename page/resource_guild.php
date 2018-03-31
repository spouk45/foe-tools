<?php 
include('../config.php');
include(HEAD);
include(HEADER);
?>
<h2>Ressources de la guilde</h2>
<article id="guild_resource">
<?php 
if(!isset($_SESSION['guild']))
{
	echo GUILD_AUTH;
	exit();
}

// récupérer tous les joueurs, puis leurs ressources

try{
	$data=$PlayerManager->getPlayerGuild($_SESSION['guild']['id']);
	include(DIR_ROOT.'class/ResourcesManager.php');
	$ResourcesManager=new ResourcesManager($db);
	$data=$ResourcesManager->getResourceGuild($data);
	$age=$ResourcesManager->getListAge();
	$totalResource=$ResourcesManager->getTotalResource($data);
	$resources_list=$ResourcesManager->getListResource();
	
	/*?><pre><?php print_r($totalResource);?></pre><?php */
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}


?>
<div id="filter">	
	<div id="filter_age">
		<p class="nav_filter button2" id="filterAge" onclick="showFilter()">Filtres</p>
		<ul id="list_filter">
			<?php 
			foreach($age as $value)
			{	
				?><li class="" id="filterAge<?php echo $value['id'];?>" onclick="filter_age(<?php echo $value['id'];?>,this.getAttribute('class'),this.id)"><a><?php echo $value['name'];?></a></li><?php
						
			}?>				
		</ul>
	</div>
	
</div>
<div id="tab" class="relative">
<table>
	<tr>
		<td rowspan="2" class="relative scroll back_color left_col">Membre</td>
		<td rowspan="3" class="relative scroll back_color left_col">Date de mise à jour</td>
		<?php 
		//foreach($data[0]['resource'] as $value)
		foreach($resources_list as $rl)
		{
			?><td colspan="3" class="<?php echo 'age'.$rl['age_id'];?>"><img class="th_resource" src="<?php echo URL_ROOT.'img/resources/'.$rl['image_name'];?>"> </td><?php 
		}
		
	?></tr>
	
	<tr>
		<?php foreach($resources_list as $rl)
		{
			?><td class="<?php echo 'age'.$rl['age_id'];?> amount"><img src="<?php echo URL_ROOT.'img/ressource.png';?>"></td><?php 
			?><td class="<?php echo 'age'.$rl['age_id'];?> production"><img src="<?php echo URL_ROOT.'img/prod.png';?>"></td><?php 
			?><td class="<?php echo 'age'.$rl['age_id'];?> panier"><img src="<?php echo URL_ROOT.'img/panier.png';?>"></td><?php 
		} ?>
	</tr>
	<tr>
		<td class="total back_color relative scroll left_col">Total</td>
		<?php foreach($resources_list as $rl)
		{
			?><td class="<?php echo 'age'.$rl['age_id'];?> amount total_amount"><?php echo $totalResource[$value['resource_id']]['amount'];?></td><?php 
			?><td class="<?php echo 'age'.$rl['age_id'];?> production"><?php echo $totalResource[$value['resource_id']]['production'];?></td><?php 
			?><td class="<?php echo 'age'.$rl['age_id'];?> panier"><?php echo $totalResource[$value['resource_id']]['need'];?></td><?php 
		}?>
	</tr>
	<tr class="space"></tr>
	<?php foreach($data as $value)
	{			
		$dateUpdate=$ResourcesManager->getDateUpdate($value['playerId']);
		?><tr>
			<td class="scroll relative left_col back_color"><?php echo $value['playerName'];?></td>			
			<td class="scroll relative left_col back_color"><?php echo $dateUpdate;?></td>			
			<?php foreach($value['resource'] as $resource)
			{
				?><td class="<?php echo 'age'.$resource['age_id'];?> amount"><?php if($resource['amount']!=0){ echo $resource['amount'];}?></td><?php
				?><td class="<?php echo 'age'.$resource['age_id'];?><?php if($resource['boost']==1){echo ' boost_on';}?> production"><?php if($resource['production']!=0){echo $resource['production'];}?></td><?php
				?><td class="<?php echo 'age'.$resource['age_id'];?> panier"><?php if($resource['need']!=0){ echo $resource['need'];}?></td><?php
			}?>
			
			
		</tr>
	<?php } ?>
</table>
</div>
<div id="legend">
	<p class="bold">Légende:</p>
	<p>
		<span><img src="<?php echo URL_ROOT.'img/ressource.png';?>"> : Vos ressources </span>
		<span><img src="<?php echo URL_ROOT.'img/prod.png';?>"> : Votre production journalière </span>
		<span><img src="<?php echo URL_ROOT.'img/panier.png';?>"> : Vos besoins à court termes </span>
	</p>
</div>
</article>
<?php include(FOOTER);?>
<script>
	function filter_age(id,through,liId)
	{		
		if(through=="")
		{
			$('.age'+id).hide();
			$('#filterAge'+id).addClass('through');
		}
		else {
			$('.age'+id).show();
			$('#filterAge'+id).removeClass('through');
		}
	}
	function showFilter()
	{
		if($('#list_filter').is(':visible'))
		{
			$('#list_filter').css('display','none');
		}
		else {
			$('#list_filter').css('display','inline-block');
		}
		
	}	
	
	$('#tab' ).scroll(function() {
  //$( "#log" ).append( "<div>Handler for .scroll() called.</div>" );
 	left=$('#tab' ).scrollLeft();
	$('.scroll').css('left',left);
	//alert(haut);
	
});

</script>