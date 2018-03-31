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
<article id="succes_registration">
	<div>
		<p>Votre inscription est à présent terminée.</p>
		<p>Vous pouvez maintenant naviguer sur le reste du site.</p>
		<p>N'hésitez pas à parcourir les news et lacher vos commentaires!</p>
	</div>
</article>
<?php include(FOOTER);?>