
<nav id="menu">
	<div class="showMenu"><p>≡ Menu</p></div>
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
				<li><a href="<?php echo URL_ROOT.'page/seek.php';?>">Recherches</a></li>
				<li class="menu">
					<a>Grand monument</a>
					<ul  class="sous_menu sous_menu2">
						<li><a href="<?php echo URL_ROOT.'page/gm.php';?>">Mes Grands Monuments</a></li>
						<li><a href="<?php echo URL_ROOT.'page/seek_gm.php';?>">Recherche de Grands Monuments</a></li>
					</ul>
				</li><?php
					
			}
		?><li><a href="<?php echo URL_ROOT.'page/soon.php';?>">Profil</a></li><?php
		} ?>
			
		
	</ul>
	
</nav>

<script>
$(document).ready(function(){
	$('header nav#menu .showMenu').click(function(){
		if($('header nav#menu ul').css('display')=='none')
		{
			$('header nav#menu ul').css('display','block');
		}
		else
		{
			$('header nav#menu ul').css('display','none');
		}
		
	});
});
</script>