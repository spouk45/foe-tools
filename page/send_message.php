<?php include('../config.php');?>
<?php $dontShowMessage=true;?>
<?php include(HEAD);?>
<?php include(HEADER);?>

<h2>Messagerie</h2>
<?php 
if(!isset($_SESSION['account']) )
{
	echo 'Erreur d\'authentification';
	exit();
}
	// on cherche les joueurs du mondes
if(isset($_SESSION['player']['id']))
{
	$worldId=$_SESSION['world']['id'];

	try{	
		include(CONNECT);	
		//$players=$PlayerManager->getPlayersNameWorld($worldId,$player);
		}
	catch(Exception $e){
		echo $e->getMessage();exit();
		}
		
	/*?><pre id="filterPlayer"><?php print_r($players);?></pre><?php*/
}
?><article id="messanger">
	<div id="boxMessanger">
		<nav id="navMessage">
			<ul>
				<li><a href="<?php echo URL_ROOT.'page/messanger.php';?>">Boite de réception</a></li>
				<li class="current"><a href="<?php echo URL_ROOT.'page/send_message.php';?>">Nouveau message</a></li>				
			</ul>			
		</nav>
		<?php if(!isset($_SESSION['player']['id']))
		{
			?><p>Vous devez vous connecter sur un monde pour envoyer un message.</p><?php
			
		}
		else {
			
		?>
		
		<div id="content">
		
		<p>Destinataires: <input type="text" id="userName" name="userName" readonly placeholder="Selectionnez les noms via la fenêtre de droite"/></p>
		<p>Titre: <input type="text" id="titleMessage" name="titleMessage"></p>		
		<textarea placeholder="Votre message...." id="message"></textarea>
		<p class="button button4 button2" id="sendMessage">Envoyer</p>
		</div>
		<aside>
			<?php if(isset($_SESSION['guild'])){
				?><p id="toGuild" class="button button2">Envoyer à la guilde</p>	
			<?php } ?>
			<p><input type="text" id="seekPlayer" name="seekPlayer" placeholder="Entrez un nom a rechercher..."/></p>				
			<div id="boxPlayers"></div>
			<?php if(isset($_SESSION['guild'])){ ?>
			<p id="seekPlayerGuild" class="button button2">Afficher les joueurs de la guilde</p>
			<?php } ?>
		</aside>
		<?php } ?>
	</div>


</article>
<?php include(FOOTER);?>
<script>
	$(document).ready(function(){
		
		$('#sendMessage').click(function(){
			message=$('#message').val();
			toPlayerName=$('#userName').val();
			titleMessage=$('#titleMessage').val();
			
			if(message==''){
				$('#message').css('border','1px solid red');
			}
			if(toPlayerName=='')
			{
				$('#userName').css('border','1px solid red');
			}
			if(message!='' && toPlayerName!='') {
				
				$.post('<?php echo URL_ROOT.'proc/proc_send_message.php';?>',{
				message:message,
				toPlayerName:toPlayerName,
				titleMessage:titleMessage
				},function(data,status){
					if(data!='')
					{
						alert(data);
					}
					else{
						//alert(status);
						$('#backFont').css('display','inline-block');
						$('#wrapperMessage').html('<p>Message envoyé avec succès</p><img src="<?php echo URL_ROOT.'/img/valid.png';?>" width="40" height="40">');
						setTimeout(reloadPage, 1500);
					}
				
				});
			}
		});
	<?php if(isset($_SESSION['guild'])){?>
		
		
			$('#toGuild').click(function(){
				$.post('<?php echo URL_ROOT.'proc/add_players_guild.php';?>',
					{
						guildId:<?php echo $_SESSION['guild']['id'];?>
					},function(data){
						$('#userName').val(data);
						
					});
			});
			
			$('#seekPlayerGuild').click(function(){
				$.post('<?php echo URL_ROOT.'proc/proc_seek_players_guild.php';?>',
					{
						guildId:<?php echo $_SESSION['guild']['id'];?>
					},function(data){
						$('#boxPlayers').html(data);
					});
			});
	<?php }?>		
		$('#seekPlayer').keyup(function(){
			player=$('#seekPlayer').val();
			if(player!=null)
			{
				$.post('<?php echo URL_ROOT.'proc/proc_seek_players.php';?>',
				{
					player:player
				},function(data){
					$('#boxPlayers').html(data);
				});
			}
		});
		
		/* Ceci est chargé depuis le proc
		$('#boxPlayers ul li').click(function(){
			name=$(this).text();
			alert(name);
		});
		*/
	});
	
	function reloadPage(){
		window.location.reload();
	}
	
	function addName(name){
		contentName=$('#userName').val();
		if(contentName==''){
			$('#userName').val(name);
		}
		else{
			$('#userName').val(contentName+';'+name);
		}
	}
	
</script>