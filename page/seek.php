
<?php include('../config.php');?>
<?php include(HEAD);?>
<?php include(HEADER);?>


<?php if(isset($_SESSION['temp']))
{
	unset($_SESSION['temp']);
}

if(!isset($_SESSION['account']) || !isset($_SESSION['player']) )
{
	echo 'Erreur d\'authentification';
	exit();
}
?>
<h2>Gestion des recherches</h2>
<?php
try{
	include(DIR_ROOT.'class/SeekManager.php');
	include(CONNECT);
	// chargement de la liste des recherches
	$SeekManager=new SeekManager($db);
	$data=$SeekManager->seekList();
	$dataSeek=$SeekManager->getSeekLinkUnlocked($_SESSION['player']['id']);
	include(DIR_ROOT.'class/ResourcesManager.php');	
	$Resources=new ResourcesManager($db);
	$resourcesTab=$Resources->getListResourceById();
				
	$age=$Resources->getListAge();
	foreach($age as $value)
	{
		$ageTabId[$value['id']]=$value['name'];
	}
	
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
				$data2[$i][]=$data[$key];
			}			
		}
	
	// chargement des infos player seek (pf)
	$playerSeek=$SeekManager->getPlayerSeek($_SESSION['player']['id']);
	// chargement des stock player
	$stock=$Resources->getAmountStock($_SESSION['player']['id']);
	// compil du stock id
	/*?><pre><?php print_r($stock);?></pre><?php*/

	$pfProd=$SeekManager->getPfProd($_SESSION['player']['id']);
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}

?><article id="seekWrapper" class="back">

<div id="ancre">
	<p><a href="#Age du Bronze">Age du Bronze</a></p>
	<p><a href="#Age du Fer">Age du Fer</a></p>
	<p><a href="#Haut Moyen Age">Haut Moyen Age</a></p>
	<p><a href="#Moyen Age Classique">Moyen Age Classique</a></p>
	<p><a href="#Renaissance">Renaissance</a></p>
	<p><a href="#Age Industriel">Age Industriel</a></p>
	<p><a href="#Ere Progressiste">Ere Progressiste</a></p>
	<p><a href="#Ere Moderne">Ere Moderne</a></p>
	<p><a href="#Ere Postmoderne">Ere Postmoderne</a></p>
	<p><a href="#Ere Contemporaine">Ere Contemporaine</a></p>
	<p><a href="#Demain">Demain</a></p>
	<p><a href="#Ere du Futur">Ere du Futur</a></p>
</div>
<div id="pf_wrap">
	<div>
		<p>Production de Point de forge journalière</p>
		<p><img src="<?php echo URL_ROOT.'img/forge.png';?>" alt="PF" width="20" height="20"> 
		<input type="text" pattern="[0-9]{1,11}" name="pfProd" value="<?php echo $pfProd;?>" id="pfProd" onKeyUp="updatePfProd(this.value)"></p>
	</div>
</div>
<div id="contain_calc">
</div>
<div id="up" onclick="startup();" style="display: block;">
<img alt="" src="<?php echo URL_ROOT.'img/top.png';?>">
</div>
<div id="error_box"></div>

<?php  /*?><pre><?php print_r($data);?></pre><?php */

