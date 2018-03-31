<?php include('../../config.php');
header(CHARSET);
session_start();
 include(DIR_ROOT.'admin/inc/check_admin.php');
 
if(!isset($_POST))
{
	echo '<p>Erreur de récupération des informations.</p>';
	exit();
}
//print_r($_POST);

// récup des données $POST
$data['name']=$_POST['name'];
$data['pf']=$_POST['pf'];
$data['resource1']=$_POST['resource1'];
$data['resource2']=$_POST['resource2'];
$data['nb_resource1']=$_POST['nb_resource1'];
$data['nb_resource2']=$_POST['nb_resource2'];
$data['age']=$_POST['age'];
$data['tabSeek']=$_POST['tabSeek'];
$data['col']=$_POST['col'];
$data['li']=$_POST['li'];

try{
	 include(DIR_ROOT.'admin/class/AdminSeek.php');
	 include(CONNECT);
	 $Seek=new Seek($db);
	 $Seek->addSeek($data);
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
	
	echo 'succès';

?><p><a href="<?php echo URL_ROOT.'admin/add_seek.php';?>">Retour</a></p>