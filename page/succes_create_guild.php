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
		<p>Votre guilde à été crée.</p>
	</div>
</article>
<?php include(FOOTER);?>