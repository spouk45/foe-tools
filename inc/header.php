<div id="wrap">

<header>
		
		<h1><img src="<?php echo URL_ROOT.'img/logo3.png';?>" height="60" alt="FoE Tools"></h1>
		
		<?php 
		include(DIR_ROOT.'class/Account.php');
		session_start();
		// si pas de session on propose la connection 
		if(!isset($_SESSION['account']))
		{
			// affichage du form de connection
			include ('user_connect.php');
		}
		else {
			
			$userName=$_SESSION['account']['name'];
			$userId=$_SESSION['account']['id'];
			?>			
			<div id="connect">
			<?php if(!isset($dontShowMessage))
			{ ?>		
				<div id="messaging">
					<a id="mail" style="background-image:url(<?php echo URL_ROOT.'img/message.png';?>);width:50px;height:36px;" href="<?php echo URL_ROOT.'page/messanger.php';?>">
					<span id="numberMessage"></span></a>
				</div>
			<?php } ?>
				<div>
				<p>Bienvenue <span class="bold"><?php echo $userName;?></span></p>
					<?php if(isset($_SESSION['world']))
					{
						?><p>Monde: <span class="bold"><?php echo $_SESSION['world']['name'];?></span></p><?php
						if(isset($_SESSION['guild']))
						{
							?><p>Guilde:<span class="bold"> <?php echo $_SESSION['guild']['name'];?></span></p><?php
						}	
					} ?>
				
				<a href="<?php echo URL_ROOT.'proc/deconnect.php';?>">Déconnexion</a>
				</div>
			</div>
			
		<?php } ?>	
		<?php include(DIR_ROOT.'inc/nav.php');?>
	
</header>
<div id="main" class="clear">
<div id="messageLoader"></div>


<script>
	function clignote()
	{
		// partir plutot sur animation css
		$('#mail').addClass('mailFade');
		//$('#mail').css('opacity','0.2');
		clearInterval(timerSeek);
	}
	function writeCountMessage(message)
	{
		$('#numberMessage').html(message);
	}
	// vérifier toutes les 2min s'il y a un new message
	function seek_message(){
		//$('#messageLoader').load('<?php echo URL_ROOT.'/proc/seek_message.php';?>');
		$.post( "<?php echo URL_ROOT.'/proc/seek_message.php';?>")
			.done(function( data )
			{
				// data est = a un nombre de message
				if(data!=0)
				{
					clignote();
					writeCountMessage(data);
				}
				
			});
	}
	function startSeekMessage(){
			timerSeek=setInterval(seek_message, 60000); 	
		}
	<?php if(!isset($dontShowMessage)){ ?>
	$(document).ready(function() {
		var timerSeek=null;	
		startSeekMessage();
		seek_message();
	});
	<?php } ?>
</script>