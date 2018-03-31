<?php include('../config.php');?>
<?php include(HEAD);?>
<?php include(HEADER);?>
<?php include(DIR_ROOT.'admin/inc/check_admin.php'); ?>

<?php 
// il faut récupérer la liste des ressources
try{
	include(DIR_ROOT.'class/ResourcesManager.php');
	include(CONNECT);
	$ResourcesManager=new ResourcesManager($db);
	$data=$ResourcesManager->getListResource();
	$data_age=$ResourcesManager->getListAge();
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}
?>
<h2>Gestion des recherches</h2>
<article id="seek">


<form method="POST" action="<?php echo URL_ROOT.'admin/proc/proc_add_seek.php';?>" id="form_add_seek">
	<p>Nom: <input type="text" name="name" id="name"></p>
	<p>Nombre de Pf: <input type="int" name="pf" id="pf"></p>
	<p>ressource 1: 
		<select name="resource1" id="resource1">
				<option name="empty" id="empty">Aucune</option>
			<?php foreach($data as $value)
			{
				?><option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option><?php
			}
			?>
		</select>
	
	Quantité: <input type="int" name="nb_resource1" id="nb_resource1"></p>
	<p>ressource 2: 
		<select name="resource2" id="resource2">	
			<option name="empty" id="empty">Aucune</option>
			<?php foreach($data as $value)
			{
				?><option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option><?php
			}
			?>
		</select>
	Quantité: <input type="int" name="nb_resource2" id="nb_resource2"></p>
	<p>Age: 
		<select name="age" id="age">				
			<?php foreach($data_age as $value)
			{
				if($value['id']!=1 && $value['id']!=2 && $value['id']!=3 && $value['id']!=4 && $value['id']!=5 && $value['id']!=6 && $value['id']!=7 && $value['id']!=8 && $value['id']!=9 && $value['id']!=10 && $value['id']!=11 && $value['id']!=12 && $value['id']!=13){
					?><option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option><?php
				}				
			}
			?>
		</select>
	</p>
	<p>tab sous forme (1:2:3)
	<input type="text" name="tabSeek" id="tabSeek"></p>
	<p>col<input type="text" name="col" id="col"></p>
	<p>li<input type="text" name="li" id="li"></p>
	<p><input type="submit"></p>
	
</form>

</article>