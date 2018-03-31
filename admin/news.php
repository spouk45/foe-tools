<?php include('../config.php');?>
<?php include(HEAD);?>
<?php include(HEADER);?>
<?php include(DIR_ROOT.'admin/inc/check_admin.php'); ?>
<h2>Gestion des news</h2>

<article id="news">
	<p><a href="<?php echo URL_ROOT.'admin/add_news.php';?>">Ajouter une news</p>
	<p><a href="<?php echo URL_ROOT.'admin/read_news.php';?>">Voir les news</p>
</article>