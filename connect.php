<?php 

try{
	$db=new PDO('mysql:host='.$host.';dbname='.$dbname,'root',$password);
	$db->exec("SET CHARACTER SET utf8");
}
catch (Exception $e){	echo '<p> Erreur SQL:'.$e->getMessage().'<br>';	exit();}