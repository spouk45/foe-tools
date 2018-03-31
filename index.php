
<?php include('config.php');?>

<?php include(HEAD);?>

<?php include(HEADER);?>

<h2>Accueil</h2>

<article id="home">
	<p>Bienvenue sur <strong>Tools for FoE!</strong></p>
	<p><i>Site de gestion pour le jeu en ligne Forge of Empire</i><p>
	<p>Testez les différents outils pour votre gestion du jeux.</p>
	<!--<p>Vous pouvez:</p>
	<ul>
		<li> Gérer vos ressources pour une meilleure organisation dans votre guilde</li>
		<li> Gérer vos Grands Monuments (prochainement) </li>
		<li> Gérer vos recherches pour vous donner une estimation de 
		vos besoins en ressources ainsi qu'une estimation d'une date d'obtention de la recherche selectionnée</li>
		<li> Et plein d'autres choses! </li>
	<ul>-->
	
	<p>... Site en cours de construction ...</p>
	<p>Inscrivez vous, et essayez!</p>
	<p>Vous pouvez parcourir le guide d'utilisation pour plus d'explications: <a class="button3 button" href="<?php echo URL_ROOT.'page/guide.php';?>">Guide</a>.</p>

	<div id="diapo">
		<figure>
			<img name="screen_seek" id="screen_seek" src="<?php echo URL_ROOT.'img/guid/screen_seek.png';?>" width="500">
			<figcaption>Gestion des recherches</figcaption>
		</figure>
		<figure>
			<img name="screen_seek" id="screen_seek" src="<?php echo URL_ROOT.'img/guid/screen_resource.png';?>" width="500">
			<figcaption>Gestion des ressources</figcaption>
		</figure>
		<figure>
			<img name="screen_seek" id="screen_seek" src="<?php echo URL_ROOT.'img/guid/split_resource.png';?>" width="500">
			<figcaption>Gestion des ressources</figcaption>
		</figure>
	</div>
	
</article>

<?php include(FOOTER);?>
