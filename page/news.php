<?php include('../config.php');?>
<?php include(HEAD);?>
<?php include(HEADER);?>

<h2>Les news</h2>
<?php 
include(DIR_ROOT.'class/NewsManager.php');
try{
include(CONNECT);
$NewsManager=new NewsManager($db);
$data=$NewsManager->readNews();
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}
//print_r($_SESSION);

?>
<article id="news">
		<?php
		if ($data==null){
			echo '<p>Aucune news</p>';
		}
		else {
		foreach($data as $value)
		{
			?><h4><?php echo $value['title'];?></h4>
				<div class="news_content"><?php echo '<p>'.nl2br(htmlentities($value['content'])).'</p>';?></div>
				<div class="foot_news">
					<p class="left back_blue" onclick="add_comment(<?php echo $value['id'];?>);">					
						<span><?php echo $value['countComment'];?> commentaire<?php if($value['countComment']>1){echo 's';}?></span>
					</p>
					
					<p class="right"><?php echo 'Le '.date('d-m-Y',$value['date']);?></p>
					
				</div>	<div class="clear"></div>			
				<div class="news_comment" id="news<?php echo $value['id'];?>">
					<div id="text_comment<?php echo $value['id'];?>"></div>
					<div id="comment_box<?php echo $value['id'];?>"></div>
					<div class="show_box">
						<p>
						<img class="add_comment" onclick="show_add_com(<?php echo $value['id'];?>);" src="<?php echo URL_ROOT.'img/add_com.png';?>" width="10" height="10">
						</p>
						<p class="middle text_error" id="box_error<?php echo $value['id'];?>"></p>
					</div>
					<div id="add_comment<?php echo $value['id'];?>">					
						<p><textarea name="text_comment" id="text_comment_add<?php echo $value['id'];?>"></textarea></p>
						<p><input type="submit" name="ok" value="Ajouter commentaire" onclick="send_comment(getElementById('text_comment_add<?php echo $value['id'];?>').value,<?php echo $value['id'];?>);"></p>
					</div>
				</div>
				
			
		<?php }
		}
?>
</article>
<?php include(FOOTER);?>
<?php 
if(!isset($_SESSION['account']))
{
	$auth=0;
}
else {$auth=1;}
?>
<script>
function add_comment(id)
{
	if(<?php echo $auth;?>==0)
	{
		// $('#box_error'+id).html('Vous devez être connecté pour ajouter un commentaire.');
		//$('.add_comment').css('display','none');
		$('.show_box').css('display','none');
	}
	if($('#news'+id).is(':hidden')){
		$.post("<?php echo URL_ROOT.'proc/read_comment.php';?>",{
				id: id
				},function(html) {$('#text_comment'+id).html(html)}
				);
				
		$('#news'+id).css({
			display:'block'			
		});
		$('#add_comment'+id).css('display','none');
		
	}
	else {
		$('#news'+id).css('display','none');
	}
	
}

function send_comment(comment,id)
{		
		$.post("<?php echo URL_ROOT.'proc/add_comment.php';?>",{
				comment: comment,
				id: id
				},function(html) {
					$('#comment_box'+id).html(html),
				$.post("<?php echo URL_ROOT.'proc/read_comment.php';?>",{
				id: id
				},function(html) {$('#text_comment'+id).html(html)}
				);					
					}
				);
			$('#add_comment'+id).css('display','none');	
			$('#box_loader').css('display','block');
}

function show_add_com(id){
	if($('#add_comment'+id).is(':hidden')){		
				
		$('#add_comment'+id).css({
			display:'block'			
		});	
		
	}
	else {
		$('#add_comment'+id).css('display','none');
	}
}
</script>