/*?><pre><?php print_r($playerSeek);?></pre><?php*/
foreach ($data2 as $key => $ageTab)
{
		
	?><div class="groupAge age<?php echo $key;?>">
		<p class="titleAge"><a id="<?php echo $ageTabId[$key];?>"><?php echo $ageTabId[$key];?></a></p><?php
		
	$col=0;
	$li=0;
//json_encode($dataSeek[$value['id']]['link']
foreach($ageTab as $value)
{	
	if($col!=$value['col'])
	{
		?><div></div><?php		
		$col=$value['col'];
	}	
	?>
	<div id="boxSeekId<?php echo $value['id'];?>" class="seek age<?php echo $value['ageId'];?>"
	onmouseover="seekOver(<?php echo $value['id'];?>,'<?php echo str_replace('"','',json_encode($dataSeek[$value['id']]['link']));?>')"
	onmouseout="leftOver(<?php echo $value['id'];?>,'<?php echo str_replace('"','',json_encode($dataSeek[$value['id']]['link']));?>')"
	>
	
		<table>
			<tr>
				<td colspan="3" class="name"><?php echo $value['name'];?>
					<div id="calc<?php echo $value['id'];?>"
					<?php if(!isset($playerSeek[$value['id']]['unlocked']) || $playerSeek[$value['id']]['unlocked']!=1)
										{
					 ?> style="display:block" <?php } else { ?> style="display:none" <?php }
					 ?> class="unshowcalc calc cursor"><img onclick="calc(<?php echo $value['id'];?>)" src="<?php echo URL_ROOT.'img/calc.png';?>" alt="calc" width="20" height="20"></div>
										
				</td>
			</tr>
			<tr>
				<td class="logo"><img src="<?php echo URL_ROOT.'img/forge.png';?>" alt="pf"></td>
				
				<td class="input" id="pfCell<?php echo $value['id'];?>">
					<?php if(isset($playerSeek[$value['id']]['unlocked']) && $playerSeek[$value['id']]['unlocked']==1)
										{ echo $value['pf'];?> sur <?php echo $value['pf'];}
										else{?><input type="text" pattern="[0-9]{1,11}" value="<?php if(isset($playerSeek[$value['id']]['pf']))
										{echo $playerSeek[$value['id']]['pf'];}?>" onKeyUp="updatePf(this.value,<?php echo $value['id'];?>,<?php echo $value['pf'];?>)" name="pf_id<?php echo $value['id'];?>" id="pf_id<?php echo $value['id'];?>"> sur <span id="pfMax<?php echo $value['id'];?>"><?php echo $value['pf'];?></span><?php } ?></td>
				<td class="seek_valid" rowspan="3">
					<img class="cursor" id="imgId<?php echo $value['id'];?>" onclick="updateSeek(<?php echo $value['id'];?>)" 
						src="<?php if(isset($playerSeek[$value['id']]['unlocked']))
										{
											if($playerSeek[$value['id']]['unlocked']==1)
												{echo URL_ROOT.'img/seek_valid.png';}
											else {echo URL_ROOT.'img/seek_novalid.png';}
										}
										else{echo URL_ROOT.'img/seek_novalid.png';}
										?> " width="25" height="25" alt="valid"></td>
			</tr>
			<tr>
				<?php 
				if(isset($playerSeek[$value['id']]['unlocked']) && $playerSeek[$value['id']]['unlocked']==1)
										{  }
									else{
					if($value['nbResource1']!=null){ ?>
				<td class="logo"><img src="<?php echo URL_ROOT.'img/resources/'.$resourcesTab[$value['resource1Id']];?>"></td>
				<td class="input"><span name="nbResource1_<?php echo $value['id'];?>" id="nbResource1_<?php $value['id'];?>"><?php echo $stock[$value['resource1Id']];?></span> sur <?php echo $value['nbResource1'];?></td>
									<?php } 
				else {
					?><td class="logo"><img src="<?php echo URL_ROOT.'img/ressource.png';?>"></td>
					<td>Aucune</td><?php
				}}
				
			?></tr>
			<tr>
					<?php 
					if(isset($playerSeek[$value['id']]['unlocked']) && $playerSeek[$value['id']]['unlocked']==1)
										{}
									else{
					if($value['nbResource2']!=null){ ?>
				<td class="logo"><img src="<?php echo URL_ROOT.'img/resources/'.$resourcesTab[$value['resource2Id']];?>"></td>
				<td class="input"><span name="nbResource2_<?php echo $value['id'];?>" id="nbResource2_<?php $value['id'];?>"><?php echo $stock[$value['resource2Id']];?></span> sur <?php echo $value['nbResource2'];?></td>
				<?php } 
				else {
					?><td class="logo"><img src="<?php echo URL_ROOT.'img/ressource.png';?>"></td>
					<td>Aucune</td><?php
									}}
				
			?></tr>
			</tr>
		</table>
		
	</div>
	<?php // placer un compteur pour les colonnes
	?>
<?php }
?></div><?php 
}

?>
</article>
<?php include(FOOTER);?>
<script>
/*
$(document).ready(function() {	
	dataSeek=<?php echo json_encode($dataSeek); ?>;
	
});
*/


function updatePf(pf,seekId,pfmax)
{
	if(/^[0-9]{1,4}$/.test(pf))
		{				
			if(pf<=pfmax)
			{
				$.post("<?php echo URL_ROOT.'proc/update_seek.php';?>",{
				pf: pf,			
				seekId: seekId		
				},function(html) {$('#error_box').html(html)}				
				);
			}
			else{
				//alert(pfmax);
				$('#pf_id'+seekId).val(pfmax);
				$.post("<?php echo URL_ROOT.'proc/update_seek.php';?>",{
				pf: pfmax,			
				seekId: seekId		
				},function(html) {$('#error_box').html(html)}				
				);
			}
			
		}
}

function updateSeek(seekId)
{
	$.post("<?php echo URL_ROOT.'proc/update_seek.php';?>",{						
				seekId: seekId		
				},function(html) {$('#error_box').html(html)}				
				);
	closeNeed();
}

function calc(seekId)
{
	if(!$('#boxSeekId'+seekId).hasClass('calcSelected'))
	{
		//$('#boxSeekId'+seekId).addClass('calcSelected');
		$.post("<?php echo URL_ROOT.'proc/calc.php';?>",{
				seekId: seekId		
				},function(html) {$('#contain_calc').html(html)}				
				);
		$('#contain_calc').css('display','block');
	}
	else 
	{
		$('#boxSeekId'+seekId).removeClass('calcSelected');		
	}
	
}
function boucle(id){
			$('#boxSeekId'+id).addClass('hoverBorderYellow');
		}
function boucleOut(id){
			$('#boxSeekId'+id).removeClass('hoverBorderYellow');
		}
				
function seekOver(id1,tabId)
	{
		$('#boxSeekId'+id1).addClass('hoverBorder');
		tab=JSON.parse(tabId);
		//alert(truc[0]);
		if(tab!=false){
			tab.forEach(boucle);	
		}
	}
function leftOver(id1,tabId)
{
	$('#boxSeekId'+id1).removeClass('hoverBorder');
	tab=JSON.parse(tabId);
	if(tab!=false){
		tab.forEach(boucleOut);	
	}
}

function closeNeed(){
	$('#contain_calc').css('display','none');
	// --- modifier les id display none en class 
	$('.calcSelected .unshowcalc').css('display','block')
	$('.calcSelected').removeClass('calcSelected');
	$.post("<?php echo URL_ROOT.'proc/temp_destroy.php';?>",{
				},function(html) {$('#contain_calc').html(html)}				
				);
}

function startup(){
  $('html,body').animate({scrollTop: 0}, 'slow');
}

function updatePfProd(pf)
{
	if(/^[0-9]{1,4}$/.test(pf))
	{				
		$.post("<?php echo URL_ROOT.'proc/proc_update_pf_prod.php';?>",{
				pf: pf	
				},function(html) {$('#error_box').html(html)}				
				);
	}
}
</script>