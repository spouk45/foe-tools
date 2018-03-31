<?php 

include ('../config.php');
header(CHARSET);
session_start();
if (!isset($_SESSION['player']))
{
	echo 'Erreur d\'authentification.';
	exit();
}


try{
	if (isset($_POST['tab']))
	{		
		include(DIR_ROOT.'class/ResourcesManager.php');
		include(CONNECT);
		$ResourcesManager=new ResourcesManager($db);
		$ResourcesManager->updateNeed($_POST['tab'],$_SESSION['player']['id']);
	}
	else
	{
		echo ('Erreur de transmission de données.');
	}
	
}
catch(Exception $e)
{
	echo $e->getMessage();
}

?><script>
    $(document).ready(function() {

       $('#updateNeed').css('display','none');

    });
</script>