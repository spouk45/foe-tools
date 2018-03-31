<?php 
include('../config.php');
header(CHARSET);
session_start();
//print_r($_GET);


if(!isset($_SESSION['account']) || !isset($_POST['messageId']))
{
	echo 'Erreur d\'authentification';
	exit();
}
$messageId=$_POST['messageId'];
	// on doit charger les message en tab
$data['accountId']=$_SESSION['account']['id'];
if(isset($_SESSION['player']['id']))
{
	$data['playerId']=$_SESSION['player']['id'];
}
try{
	/*
	include(DIR_ROOT.'/class/Message.php');
	$Message=new Message($data);*/
	include(CONNECT);
	include(DIR_ROOT.'/class/MessageManager.php');
	$MessageManager=new MessageManager($db);
	$messageContent=$MessageManager->getMessageContent($messageId);	
	/*?><pre><?php print_r($messageContent);?></pre><?php*/
	// marqué comme lu les messageges ouvert
	foreach($messageContent as $value)
	{		
		if($value['fromPlayerId']==null)
		{
			$data['playerId']=null;
		}
		if($value['fromAccountId']==null)
		{
			$data['accountId']=null;
		}
		$MessageManager->everRead($data,$value['messageId']);
	}	
}
catch(Exception $e){
	echo $e->getMessage();exit();
	}

/*?><pre><?php print_r($messageContent);?></pre><?php*/

?>
<div id="messageContent">

	<p class="button button2 button3"><a onclick="leaveMessage(<?php echo $messageId;?>);">Quitter la conversation</a></p>

<?php foreach($messageContent as $value)
{
	?><div class="messageLine">
	
		<p class="messageContent <?php if($value['fromName']==$_SESSION['account']['name']){echo 'messageRight';}?>">
		<span class="bold"><?php echo $value['fromName'].':';?></span><br>
		<?php echo $value['message'];?>
	</p>
	<p class="dateMessageInfo <?php if($value['fromName']==$_SESSION['account']['name']){echo 'messageRight';}?>"><?php echo 'le '.date('d/m/Y \à H:i',$value['dateMessage']);?></p></div>
	
	
<?php } ?>
</div>

<div class="respondBox">
	<textarea id="respondMessage" placeholder="Ecrire un message..."></textarea>
</div>
<div class="buttons">
	<p class="button button2 button3"><a onclick="closeMessage(<?php echo $messageId;?>);">Fermer</a></p>
	<p class="button button2 button3"><a onclick="respond();">Répondre</a></p>
</div>
<script>
function scrollBot()
{
	$('#wrapperMessage').scrollTop($('#wrapperMessage').height());
}
$(document).ready(function() {	   
	   $('.loadingMessage').css('display','none');	
		scrollBot();
	});
	

function closeMessage(id)
{
	$('#wrapperMessage').load('<?php echo URL_ROOT.'inc/loading.php';?>');	 	 			 
	closeFont();
	$('#linkId'+id).addClass('everRead');
}

function respond()
{	
	data=$('#respondMessage').val();
	//alert(data);
	if(data=='')
	{	
		$('#respondMessage').css({
			'border':'1px solid red'
		});
	}
	else{
		$.post('<?php echo URL_ROOT.'proc/add_respond.php';?>',{
			data:data,
			link:<?php echo $messageId;?>
			},
			  function(data2, status){
        
		$('#messageContent').html($('#messageContent').html()+'<div class="messageLine"><p class="messageContent messageRight">'+data2+'</p><p class="dateMessageInfo messageRight"><?php echo 'de '.$_SESSION['account']['name'];?></p></div>'),
		$('#respondMessage').val(''),
		scrollBot();
		});
	}
	//$('#respondMessage').css('<?php echo URL_ROOT.'inc/loading.php';?>');
}

function leaveMessage(messageId){
	if(messageId!='')
	{
		$.post('<?php echo URL_ROOT.'proc/leave_message.php';?>',{
			messageId:messageId,			
			},
			  function(data, status){
				if(data!=''){alert(data);}
				else{
					$('#wrapperMessage').load('../inc/loading.php');
					closeFont();
					$('#linkId'+messageId).css('display','none');
				}
		
		});
	}
}

</script>
