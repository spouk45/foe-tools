<?php 
include('../config.php');
session_start();?>
	<ul>
	<?php 		
	// -------------- Connecté -----------
	?><li><a href="<?php echo URL_ROOT.'index.php';?>">Accueil</a></li><?php
	?><li><a href="<?php echo URL_ROOT.'page/news.php';?>">Les news</a></li><?php
	if(isset($_SESSION['account']))
		{ 
			?><li><a href="<?php echo URL_ROOT.'page/world.php';?>">Les mondes</a></li><?php
		
		// ------------- Connecté à un monde ------------
		if(isset($_SESSION['world']))
			{
				?><li><a href="<?php echo URL_ROOT.'page/resources.php';?>">Ressources</a></li><?php
				?><li class="menu">
					<a>Guilde</a>
					<ul class="sous_menu" >										
						<?php 
						include(DIR_ROOT.'inc/sub_nav_guild.php');
						?>
					</ul>
				</li>
				<li><a href="<?php echo URL_ROOT.'page/seek.php';?>">Recherches</a></li><?php
					
			}
		?><li><a href="#">Profil</a></li><?php
		} ?>
			
		
	</ul>
