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

$data['playerId']=$_SESSION['player']['id'];
if(!isset($_POST['seekId']))
{
	echo ERROR_DATA;
	exit();
}

$data['seekId']=$_POST['seekId'];


try{
	include(DIR_ROOT.'class/SeekManager.php');
	include(CONNECT);
	$SeekManager=new SeekManager($db);
	if(isset($_POST['pf']))
	{
		$data['pf']=$_POST['pf'];
		$SeekManager->updatePf($data);
	}
	else{
		
		$dataSeek=$SeekManager->getSeekLinkUnlocked($data['playerId']);
		$result=$dataSeek[$data['seekId']]['unlocked'];		
		if($result==0)
		{	
			
			$temp=$SeekManager->SelectSeekIdLocked($data['seekId'],$data['playerId']);
					// on fait les mises à jour en chaine
					foreach($temp as $value)
					{
						$tab['seekId']=$value;
						$playerId=$_SESSION['player']['id'];	
						$tab['playerId']=$playerId;
						//echo 'change id->'.$value.'<br>'; 
						$SeekManager->updateSeekPlayer($playerId,$value,1);
						$resultPf=$SeekManager->updatePf($tab);
						?><script>
							$('#imgId<?php echo $tab['seekId'];?>').attr('src','<?php echo URL_ROOT.'img/seek_valid.png';?>');
							$('#pf_id<?php echo $tab['seekId'];?>').attr('value','<?php echo $resultPf;?>');
							if($('#pf_id<?php echo $tab['seekId'];?>').val() == $('#pfMax<?php echo $tab['seekId'];?>').text())
							{
								$('#pfCell<?php echo $tab['seekId'];?>').text('<?php echo $resultPf.' sur '.$resultPf;?>');
							}
						$('#calc<?php echo $tab['seekId'];?>').css('display','none');
					</script><?php		
					}		
				
		}
		
		if($result==1){
			$SeekManager->updateSeekPlayer($data['playerId'],$data['seekId'],0);
			?><script>$('#imgId<?php echo $data['seekId'];?>').attr('src','<?php echo URL_ROOT.'img/seek_novalid.png';?>');</script><?php
			?><script>$('#calc<?php echo $data['seekId'];?>').css('display','block');</script><?php	
			// vérifier les autres id plus haut
		}
		
		
		
	}
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}


?><script>

</script>