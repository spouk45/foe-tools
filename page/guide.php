<?php include('../config.php');?>
<?php include(HEAD);?>
<?php include(HEADER);?>

<h2>Guide d'utilisation</h2>

<article id="guide">

<div id="font">
	<div id="box_img">
		<img id="wrap_img" src="" onclick="closeImg()"/>
	</div>
</div>
<div id="guid_wrapper">
	<div id="links">
		<ul>
			<li><a href="#resource">Ressources</a></li>
			<li><a href="#seek">Recherches</a></li>
		</ul>
	</div>

	<h5><a name="resource" id="resource">Ressources</a></h5>
	<figure>
		<img name="screen_resource" id="screen_resource" onclick="showImg(this);" src="<?php echo URL_ROOT.'img/guid/screen_resource.png';?>" width="500">
		<figcaption>Gestion des ressources</figcaption>
	</figure>
	
		<div class="desc">
			<p>Ici vous pouvez gérer vos ressources comme suit:</p>
			<ul>
			<li><b>Stock:</b> Indiquez ici vos ressources que vous possédez.</li>
			<li><b>Production:</b> Indiquez ici votre production journalière estimée sur 24h. Votre production peut être négative à partir de l'ère moderne.</li>
			<li><b>Besoin:</b> Indiquez ici votre besoin en ressource.</li>
			<li><b>Boost:</b> Cochez la case si vous possédez un bonus de production.</li>
			</ul>
			
			<p>Vous pouvez ensuite retrouver les ressources de tous les membres de la guilde dans la section "guilde".</p>
		</div>
		
		<p class="bold">Conseils pratique:</p>
		<p>Vous pouvez également ouvrir tools for foe dans une nouvelle page 
		afin de superposer la fenetre du jeu avec celle du site de gestion comme l'image ci-dessous.</p>
		<figure>
			<img name="split_resource" id="split_resource" onclick="showImg(this);" src="<?php echo URL_ROOT.'img/guid/split_resource.png';?>" width="500">
			<figcaption>Gestion des ressources en écran partagé</figcaption>
		</figure>
		
	<h5><a name="seek" id="seek">Recherches</a></h5>
	<figure>
		<img name="screen_seek" id="screen_seek" onclick="showImg(this);" src="<?php echo URL_ROOT.'img/guid/screen_seek.png';?>" width="500">
		<figcaption>Gestion des recherches</figcaption>
	</figure>
	<p>Cliquez sur les boutons <img src="<?php echo URL_ROOT.'img/seek_novalid.png';?>" width="15" height="15"> pour valider vos recherches effectuées.
	Nul besoin de les valider une à une, faites directement la dernière, ainsi, les recherches précédentes se rempliront automatiquement.
	Attention, si vous valider une recherche trop en avance avec votre réalité du jeu, vous serez obligé de les retirer une à une. Faites donc bien attention
	à valider votre dernière recherche faite dans le jeu et non la dernière recherche de l'arbre histoire de "s'amuser".</p>
	<br>
	<p>
		En cliquant sur le bouton <img src="<?php echo URL_ROOT.'img/calc.png';?>" width="px" height="15"> un cadre s'ouvre sur la gauche de l'écran.
		Vous y retrouverez toutes les ressources dont vous avez besoin pour réaliser votre recherche ainsi qu'une estimation du temps que cela vous prendra en
		fonction de votre production de points de forges journaliers et de ressources.		
	</p>
	<figure>
		<img name="screen_seek" id="screen_seek" onclick="showImg(this);" src="<?php echo URL_ROOT.'img/guid/seek_calc.png';?>" width="500">
		<figcaption>Sélection des recherches pour calcul</figcaption>
	</figure>
	<figure>
		<img name="seek_ress" id="seek_ress" onclick="showImg(this);" src="<?php echo URL_ROOT.'img/guid/seek_ress.png';?>" width="500">
		<figcaption>Cadre récapitulatif</figcaption>
	</figure>
	
	<p> Et pour finir, dans le cadre de gauche, vous avez un bouton pour mettre automatiquement vos besoins en ressources à jour en un clic.</p>
	
</div>
</article>

<script>
function showImg(truc){
	var id=truc.id;
	var src=truc.src;
	$('#box_img').css('display','inline-block');
	$('#font').css('display','inline-block');
	//$('#font').css('line-height',945);
	$('#wrap_img').attr('src',src);
	
}

function closeImg(){
	$('#box_img').css('display','none');
	$('#font').css('display','none');
}

$(document).ready(function() {
   var haut=$(window).height();
   //alert(haut);
   $('#font').css('line-height',haut+'px');
});
</script>