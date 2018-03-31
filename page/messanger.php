<?php include('../config.php');?>
<?php $dontShowMessage=true;?>
<?php include(HEAD);?>
<?php include(HEADER);?>

<h2>Messagerie</h2>
<?php 
if(!isset($_SESSION['account']))
{
	echo 'Erreur d\'authentification';
	exit();
}
	
$data['accountId']=$_SESSION['account']['id'];
if(isset($_SESSION['player']['id']))
{
	$data['playerId']=$_SESSION['player']['id'];
}
try{	
	include(CONNECT);
	include(DIR_ROOT.'/class/MessageManager.php');
	$MessageManager=new MessageManager($db);
	$messageData=$MessageManager->getMessageList($data);	
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	
/*?><pre><?php print_r($messageData);?></pre><?php*/

?><article id="messanger">
	<div id="boxMessanger">
		<nav id="navMessage">
			<ul>
				<li class="current"><a href="<?php echo URL_ROOT.'page/messanger.php';?>">Boite de réception</a></li>
				<li><a href="<?php echo URL_ROOT.'page/send_message.php';?>">Nouveau message</a></li>				
			</ul>			
		</nav>
		<div id="contentList">
			<ul>
			<?php 
			if($messageData!=null){
				foreach($messageData as $value)
				{					
					?><li id="linkId<?php echo $value['messageId'];?>" class="<?php if($value['everRead']==1){echo 'everRead';}?>" onclick="openMessage(<?php echo $value['messageId'];?>)">
						<p class="titleMessage"><?php 
							if(isset($value['title'])){
								echo $value['title'];
							}
							else { echo 'Sans titre';}
							?>
						</p>
						<p class="fromName"><?php echo 'de '.$value['fromName'];?></p>
						<p class="dateMessage">
							<?php echo 'le '.date('d/m/Y \à H:i',$value['dateMessage']);?>
						</p>
						<div class="clear"></div>
					</li><?php
					
				}
			}
else {
	?><p>Vous n'avez aucun message.</p><?php
}			?>
			
		
			</ul>
		</div>
	</div>

</article>
<?php include(FOOTER);?>
<script>
	function openMessage(linkId)
	{
		$('#backFont').css('display','inline-block');
		/*$('#wrapperMessage').load('<?php echo URL_ROOT.'/inc/read_message.php?messageId=';?>'+linkId);*/
		$.post('<?php echo URL_ROOT.'/inc/read_message.php';?>',{
				messageId:linkId,				
				},function(data,status){					
						$('#wrapperMessage').html(data);
				});
	}
	
</script>
