<?php include('../config.php');?>
<?php include(HEAD);?>
<?php include(HEADER);?>

<h2>Accueil</h2>


<?php if(!isset($_SESSION['account']))
{
	echo $ERROR_AUTH;
	exit();
}
?>
<article>
	<div>
		<p>Vous venez de rejoindre une guilde.</p>
		<p>A présent, vous devez attendre qu'un administrateur de la guilde accepte votre candidature.</p>
	</div>
</article>
<?php include(FOOTER);?>