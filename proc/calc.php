<?php include('../config.php');
header(CHARSET);
session_start();
if (!isset($_SESSION['player']))
{
	echo ERROR_AUTH;
	exit();
}

if (!isset($_POST))
{
	echo ERROR_DATA;
	exit();
}

//--------------
$playerId=$_SESSION['player']['id'];
if(!isset($_POST['seekId']))
{
	echo ERROR_DATA;
	exit();
}
$seekId=$_POST['seekId'];

try{
	include(DIR_ROOT.'class/SeekManager.php');
	include(CONNECT);
	$SeekManager=new SeekManager($db);
	//$seekList=$SeekManager->seekNeedResourceToUnlock($seekId,$playerId);
	
	
	$listSeekId=$SeekManager->SelectSeekIdLocked($seekId,$playerId);
	
		if(isset($_SESSION['temp']))
		{
			$_SESSION['temp']=array_merge($listSeekId,$_SESSION['temp']);
			$_SESSION['temp']=array_unique($_SESSION['temp']);
			sort($_SESSION['temp']);
			$listSeekId=$_SESSION['temp'];
		}
		else {
			$_SESSION['temp']=$listSeekId;
		}
		
	/*?><pre><?php print_r($listSeekId);?></pre><?php*/
	
	
	foreach ($listSeekId as $key =>$value)
		{
			?><script>
				if(!$('#boxSeekId<?php echo $value;?>').hasClass('calcSelected'))
				{
					$('#boxSeekId<?php echo $value;?>').addClass('calcSelected');	
					$('#calc<?php echo $value;?>').css('display','none');
				}				
			</script><?php
			$truc=$SeekManager->getSeekResources($value);
			if($truc!=null)
			{
				$seekResource[$value]=$truc;			
			}
						
		}
	if(!isset($seekResource)){$seekResource=null;}
		
	// tableau des ressourcesId et quantité
	if($seekResource!=null)
	{
		foreach($seekResource as $value)
		{
			
			foreach ($value as $key => $value2)
			{
				if(isset($resource[$key]['need']) && $resource[$key]['need']!=null)
				{
					$resource[$key]['need']=$resource[$key]['need']+$value2;
				}
				else
				{
					$resource[$key]['need']=$value2;
				}
			}				
		}
	
		// préparation à l'affichage
		include(DIR_ROOT.'class/ResourcesManager.php');
		$ResourceManager=new ResourcesManager($db);
		
		foreach($resource as $key => $value)
		{
			//echo $key;
			$resource[$key]+=$ResourceManager->getResource($key);
			$resource[$key]+=$ResourceManager->getAmountStockById($playerId,$key);
			if(($total=$resource[$key]['need']-$resource[$key]['stock'])>0)
			{
				$resource[$key]['missed']=$total;
			}				
		}
		
		// -------------- prépa ressource temps calc ----------
		foreach($resource as $key => $value)
		{
			//print_r($value);
			if(isset($value['missed']))
			{		
				$prod=$ResourceManager->getProduction($playerId,$key);
				if($prod!=0)
				{
					$resource[$key]['dayLeft']=(int)($value['missed']/$prod);
					
				}
				else{
					$resource[$key]['dayLeft']=null;
				}
			}
			else{
					$resource[$key]['dayLeft']=0;
				}
		}
		
		$countMaxday=0;
		
		foreach($resource as $key => $value)
		{
			if($value['dayLeft']>$countMaxday)
			{
				$countMaxday=$value['dayLeft'];
			}
		}
		
	}
	
	//----------  prépa pf calc ------------
	$pfNeed=0;
	foreach($listSeekId as $value)
	{
		$pfNeed+=$SeekManager->getPfTotal($value,$playerId);
	}
	$pfProd=$SeekManager->getPfProd($playerId);
	if($pfProd!=0)
	{
		$countDay=(int)($pfNeed/$pfProd);
	}
	else{
		$countDay=null;
	}
	
	
	
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}
?>
<div id="closeNeed" class="cursor"><img src="<?php echo URL_ROOT.'img/croix_rouge.png';?>" width="15" height="15" onclick="closeNeed()"></div>
<div id="TotalPf">
	<p>Vous avez besoin de <?php echo $pfNeed;?> <img src="<?php echo URL_ROOT.'img/forge.png';?>" width="20" height="20"></p>
	<?php if($countDay!=null)
	{
		?><p> soit <span class="bold"><?php echo $countDay;?> jours</span></p><?php
	}
	else 
	{
		?><p>Inscrivez votre production journalière.</p><?php
	} ?>
	<?php if(isset($countMaxday)){?>
	<p>Il vous faut <span class="bold"><?php echo $countMaxday;?> jours</span> pour produire les ressources. Détails ci-dessous.</p><p> Si "?",
	vous devez faire du commerce pour obtenir les ressources.</p>
	<?php } ?>
</div>
<div id="wrap_need">

<?php if(isset($resource)){ ?>

	<div class="need">
	<p class="title">Besoins</p>
	<?php 
		
				ksort($resource);
				foreach($resource as $key =>$value)
				{
					?><p><img src="<?php echo URL_ROOT.'img/resources/'.$value['image_name'];?>" width="18" height="18" alt=""> <?php echo $value['need'];?></p><?php			
				}
	?></div>
	
			<div class="missed">
			<p class="title">Manquant</p><?php 
				foreach($resource as $key =>$value)
				{
					
					if(isset($value['missed'])){
						?><p><img src="<?php echo URL_ROOT.'img/resources/'.$value['image_name'];?>" width="18" height="18" alt=""> <?php echo $value['missed'];?></p><?php
						}
						else 
						{
							?><p><img src="<?php echo URL_ROOT.'img/resources/'.$value['image_name'];?>" width="18" height="18" alt=""> 0 </p><?php
						}
				}
			?></div>
			<div class="leftDay">
				<p class="title">Jours</p>
			<?php foreach($resource as $key =>$value)
				{
					if($value['dayLeft']!==null){
						echo '<p>'.$value['dayLeft'].'</p>';
					}
					else {
						echo '<p>?</p>';
					}
				} ?>
			</div>	
				<div id="updateNeed">
					<p class="button button2" onClick="updateNeed()" >Mettre à jour les demandes</p>
					<p class="info">Attention, vous allez mettre à jour l'intégralité de la partie "besoin" de la section "ressources". <br>
						Si vous avez d'autres besoins, vous aller devoir les rajouter par la suite.</p>
				</div>
			<?php		
	}
		else {
			?><p>Vous n'avez pas besoin de ressources</p><?php
		}
	
	?>
	<?php //echo str_replace('"','\'',serialize($resource));?>
</div>

<script>
function updateNeed()
{
	tab=<?php  echo json_encode($resource);?>;
	$.post("<?php echo URL_ROOT.'proc/update_need.php';?>",{
				tab: tab	
				},function(html) {$('#error_box').html(html)}				
				);
	
}

</script>


