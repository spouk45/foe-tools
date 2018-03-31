<?php include('../../config.php');
/*header(CHARSET);*/
session_start();
 include(DIR_ROOT.'admin/inc/check_admin.php');
 
if(!isset($_POST))
{
	echo '<p>Erreur de récupération des informations.</p>';
	exit();
}

$newsId=$_POST['news_id'];
$commentId=$_POST['id'];
try{
include(DIR_ROOT.'class/NewsManager.php');
include(CONNECT);
$NewsManager=new NewsManager($db);
$NewsManager->deleteComment($newsId,$commentId);
}
catch(Exception $e){
	echo $e->getMessage();
	exit();
}
?>
<script>
$('#comment<?php echo $commentId;?>').css('display','none');
</script>