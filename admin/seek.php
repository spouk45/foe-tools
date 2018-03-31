<?php include('../config.php');?>
<?php include(HEAD);?>
<?php include(HEADER);?>
<?php include(DIR_ROOT.'admin/inc/check_admin.php'); ?>
<h2>Gestion des recherches</h2>

<article id="news">
	<p><a href="<?php echo URL_ROOT.'admin/add_seek.php';?>">Ajouter une recherche</p>
	<p><a href="<?php echo URL_ROOT.'admin/read_seek.php';?>">Voir les recherches</p>
</article>