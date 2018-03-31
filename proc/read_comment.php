<?php 
include('../config.php');
header(CHARSET);

if(!isset($_POST['id']))
{
	echo 'Erreur de récupération de la news';
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

?><div id="box_loader" class="loader"><img src="<?php echo URL_ROOT.'img/loader.gif';?>" width="10" height="10"></div><?php
foreach($data as $value)
{
	//print_r($value);
	
	?><div class="wrapper_comment">
		<p class="title_comment"><?php echo $value['user'].' - Le '.date('d-m-Y à H:i',$value['date']);?></p>
		<p class="text_comment"><?php echo nl2br(htmlentities($value['content']));?></p>
	</div>
	<?php
}
