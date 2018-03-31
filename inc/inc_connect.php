<?php 
session_start();
	$userName=$_SESSION['account']['name'];
			$userId=$_SESSION['account']['id'];
			?>

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