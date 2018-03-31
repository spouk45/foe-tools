<?php 
include('../config.php');
header(CHARSET);

session_start();
if(!isset($_SESSION['account']) ||!isset($_SESSION['player']))
{
	echo ERROR_AUTH;
	exit();
}

if(!isset($_POST['player']))
{
	echo ERROR_DATA;
	exit();
}

$player=$_POST['player'];

try{
	include(CONNECT);
	include(DIR_ROOT.'class/PlayerManager.php');
	$PlayerManager=new PlayerManager($db);
	$data=$PlayerManager->getPlayersNameWorld($_SESSION['world']['id'],$player);
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}
if($data==null){
	exit();
}

?>
<ul>
	<?php foreach($data as $value)
	{
		?><li><?php echo $value;?></li><?php
		
	}
	?>
</ul>
<script>

$('#boxPlayers ul li').click(function(){
		name=$(this).text();		
		addName(name);
	});
</script>

