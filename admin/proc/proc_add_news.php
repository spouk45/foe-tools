<?php include('../../config.php');
/*header(CHARSET);*/
session_start();
 include(DIR_ROOT.'admin/inc/check_admin.php');
 
if(!isset($_POST))
{
	echo '<p>Erreur de récupération des informations.</p>';
	exit();
}

$data['title']=$_POST['title'];
$data['content']=$_POST['news_content'];
try{
include(DIR_ROOT.'class/News.php');
$News=new News($data);

include(DIR_ROOT.'class/NewsManager.php');
include(CONNECT);
$NewsManager=new NewsManager($db);
$NewsManager->addNews($News);
}
catch(Exception $e){
	echo $e->getMessage();
	exit();
}
?>

<script>
$(document).ready(function() {
window.location.replace('<?php echo URL_ROOT;?>admin/read_news.php'); 
});
</script>
