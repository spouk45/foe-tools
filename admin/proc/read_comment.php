<?php include('../../config.php');
/*header(CHARSET);*/
session_start();
 include(DIR_ROOT.'admin/inc/check_admin.php');
 
if(!isset($_POST['id']))
{
	echo '<p>Erreur de récupération des informations.</p>';
	exit();
}


try{
	include(DIR_ROOT.'class/NewsManager.php');
	include(CONNECT);
	$NewsManager=new NewsManager($db);
	$data=$NewsManager->readComment($_POST['id']);
	
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}
if($data==null)
{
	exit();
}

foreach($data as $value)
{
	//print_r($value);
	
	?><div class="wrapper_comment" id="comment<?php echo $value['id'];?>">
		<p class="title_comment"><?php echo $value['user'].' - Le '.date('d-m-Y à H:i',$value['date']);?>
			<img onclick="delete_comment(<?php echo $_POST['id'];?>,<?php echo $value['id'];?>);" class="delete" src="<?php echo URL_ROOT.'img/croix_rouge.png';?>" alt="delete" width="10" height="10">
		</p>
		<p class="text_comment"><?php echo nl2br(htmlentities($value['content']));?></p>
	</div>
	<?php
}
