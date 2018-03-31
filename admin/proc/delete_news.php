<?php include('../../config.php');
session_start();
 include(DIR_ROOT.'admin/inc/check_admin.php'); 

$id=$_POST['id'];
include(DIR_ROOT.'class/NewsManager.php');
try{
include(CONNECT);
$NewsManager=new NewsManager($db);
$data=$NewsManager->deleteNews($id);
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}

echo '<p>News supprimé avec succès</p>';
?><script>
$('#wrap_news<?php echo $id;?>').css('display','none');
</script>